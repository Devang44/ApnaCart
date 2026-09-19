<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$adminName = $_SESSION['admin_name'] ?? 'ApnaCart Admin';

$usersCount = 0;
$productsCount = 0;
$activeProducts = 0;
$ordersCount = 0;
$revenue = 0;
$pendingOrders = 0;
$brandsCount = 0;
$offersCount = 0;
$categoriesCount = 0;

if ($conn) {
    $usersCount = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
    $productsCount = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];
    $activeProducts = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products WHERE status = 'active'"))['total'];
    $brandsCount = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM brands"))['total'];
    $offersCount = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM offers"))['total'];
    $categoriesCount = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM category"))['total'];

    $ordersTable = mysqli_query($conn, "SHOW TABLES LIKE 'orders'");
    if ($ordersTable && mysqli_num_rows($ordersTable) > 0) {
        $ordersCount = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];
        $revenue = (float) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(totalAmount), 0) AS total FROM orders"))['total'];
        $pendingOrders = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE orderStatus IN ('Pending', 'Processing', 'Shipped', 'Placed')"))['total'];
    }
}

$recentProducts = mysqli_query($conn, "SELECT * FROM products ORDER BY pID DESC LIMIT 5");
$recentUsers = mysqli_query($conn, "SELECT * FROM users ORDER BY userID DESC LIMIT 5");
$recentOrders = mysqli_query($conn, "SELECT * FROM orders ORDER BY orderID DESC LIMIT 5");
$recentOffers = mysqli_query($conn, "SELECT * FROM offers ORDER BY offerID DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ApnaCart</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7fb; }
        .sidebar { background: linear-gradient(180deg, #0f172a, #111827); min-height: 100vh; }
        .nav-link { color: #dfe9ff; border-radius: 10px; padding: 10px 14px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .stat-card { border: none; border-radius: 18px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06); }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; }
        .table td, .table th { vertical-align: middle; }
        .hero-panel { background: linear-gradient(135deg, #1d4ed8, #0f172a); color: white; border-radius: 22px; }
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
                        <small class="text-white-50">Seller Panel</small>
                    </div>
                </div>

                <nav class="nav flex-column gap-2">
                    <a class="nav-link active" href="index.php"><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</a>
                    <a class="nav-link" href="products.php"><i class="fa-solid fa-box me-2"></i>Products</a>
                    <a class="nav-link" href="add_product.php"><i class="fa-solid fa-plus me-2"></i>Add Product</a>
                    <a class="nav-link" href="orders.php"><i class="fa-solid fa-bag-shopping me-2"></i>Orders</a>
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
                    <div>
                        <h4 class="mb-0 fw-bold">Dashboard</h4>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted">Welcome, <?php echo htmlspecialchars($adminName); ?></span>
                        <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                    </div>
                </div>

                <div class="p-4">
                    <div class="hero-panel p-4 mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Store Overview</div>
                            <h3 class="mb-0 fw-bold">ApnaCart Commerce Dashboard</h3>
                        </div>
                        <div class="text-end">
                            <div class="small text-white-50">Total Sales</div>
                            <div class="fw-bold fs-5">₹<?php echo number_format($revenue, 2); ?></div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Total Users</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $usersCount; ?></div>
                                    </div>
                                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-users"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Products</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $productsCount; ?></div>
                                    </div>
                                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-box"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Active Products</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $activeProducts; ?></div>
                                    </div>
                                    <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-circle-check"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Pending Orders</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $pendingOrders; ?></div>
                                    </div>
                                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Categories</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $categoriesCount; ?></div>
                                    </div>
                                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary"><i class="fa-solid fa-list"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Subcategories</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM subcategory"))['total']; ?></div>
                                    </div>
                                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-layer-group"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Brands</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $brandsCount; ?></div>
                                    </div>
                                    <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-tag"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Offers</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $offersCount; ?></div>
                                    </div>
                                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-percent"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card stat-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Orders</div>
                                        <div class="fs-3 fw-bold mt-1"><?php echo $ordersCount; ?></div>
                                    </div>
                                    <div class="stat-icon bg-dark bg-opacity-10 text-dark"><i class="fa-solid fa-bag-shopping"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card shadow-sm border-0 rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Recent Products</h5>
                                        <a href="products.php" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($recentProducts && mysqli_num_rows($recentProducts) > 0) : ?>
                                                    <?php while ($row = mysqli_fetch_assoc($recentProducts)) : ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row['pTitle']); ?></td>
                                                            <td>
                                                                <span class="badge bg-<?php echo ($row['status'] === 'active') ? 'success' : 'secondary'; ?> rounded-pill">
                                                                    <?php echo htmlspecialchars($row['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td>₹<?php echo number_format($row['Pprice'], 2); ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <tr><td colspan="3" class="text-muted text-center py-3">No products found.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card shadow-sm border-0 rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Recent Orders</h5>
                                        <a href="orders.php" class="btn btn-sm btn-outline-primary">View all</a>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Customer</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($recentOrders && mysqli_num_rows($recentOrders) > 0) : ?>
                                                    <?php while ($order = mysqli_fetch_assoc($recentOrders)) : ?>
                                                        <tr>
                                                            <td>#<?php echo $order['orderID']; ?></td>
                                                            <td><?php echo htmlspecialchars($order['fullName']); ?></td>
                                                            <td>₹<?php echo number_format($order['totalAmount'], 2); ?></td>
                                                            <td>
                                                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($order['orderStatus']); ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <tr><td colspan="4" class="text-muted text-center py-3">No orders yet.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-lg-6">
                            <div class="card shadow-sm border-0 rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Recent Offers</h5>
                                        <a href="offers.php" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr><th>Title</th><th>Code</th><th>Value</th></tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($recentOffers && mysqli_num_rows($recentOffers) > 0) : ?>
                                                    <?php while ($offer = mysqli_fetch_assoc($recentOffers)) : ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($offer['title']); ?></td>
                                                            <td><?php echo htmlspecialchars($offer['couponCode']); ?></td>
                                                            <td><?php echo htmlspecialchars($offer['discountType']) . ' ' . number_format($offer['discountValue'], 2); ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <tr><td colspan="3" class="text-muted text-center py-3">No offers found.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card shadow-sm border-0 rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Recent Users</h5>
                                        <a href="users.php" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Mobile</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($recentUsers && mysqli_num_rows($recentUsers) > 0) : ?>
                                                    <?php while ($user = mysqli_fetch_assoc($recentUsers)) : ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($user['fullName']); ?></td>
                                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                            <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                                                            <td>
                                                                <span class="badge bg-<?php echo ($user['status'] === 'Active') ? 'success' : 'secondary'; ?> rounded-pill">
                                                                    <?php echo htmlspecialchars($user['status']); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <tr><td colspan="4" class="text-muted text-center py-3">No users found.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
