<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['toggle_user_status'])) {
    $userID = intval($_GET['toggle_user_status']);
    $userData = mysqli_query($conn, "SELECT status FROM users WHERE userID = '$userID' LIMIT 1");
    if ($userData && mysqli_num_rows($userData) > 0) {
        $row = mysqli_fetch_assoc($userData);
        $newStatus = ($row['status'] === 'Active') ? 'Blocked' : 'Active';
        mysqli_query($conn, "UPDATE users SET status = '$newStatus' WHERE userID = '$userID'");
    }
    header("Location: users.php");
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY userID DESC");
$activeUsers = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE status = 'Active'");
$blockedUsers = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE status = 'Blocked'");

$activeCount = 0;
$blockedCount = 0;
if ($activeUsers && mysqli_num_rows($activeUsers) > 0) {
    $activeCount = (int) mysqli_fetch_assoc($activeUsers)['total'];
}
if ($blockedUsers && mysqli_num_rows($blockedUsers) > 0) {
    $blockedCount = (int) mysqli_fetch_assoc($blockedUsers)['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | ApnaCart</title>
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
                    <a class="nav-link" href="orders.php"><i class="fa-solid fa-bag-shopping me-2"></i>Orders</a>
                    <a class="nav-link active" href="users.php"><i class="fa-solid fa-users me-2"></i>Users</a>
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
                    <div><h4 class="mb-0 fw-bold">Users</h4></div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                </div>

                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Active Users</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $activeCount; ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Blocked Users</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $blockedCount; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Registered Customers</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($users && mysqli_num_rows($users) > 0) : ?>
                                            <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                                                <tr>
                                                    <td>#<?php echo $user['userID']; ?></td>
                                                    <td><?php echo htmlspecialchars($user['fullName']); ?></td>
                                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo ($user['status'] === 'Active') ? 'success' : 'secondary'; ?> rounded-pill">
                                                            <?php echo htmlspecialchars($user['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="users.php?toggle_user_status=<?php echo $user['userID']; ?>" class="btn btn-sm btn-outline-<?php echo ($user['status'] === 'Active') ? 'warning' : 'success'; ?>"><?php echo ($user['status'] === 'Active') ? 'Block' : 'Unblock'; ?></a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>
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
