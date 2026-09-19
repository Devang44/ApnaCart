<?php
session_start();
include("config/db.php");

$orderID = intval($_GET['orderID'] ?? 0);

$page_title = "Order Confirmed | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5 text-center">
    <div class="card border-0 shadow-sm rounded-4 p-5 mx-auto" style="max-width: 550px;">
        <div class="mb-3 text-success d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto" style="width: 90px; height: 90px; font-size: 2.8rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">Order Placed Successfully!</h2>
        <p class="text-muted mb-4">Thank you for your purchase. Your Order ID is <strong>#<?php echo $orderID; ?></strong>.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="invoice.php?orderID=<?php echo $orderID; ?>" class="btn btn-outline-success rounded-pill px-4">View Bill</a>
            <a href="orders.php" class="btn btn-outline-primary rounded-pill px-4">View My Orders</a>
            <a href="shop.php" class="btn btn-primary rounded-pill px-4">Continue Shopping</a>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>