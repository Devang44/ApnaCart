<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$messageType = "";

if (isset($_GET['delete'])) {
    $offerID = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM offers WHERE offerID = '$offerID'");
    header("Location: offers.php");
    exit();
}

if (isset($_GET['toggle_status'])) {
    $offerID = intval($_GET['toggle_status']);
    $offer = mysqli_query($conn, "SELECT status FROM offers WHERE offerID = '$offerID' LIMIT 1");
    if ($offer && mysqli_num_rows($offer) > 0) {
        $row = mysqli_fetch_assoc($offer);
        $newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';
        mysqli_query($conn, "UPDATE offers SET status = '$newStatus' WHERE offerID = '$offerID'");
    }
    header("Location: offers.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_offer'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $couponCode = trim($_POST['couponCode']);
    $discountType = trim($_POST['discountType']);
    $discountValue = floatval($_POST['discountValue']);
    $minCartValue = floatval($_POST['minCartValue']);
    $targetAudience = trim($_POST['targetAudience']);
    $badgeText = trim($_POST['badgeText']);
    $status = trim($_POST['status']);

    if ($title !== '' && $couponCode !== '') {
        $sql = "INSERT INTO offers (title, description, couponCode, discountType, discountValue, minCartValue, targetAudience, badgeText, status)
                VALUES ('" . mysqli_real_escape_string($conn, $title) . "', '" . mysqli_real_escape_string($conn, $description) . "', '" . mysqli_real_escape_string($conn, $couponCode) . "', '" . mysqli_real_escape_string($conn, $discountType) . "', '$discountValue', '$minCartValue', '" . mysqli_real_escape_string($conn, $targetAudience) . "', '" . mysqli_real_escape_string($conn, $badgeText) . "', '" . mysqli_real_escape_string($conn, $status) . "')";

        if (mysqli_query($conn, $sql)) {
            $message = "Offer added successfully.";
            $messageType = "success";
        } else {
            $message = "Failed to add offer: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Offer title and coupon code are required.";
        $messageType = "warning";
    }
}

$offers = mysqli_query($conn, "SELECT * FROM offers ORDER BY offerID DESC");
$offersCount = mysqli_query($conn, "SELECT COUNT(*) AS total FROM offers");
$totalOffers = 0;
if ($offersCount && mysqli_num_rows($offersCount) > 0) {
    $totalOffers = (int) mysqli_fetch_assoc($offersCount)['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers | ApnaCart</title>
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
                    <a class="nav-link" href="users.php"><i class="fa-solid fa-users me-2"></i>Users</a>
                    <a class="nav-link" href="category.php"><i class="fa-solid fa-list me-2"></i>Categories</a>
                    <a class="nav-link" href="subcategory.php"><i class="fa-solid fa-layer-group me-2"></i>Subcategories</a>
                    <a class="nav-link" href="brands.php"><i class="fa-solid fa-tags me-2"></i>Brands</a>
                    <a class="nav-link active" href="offers.php"><i class="fa-solid fa-percent me-2"></i>Offers</a>
                    <a class="nav-link" href="invoice_report.php"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Profit & Invoice</a>
                    <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
                </nav>
            </aside>

            <main class="col-md-10 p-0">
                <div class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
                    <div><h4 class="mb-0 fw-bold">Offers</h4></div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                </div>

                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Total Offers</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $totalOffers; ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if ($message !== "") : ?>
                        <div class="alert alert-<?php echo $messageType; ?> rounded-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Add Offer</h5>
                            <form method="POST" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Coupon Code</label>
                                    <input type="text" class="form-control" name="couponCode" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Type</label>
                                    <select class="form-select" name="discountType">
                                        <option value="Flat">Flat</option>
                                        <option value="Percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Discount Value</label>
                                    <input type="number" step="0.01" class="form-control" name="discountValue" value="0" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Min Cart Value</label>
                                    <input type="number" step="0.01" class="form-control" name="minCartValue" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Audience</label>
                                    <input type="text" class="form-control" name="targetAudience" value="ALL">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Badge Text</label>
                                    <input type="text" class="form-control" name="badgeText" value="Special Offer">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea class="form-control" rows="3" name="description" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="add_offer" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add Offer</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Offer List</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Code</th>
                                            <th>Discount</th>
                                            <th>Min Cart</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($offers && mysqli_num_rows($offers) > 0) : ?>
                                            <?php while ($offer = mysqli_fetch_assoc($offers)) : ?>
                                                <tr>
                                                    <td>#<?php echo $offer['offerID']; ?></td>
                                                    <td><?php echo htmlspecialchars($offer['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($offer['couponCode']); ?></td>
                                                    <td><?php echo htmlspecialchars($offer['discountType']) . ' ' . number_format($offer['discountValue'], 2); ?></td>
                                                    <td>₹<?php echo number_format($offer['minCartValue'], 2); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo ($offer['status'] === 'active') ? 'success' : 'secondary'; ?> rounded-pill"><?php echo htmlspecialchars($offer['status']); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="offers.php?toggle_status=<?php echo $offer['offerID']; ?>" class="btn btn-sm btn-outline-<?php echo ($offer['status'] === 'active') ? 'warning' : 'success'; ?>"><?php echo ($offer['status'] === 'active') ? 'Disable' : 'Enable'; ?></a>
                                                            <a href="offers.php?delete=<?php echo $offer['offerID']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this offer?');">Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr><td colspan="7" class="text-center text-muted py-4">No offers found.</td></tr>
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
