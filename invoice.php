<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['userID'])) {
    $_SESSION['redirect_after_login'] = "invoice.php?orderID=" . ($_GET['orderID'] ?? '');
    header("Location: login.php");
    exit();
}

$userID = intval($_SESSION['userID']);
$orderID = intval($_GET['orderID'] ?? 0);

if ($orderID <= 0) {
    header("Location: orders.php");
    exit();
}

$orderQuery = mysqli_query($conn, "SELECT * FROM orders WHERE orderID = '$orderID' AND userID = '$userID' LIMIT 1");
if (!$orderQuery || mysqli_num_rows($orderQuery) === 0) {
    header("Location: orders.php");
    exit();
}

$order = mysqli_fetch_assoc($orderQuery);
$itemsQuery = mysqli_query($conn, "SELECT oi.*, p.pTitle, p.Pimage1 FROM order_items oi JOIN products p ON p.pID = oi.pID WHERE oi.orderID = '$orderID'");

$grandTotal = 0;
while ($item = mysqli_fetch_assoc($itemsQuery)) {
    $grandTotal += ((float)$item['price'] * (int)$item['quantity']);
}

$taxRate = 0.05;
$taxAmount = round($grandTotal * $taxRate, 2);
$shipping = 0.00;
$finalAmount = (float)$order['totalAmount'];

$page_title = "Invoice #" . $orderID . " | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<style>
    .invoice-box {
        background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
    }

    .company-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: #fff;
        border-radius: 1.25rem 1.25rem 0 0;
    }

    @media print {
        body {
            background: #fff !important;
        }
        .navbar,
        .footer,
        .btn,
        .topbar,
        .no-print {
            display: none !important;
        }
        .invoice-box {
            box-shadow: none !important;
            border: 0 !important;
        }
        .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>

<div class="container my-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden invoice-box">
        <div class="company-header p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="fw-bold" style="font-size: 1.5rem;">ApnaCart</div>
                    <div class="small opacity-75">Online Shopping Store</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold">Invoice</div>
                    <div class="small opacity-75">#<?php echo $order['orderID']; ?></div>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 border-bottom pb-4 mb-4">
                <div>
                    <div class="text-uppercase text-muted small fw-bold">Company Details</div>
                    <div class="fw-semibold mt-2">ApnaCart Pvt. Ltd.</div>
                    <div class="text-muted small">12 Market Road, Delhi, India</div>
                    <div class="text-muted small">GSTIN: 29ABCDE1234F1Z5</div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">Invoice Date</div>
                    <div class="text-muted small"><?php echo date('d M Y, h:i A', strtotime($order['orderDate'])); ?></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="bg-light rounded-4 p-3 h-100">
                        <h6 class="fw-bold text-uppercase text-muted mb-3">Billing To</h6>
                        <p class="mb-1 fw-bold"><?php echo htmlspecialchars($order['fullName']); ?></p>
                        <p class="mb-1"><?php echo htmlspecialchars($order['phone']); ?></p>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                        <p class="mb-0"><?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' - ' . $order['pincode']); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light rounded-4 p-3 h-100">
                        <h6 class="fw-bold text-uppercase text-muted mb-3">Order Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Payment Method</span>
                            <span class="fw-semibold"><?php echo htmlspecialchars($order['paymentMethod']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Payment Status</span>
                            <span class="fw-semibold"><?php echo htmlspecialchars($order['paymentStatus']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Order Status</span>
                            <span class="fw-semibold"><?php echo htmlspecialchars($order['orderStatus']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <span class="fw-bold text-success">₹<?php echo number_format((float)$finalAmount, 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $itemsQuery = mysqli_query($conn, "SELECT oi.*, p.pTitle, p.Pimage1 FROM order_items oi JOIN products p ON p.pID = oi.pID WHERE oi.orderID = '$orderID'");
                        $grandTotal = 0;
                        while ($item = mysqli_fetch_assoc($itemsQuery)) {
                            $subtotal = (float)$item['price'] * (int)$item['quantity'];
                            $grandTotal += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="images/<?php echo htmlspecialchars($item['Pimage1']); ?>" class="rounded-3 border me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <span class="fw-semibold"><?php echo htmlspecialchars($item['pTitle']); ?></span>
                                    </div>
                                </td>
                                <td>₹<?php echo number_format((float)$item['price'], 2); ?></td>
                                <td><?php echo (int)$item['quantity']; ?></td>
                                <td class="text-end fw-bold text-success">₹<?php echo number_format((float)$subtotal, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <div class="w-100" style="max-width: 360px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₹<?php echo number_format((float)$grandTotal, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>GST (5%)</span>
                        <span>₹<?php echo number_format((float)$taxAmount, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Grand Total</span>
                        <span class="text-success">₹<?php echo number_format((float)$finalAmount, 2); ?></span>
                    </div>
                </div>
            </div>

            <div class="alert alert-light border mt-4 mb-0 rounded-4 text-muted small">
                Thank you for shopping with ApnaCart. This invoice is generated for your order and is valid for record purposes.
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top flex-wrap gap-3 no-print">
                <a href="orders.php" class="btn btn-outline-secondary rounded-pill px-4">Back to Orders</a>
                <button class="btn btn-primary rounded-pill px-4" onclick="window.print()">
                    <i class="fa fa-print me-2"></i> Print Bill
                </button>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>
