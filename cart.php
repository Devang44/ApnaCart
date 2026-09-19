<?php
session_start();
include("config/db.php");
include("include/offer_functions.php");

// 1. Add to Cart Logic
if(isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    
    // Check karein ki user login hai ya nahi
    if(!isset($_SESSION['userID'])) {
        $pID_val = intval($_GET['id']);
        $_SESSION['redirect_after_login'] = "product-details.php?id=" . $pID_val;
        header("Location: login.php");
        exit();
    }

    $userID = intval($_SESSION['userID']);
    $pID = intval($_GET['id']);

    $productCheck = mysqli_query($conn, "SELECT stock FROM products WHERE pID = '$pID' LIMIT 1");
    if(!$productCheck || mysqli_num_rows($productCheck) === 0) {
        header("Location: shop.php");
        exit();
    }

    $productData = mysqli_fetch_assoc($productCheck);
    $availableStock = (int)($productData['stock'] ?? 0);

    if($availableStock <= 0) {
        $_SESSION['cart_message'] = "This product is out of stock.";
        header("Location: product.php?id=$pID");
        exit();
    }

    $checkQuery = mysqli_query($conn, "SELECT quantity FROM cart WHERE userID = '$userID' AND pID = '$pID' LIMIT 1");
    $currentQty = 0;
    if($checkQuery && mysqli_num_rows($checkQuery) > 0) {
        $currentItem = mysqli_fetch_assoc($checkQuery);
        $currentQty = (int)$currentItem['quantity'];
    }

    $newQty = $currentQty + 1;
    if($newQty > $availableStock) {
        $_SESSION['cart_message'] = "Only $availableStock item(s) left in stock.";
        header("Location: product.php?id=$pID");
        exit();
    }

    if(mysqli_num_rows($checkQuery) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE userID = '$userID' AND pID = '$pID'");
    } else {
        mysqli_query($conn, "INSERT INTO cart (userID, pID, quantity) VALUES ('$userID', '$pID', 1)");
    }

    header("Location: cart.php");
    exit();
}

// 2. Increase Quantity (+ button)
if(isset($_GET['action']) && $_GET['action'] == 'increase' && isset($_GET['cartID'])) {
    if(isset($_SESSION['userID'])) {
        $cartID = intval($_GET['cartID']);
        $userID = intval($_SESSION['userID']);

        $cartItem = mysqli_query($conn, "SELECT cart.quantity, cart.pID, products.stock FROM cart JOIN products ON products.pID = cart.pID WHERE cart.cartID = '$cartID' AND cart.userID = '$userID' LIMIT 1");
        if($cartItem && mysqli_num_rows($cartItem) > 0) {
            $item = mysqli_fetch_assoc($cartItem);
            $availableStock = (int)($item['stock'] ?? 0);
            if($availableStock > 0 && ((int)$item['quantity'] + 1) <= $availableStock) {
                mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE cartID = '$cartID' AND userID = '$userID'");
            } else {
                $_SESSION['cart_message'] = "Only $availableStock item(s) left in stock.";
            }
        }
    }
    header("Location: cart.php");
    exit();
}

// 3. Decrease Quantity (- button)
if(isset($_GET['action']) && $_GET['action'] == 'decrease' && isset($_GET['cartID'])) {
    if(isset($_SESSION['userID'])) {
        $cartID = intval($_GET['cartID']);
        $userID = intval($_SESSION['userID']);
        $res = mysqli_query($conn, "SELECT quantity FROM cart WHERE cartID = '$cartID' AND userID = '$userID'");
        if($res && mysqli_num_rows($res) > 0) {
            $row_item = mysqli_fetch_assoc($res);
            if($row_item['quantity'] > 1) {
                mysqli_query($conn, "UPDATE cart SET quantity = quantity - 1 WHERE cartID = '$cartID' AND userID = '$userID'");
            } else {
                mysqli_query($conn, "DELETE FROM cart WHERE cartID = '$cartID' AND userID = '$userID'");
            }
        }
    }
    header("Location: cart.php");
    exit();
}

// 4. Remove Item from Cart
if(isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['cartID'])) {
    if(isset($_SESSION['userID'])) {
        $cartID = intval($_GET['cartID']);
        $userID = intval($_SESSION['userID']);
        mysqli_query($conn, "DELETE FROM cart WHERE cartID = '$cartID' AND userID = '$userID'");
    }
    header("Location: cart.php");
    exit();
}

$page_title = "Shopping Cart | ApnaCart";
include("include/header.php");
include("include/navbar.php");

if (isset($_SESSION['cart_message'])) {
    echo '<div class="container mt-4"><div class="alert alert-warning rounded-3">' . htmlspecialchars($_SESSION['cart_message']) . '</div></div>';
    unset($_SESSION['cart_message']);
}

$isLoggedIn = isset($_SESSION['userID']);
$cartItems = null;

if($isLoggedIn) {
    $userID = intval($_SESSION['userID']);
    $query = "SELECT cart.cartID, cart.quantity, products.pID, products.pTitle, products.Pprice, products.Pimage1 
              FROM cart 
              JOIN products ON cart.pID = products.pID 
              WHERE cart.userID = '$userID'";
    $cartItems = mysqli_query($conn, $query);
}
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Your Shopping Cart</h2>

    <?php if($isLoggedIn && $cartItems && mysqli_num_rows($cartItems) > 0) { ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal = 0;
                                while($item = mysqli_fetch_assoc($cartItems)) { 
                                    $total = $item['Pprice'] * $item['quantity'];
                                    $subtotal += $total;
                                }
                                $userIDForOffer = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : 0;
                                $bestOffer = getApplicableOfferForCart($conn, $userIDForOffer, $subtotal);
                                $discountAmount = (float) ($bestOffer['amount'] ?? 0);
                                $finalTotal = max(0, $subtotal - $discountAmount);
                                $cartItems = mysqli_query($conn, $query);
                                mysqli_data_seek($cartItems, 0);
                                while($item = mysqli_fetch_assoc($cartItems)) { 
                                    $total = $item['Pprice'] * $item['quantity'];
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="images/<?php echo htmlspecialchars($item['Pimage1']); ?>" class="rounded-3 me-3 border" style="width: 60px; height: 60px; object-fit: contain;">
                                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($item['pTitle']); ?></span>
                                            </div>
                                        </td>
                                        <td>₹<?php echo $item['Pprice']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="cart.php?action=decrease&cartID=<?php echo $item['cartID']; ?>" class="btn btn-outline-secondary btn-sm px-2 py-1">
                                                    <i class="fa-solid fa-minus"></i>
                                                </a>
                                                <span class="mx-3 fw-bold"><?php echo $item['quantity']; ?></span>
                                                <a href="cart.php?action=increase&cartID=<?php echo $item['cartID']; ?>" class="btn btn-outline-secondary btn-sm px-2 py-1">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success">₹<?php echo $total; ?></td>
                                        <td>
                                            <a href="cart.php?action=remove&cartID=<?php echo $item['cartID']; ?>" class="btn btn-outline-danger btn-sm rounded-3">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cart Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">₹<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <?php if ($discountAmount > 0) : ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Offer Discount</span>
                            <span class="fw-semibold text-success">-₹<?php echo number_format($discountAmount, 2); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success fw-semibold">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total Amount</span>
                        <span class="fw-bold fs-5 text-success">₹<?php echo number_format($finalTotal, 2); ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="text-center py-5 card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <i class="fa-solid fa-cart-shopping fs-1 text-muted mb-3"></i>
                <h4>Your cart is empty! (0 items)</h4>
                <p class="text-muted">
                    <?php echo !$isLoggedIn ? 'Please sign in to view your cart items.' : 'Looks like you haven\'t added anything to your cart yet.'; ?>
                </p>
                <?php if(!$isLoggedIn) { ?>
                    <a href="login.php" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold me-2">Sign In</a>
                <?php } ?>
                <a href="shop.php" class="btn btn-outline-primary px-4 py-2 rounded-3 fw-semibold">Shop Now</a>
            </div>
        </div>
    <?php } ?>
</div>

<?php include("include/footer.php"); ?>