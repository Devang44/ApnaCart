<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    $orderID = intval($_POST['order_id']);
    $status = $_POST['status'];
    if ($status !== '') {
        mysqli_query($conn, "UPDATE orders SET orderStatus = '" . mysqli_real_escape_string($conn, $status) . "' WHERE orderID = '$orderID'");
    }
    header("Location: orders.php");
    exit();
}

$orders = null;
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'orders'");
if ($tableCheck && mysqli_num_rows($tableCheck) > 0) {
    $orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY orderID DESC LIMIT 50");
}

$stats = [
    'total' => 0,
    'pending' => 0,
    'shipped' => 0,
    'delivered' => 0,
    'revenue' => 0
];

if ($orders !== null) {
    $totalQ = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
    $stats['total'] = (int) mysqli_fetch_assoc($totalQ)['total'];

    $pendingQ = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE orderStatus IN ('Pending', 'Processing', 'Shipped')");
    $stats['pending'] = (int) mysqli_fetch_assoc($pendingQ)['total'];

    $shippedQ = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE orderStatus = 'Shipped'");
    $stats['shipped'] = (int) mysqli_fetch_assoc($shippedQ)['total'];

    $deliveredQ = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE orderStatus = 'Delivered'");
    $stats['delivered'] = (int) mysqli_fetch_assoc($deliveredQ)['total'];

    $revenueQ = mysqli_query($conn, "SELECT COALESCE(SUM(totalAmount), 0) AS total FROM orders WHERE orderStatus = 'Delivered'");
    $stats['revenue'] = (float) mysqli_fetch_assoc($revenueQ)['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | ApnaCart</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6fb; }
        .sidebar { background: linear-gradient(180deg, #0f172a, #111827); min-height: 100vh; }
        .nav-link { color: #dbeafe; border-radius: 10px; padding: 10px 14px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.08); color: #fff; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .table td, .table th { vertical-align: middle; }
        .stat-box { border-radius: 16px; box-shadow: 0 10px 25px rgba(15,23,42,0.06); }
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
                    <a class="nav-link active" href="orders.php"><i class="fa-solid fa-bag-shopping me-2"></i>Orders</a>
                    <a class="nav-link" href="users.php"><i class="fa-solid fa-users me-2"></i>Users</a>
                    <a class="nav-link" href="category.php"><i class="fa-solid fa-list me-2"></i>Categories</a>
                    <a class="nav-link" href="subcategory.php"><i class="fa-solid fa-layer-group me-2"></i>Subcategories</a>
                    <a class="nav-link" href="brands.php"><i class="fa-solid fa-tags me-2"></i>Brands</a>
                    <a class="nav-link" href="offers.php"><i class="fa-solid fa-percent me-2"></i>Offers</a>
                    <a class="nav-link" href="invoice_report.php"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Profit & Invoice</a>
                    <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
                </nav>
            </aside>

            <main class="col-md-10 p-0">
                <div class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
                    <div><h4 class="mb-0 fw-bold">Orders</h4></div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                </div>

                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Total Orders</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $stats['total']; ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Pending</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $stats['pending']; ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Shipped</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $stats['shipped']; ?></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Revenue</div>
                                <div class="fs-3 fw-bold mt-1">₹<?php echo number_format($stats['revenue'], 2); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Recent Orders</h5>
                            <?php if ($orders && mysqli_num_rows($orders) > 0) : ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Phone</th>
                                                <th>Amount</th>
                                                <th>Payment</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($order = mysqli_fetch_assoc($orders)) : ?>
                                                <tr>
                                                    <td>#<?php echo $order['orderID']; ?></td>
                                                    <td><?php echo htmlspecialchars($order['fullName']); ?></td>
                                                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                                    <td>₹<?php echo number_format($order['totalAmount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($order['paymentMethod']); ?></td>
                                                    <td>
                                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($order['orderStatus']); ?></span>
                                                    </td>
                                                    <td><?php echo date('d M Y', strtotime($order['orderDate'])); ?></td>
                                                    <td>
                                                        <form method="POST" class="d-flex gap-2 align-items-center">
                                                            <input type="hidden" name="order_id" value="<?php echo $order['orderID']; ?>">
                                                            <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                                                <option value="Pending" <?php echo ($order['orderStatus'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="Processing" <?php echo ($order['orderStatus'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                                                <option value="Shipped" <?php echo ($order['orderStatus'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                                                <option value="Delivered" <?php echo ($order['orderStatus'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                                                <option value="Cancelled" <?php echo ($order['orderStatus'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                            </select>
                                                            <button class="btn btn-sm btn-primary" type="submit" name="update_order_status">Update</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-info mb-0 rounded-3">No orders have been placed yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
