<?php
session_start();
include("config/db.php");
include("include/offer_functions.php");

if(!isset($_SESSION['userID']) || !isset($_SESSION['shipping_address'])) {
    header("Location: cart.php");
    exit();
}

$userID = intval($_SESSION['userID']);
$addr = $_SESSION['shipping_address'];
$paymentMethod = $_POST['paymentMethod'] ?? 'COD';
$paymentStatus = isset($_POST['razorpay_payment_id']) ? 'Paid (Razorpay)' : 'Pending (COD)';
$buyNowProductID = intval($_SESSION['buy_now_product_id'] ?? 0);

$items = [];
if ($buyNowProductID > 0) {
    $productRes = mysqli_query($conn, "SELECT pID, Pprice, stock, pTitle FROM products WHERE pID = '$buyNowProductID' LIMIT 1");
    if (!$productRes || mysqli_num_rows($productRes) === 0) {
        header("Location: shop.php");
        exit();
    }

    $product = mysqli_fetch_assoc($productRes);
    if ((int)($product['stock'] ?? 0) <= 0) {
        $_SESSION['cart_message'] = "This product is out of stock.";
        header("Location: product.php?id=$buyNowProductID");
        exit();
    }

    $items[] = [
        'pID' => (int)$product['pID'],
        'Pprice' => (float)$product['Pprice'],
        'quantity' => 1,
        'pTitle' => $product['pTitle']
    ];
    $totalAmount = (float)$product['Pprice'];
} else {
    $query = "SELECT cart.quantity, products.pID, products.Pprice 
              FROM cart 
              JOIN products ON cart.pID = products.pID 
              WHERE cart.userID = '$userID'";
    $res = mysqli_query($conn, $query);

    $totalAmount = 0;
    while($row = mysqli_fetch_assoc($res)) {
        $totalAmount += ($row['Pprice'] * $row['quantity']);
        $items[] = $row;
    }
}

$bestOffer = getApplicableOfferForCart($conn, $userID, $totalAmount);
$totalAmount = max(0, $totalAmount - (float) ($bestOffer['amount'] ?? 0));

if(empty($items)) {
    header("Location: cart.php");
    exit();
}

foreach($items as $item) {
    $productStmt = mysqli_query($conn, "SELECT stock FROM products WHERE pID = '" . intval($item['pID']) . "' LIMIT 1");
    if(!$productStmt || mysqli_num_rows($productStmt) === 0) {
        $_SESSION['cart_message'] = "One of the products is no longer available.";
        header("Location: cart.php");
        exit();
    }

    $productData = mysqli_fetch_assoc($productStmt);
    if((int)$productData['stock'] < (int)$item['quantity']) {
        $_SESSION['cart_message'] = "Not enough stock for one or more items in your cart.";
        header("Location: cart.php");
        exit();
    }
}

// 2. Orders table me insert karein
$fullName = mysqli_real_escape_string($conn, $addr['fullName']);
$phone = mysqli_real_escape_string($conn, $addr['phone']);
$address = mysqli_real_escape_string($conn, $addr['address']);
$city = mysqli_real_escape_string($conn, $addr['city']);
$state = mysqli_real_escape_string($conn, $addr['state']);
$pincode = mysqli_real_escape_string($conn, $addr['pincode']);

$orderSql = "INSERT INTO orders (userID, fullName, phone, address, city, state, pincode, totalAmount, paymentMethod, paymentStatus) 
             VALUES ('$userID', '$fullName', '$phone', '$address', '$city', '$state', '$pincode', '$totalAmount', '$paymentMethod', '$paymentStatus')";

if(mysqli_query($conn, $orderSql)) {
    $orderID = mysqli_insert_id($conn);

    // 3. order_items table me items insert karein
    foreach($items as $item) {
        $pID = $item['pID'];
        $price = $item['Pprice'];
        $qty = $item['quantity'];
        mysqli_query($conn, "INSERT INTO order_items (orderID, pID, price, quantity) VALUES ('$orderID', '$pID', '$price', '$qty')");
        mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE pID = '$pID'");
    }

    // 4. Cart khali karein
    if ($buyNowProductID <= 0) {
        mysqli_query($conn, "DELETE FROM cart WHERE userID = '$userID'");
    }

    // 5. Shipping address session unset karein
    unset($_SESSION['shipping_address']);
    unset($_SESSION['buy_now_product_id']);

    // 6. Order Success page par redirect karein
    header("Location: order-success.php?orderID=" . $orderID);
    exit();
} else {
    echo "Error placing order: " . mysqli_error($conn);
}