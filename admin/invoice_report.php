<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = "Profit & Invoice Report | ApnaCart";

$invoiceTotal = 0;
$totalOrders = 0;
$totalCost = 0;
$totalProfit = 0;
$orderRows = [];

$orderSummary = mysqli_query($conn, "SELECT o.orderID, o.userID, o.fullName, o.totalAmount, o.orderDate, o.paymentStatus, o.paymentMethod, o.orderStatus FROM orders o WHERE o.orderStatus = 'Delivered' ORDER BY o.orderID DESC");

if ($orderSummary) {
    $totalOrders = mysqli_num_rows($orderSummary);
    while ($order = mysqli_fetch_assoc($orderSummary)) {
        $orderRows[] = $order;
        $invoiceTotal += (float) $order['totalAmount'];

        $items = mysqli_query($conn, "SELECT oi.quantity, p.costPrice, p.Pprice FROM order_items oi JOIN products p ON p.pID = oi.pID WHERE oi.orderID = '{$order['orderID']}'");
        if ($items) {
            while ($item = mysqli_fetch_assoc($items)) {
                $qty = (int) $item['quantity'];
                $cost = (float) ($item['costPrice'] ?? 0);
                $totalCost += $qty * $cost;
            }
        }
    }
}

$totalProfit = $invoiceTotal - $totalCost;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6fb; }
        .sidebar { background: linear-gradient(180deg, #0f172a, #111827); min-height: 100vh; }
        .nav-link { color: #dbeafe; border-radius: 10px; padding: 10px 14px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.08); color: #fff; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .stat-box { border-radius: 18px; box-shadow: 0 10px 25px rgba(15,23,42,0.06); }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-2 sidebar text-white p-3">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-white text-primary rounded-3 p-2"><i class="fa-solid fa-shop"></i></div>
                    <div>
                        <div class="fw-bold">ApnaCart</div>
                        <small class="text-white-50">Admin Panel</small>
                    </div>
                </div>
                <nav class="nav flex-column gap-2">
                    <a class="nav-link" href="index.php"><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</a>
                    <a class="nav-link" href="products.php"><i class="fa-solid fa-box me-2"></i>Products</a>
                    <a class="nav-link" href="add_product.php"><i class="fa-solid fa-plus me-2"></i>Add Product</a>
                    <a class="nav-link" href="orders.php"><i class="fa-solid fa-bag-shopping me-2"></i>Orders</a>
                    <a class="nav-link" href="users.php"><i class="fa-solid fa-users me-2"></i>Users</a>
                    <a class="nav-link" href="category.php"><i class="fa-solid fa-list me-2"></i>Categories</a>
                    <a class="nav-link" href="subcategory.php"><i class="fa-solid fa-layer-group me-2"></i>Subcategories</a>
                    <a class="nav-link" href="brands.php"><i class="fa-solid fa-tags me-2"></i>Brands</a>
                    <a class="nav-link" href="offers.php"><i class="fa-solid fa-percent me-2"></i>Offers</a>
                    <a class="nav-link active" href="invoice_report.php"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Profit & Invoice</a>
                    <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
                </nav>
            </aside>

            <main class="col-md-10 p-0">
                <div class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
                    <div><h4 class="mb-0 fw-bold">Profit & Invoice Report</h4></div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                </div>

                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Total Orders</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $totalOrders; ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Revenue</div>
                                <div class="fs-3 fw-bold mt-1">₹<?php echo number_format($invoiceTotal, 2); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Cost</div>
                                <div class="fs-3 fw-bold mt-1">₹<?php echo number_format($totalCost, 2); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Profit</div>
                                <div class="fs-3 fw-bold mt-1 text-success">₹<?php echo number_format($totalProfit, 2); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Order Invoice Summary</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Payment</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($orderRows)) : ?>
                                            <?php foreach ($orderRows as $row) : ?>
                                                <tr>
                                                    <td>#<?php echo $row['orderID']; ?></td>
                                                    <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                                                    <td><?php echo date('d M Y', strtotime($row['orderDate'])); ?></td>
                                                    <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                                                    <td class="fw-bold text-success">₹<?php echo number_format((float)$row['totalAmount'], 2); ?></td>
                                                    <td>
                                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['orderStatus']); ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="../invoice.php?orderID=<?php echo $row['orderID']; ?>" class="btn btn-sm btn-outline-primary" target="_blank">View</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">No orders found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
