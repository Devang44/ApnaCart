<?php
session_start();
include("config/db.php");

// Check karein user login hai ya nahi
if(!isset($_SESSION['userID'])) {
    $_SESSION['redirect_after_login'] = "orders.php";
    header("Location: login.php");
    exit();
}

$userID = intval($_SESSION['userID']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $cancelOrderID = intval($_POST['order_id']);
    $cancelCheck = mysqli_query($conn, "SELECT orderID, orderStatus FROM orders WHERE orderID = '$cancelOrderID' AND userID = '$userID' LIMIT 1");

    if ($cancelCheck && mysqli_num_rows($cancelCheck) > 0) {
        $orderData = mysqli_fetch_assoc($cancelCheck);
        if (!in_array($orderData['orderStatus'], ['Delivered', 'Cancelled'])) {
            mysqli_query($conn, "UPDATE orders SET orderStatus = 'Cancelled' WHERE orderID = '$cancelOrderID' AND userID = '$userID'");
            $_SESSION['order_message'] = "Order #$cancelOrderID has been cancelled successfully.";
        }
    }

    header("Location: orders.php");
    exit();
}

$page_title = "My Orders | ApnaCart";
include("include/header.php");
include("include/navbar.php");

// Agar specific orderID pass hua ho toh single order details dikhayein
if(isset($_GET['orderID'])) {
    $orderID = intval($_GET['orderID']);
    $ordQuery = mysqli_query($conn, "SELECT * FROM orders WHERE orderID = '$orderID' AND userID = '$userID'");
    
    if($ordQuery && mysqli_num_rows($ordQuery) > 0) {
        $order = mysqli_fetch_assoc($ordQuery);
        
        // Order items fetch karein
        $itemsQuery = "SELECT order_items.*, products.pTitle, products.Pimage1 
                       FROM order_items 
                       JOIN products ON order_items.pID = products.pID 
                       WHERE order_items.orderID = '$orderID'";
        $itemsRes = mysqli_query($conn, $itemsQuery);
        ?>
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-1">Order Details #<?php echo $order['orderID']; ?></h3>
                    <p class="text-muted small mb-0">Placed on <?php echo date('d M Y, h:i A', strtotime($order['orderDate'])); ?></p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="invoice.php?orderID=<?php echo $order['orderID']; ?>" class="btn btn-success rounded-pill px-4 btn-sm">
                        <i class="fa fa-receipt me-1"></i> View Bill
                    </a>
                    <?php if (!in_array($order['orderStatus'], ['Delivered', 'Cancelled'])) : ?>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            <input type="hidden" name="order_id" value="<?php echo $order['orderID']; ?>">
                            <button type="submit" name="cancel_order" class="btn btn-outline-danger rounded-pill px-4 btn-sm">
                                <i class="fa fa-times-circle me-1"></i> Cancel Order
                            </button>
                        </form>
                    <?php endif; ?>
                    <a href="orders.php" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back to Orders
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left: Ordered Products -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Items in this Order</h5>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    while($item = mysqli_fetch_assoc($itemsRes)) { 
                                        $sub = $item['price'] * $item['quantity'];
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/<?php echo htmlspecialchars($item['Pimage1']); ?>" class="rounded-3 me-3 border" style="width: 55px; height: 55px; object-fit: contain;">
                                                    <span class="fw-semibold text-dark"><?php echo htmlspecialchars($item['pTitle']); ?></span>
                                                </div>
                                            </td>
                                            <td>₹<?php echo $item['price']; ?></td>
                                            <td><span class="badge bg-light text-dark border px-3 py-2"><?php echo $item['quantity']; ?></span></td>
                                            <td class="fw-bold text-success">₹<?php echo $sub; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Delivery Address & Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light mb-4">
                        <h5 class="fw-bold mb-3">Delivery Address</h5>
                        <p class="fw-bold mb-1"><?php echo htmlspecialchars($order['fullName']); ?> (<?php echo htmlspecialchars($order['phone']); ?>)</p>
                        <p class="text-muted small mb-0">
                            <?php echo htmlspecialchars($order['address'] . ', ' . $order['city'] . ', ' . $order['state'] . ' - ' . $order['pincode']); ?>
                        </p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                        <h5 class="fw-bold mb-3">Payment & Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment Method</span>
                            <span class="fw-semibold"><?php echo $order['paymentMethod']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment Status</span>
                            <span class="badge bg-success"><?php echo $order['paymentStatus']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Order Status</span>
                            <span class="badge bg-primary"><?php echo $order['orderStatus']; ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-5">Total Paid</span>
                            <span class="fw-bold fs-5 text-success">₹<?php echo $order['totalAmount']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="container my-5 text-center"><h4>Order not found!</h4><a href="orders.php" class="btn btn-primary mt-3">Back to Orders</a></div>';
    }
} else {
    // List all orders of the user
    $ordersQuery = mysqli_query($conn, "SELECT * FROM orders WHERE userID = '$userID' ORDER BY orderID DESC");
    ?>
    <div class="container my-5">
        <?php if (isset($_SESSION['order_message'])) : ?>
            <div class="alert alert-success rounded-3 mb-4">
                <?php echo htmlspecialchars($_SESSION['order_message']); unset($_SESSION['order_message']); ?>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">My Orders</h2>
                <p class="text-muted small mb-0">Track and manage your past orders</p>
            </div>
            <a href="shop.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">
                <i class="fa fa-shopping-bag me-1"></i> Continue Shopping
            </a>
        </div>

        <?php if($ordersQuery && mysqli_num_rows($ordersQuery) > 0) { ?>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($ord = mysqli_fetch_assoc($ordersQuery)) { ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo $ord['orderID']; ?></td>
                                    <td class="text-muted small"><?php echo date('d M Y', strtotime($ord['orderDate'])); ?></td>
                                    <td class="fw-bold text-success">₹<?php echo $ord['totalAmount']; ?></td>
                                    <td><?php echo $ord['paymentMethod']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo strpos($ord['paymentStatus'], 'Paid') !== false ? 'success' : 'warning text-dark'; ?>">
                                            <?php echo $ord['paymentStatus']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $ord['orderStatus']; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                                            <a href="orders.php?orderID=<?php echo $ord['orderID']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                View Details <i class="fa fa-arrow-right ms-1"></i>
                                            </a>
                                            <?php if (!in_array($ord['orderStatus'], ['Delivered', 'Cancelled'])) : ?>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Cancel this order?');">
                                                    <input type="hidden" name="order_id" value="<?php echo $ord['orderID']; ?>">
                                                    <button type="submit" name="cancel_order" class="btn btn-outline-danger btn-sm rounded-pill px-3">Cancel</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } else { ?>
            <div class="text-center py-5 card border-0 shadow-sm rounded-4">
                <div class="card-body py-5">
                    <div class="mb-3 text-primary d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto" style="width: 85px; height: 85px; font-size: 2.2rem;">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Orders Found</h4>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping now!</p>
                    <a href="shop.php" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                        Explore Shop
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
<?php
}
include("include/footer.php");
?>