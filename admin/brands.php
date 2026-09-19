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
    $brandID = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM brands WHERE brandID = '$brandID'");
    header("Location: brands.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_brand'])) {
    $brandName = trim($_POST['brandName']);

    if ($brandName !== '') {
        $sql = "INSERT INTO brands (brandName) VALUES ('" . mysqli_real_escape_string($conn, $brandName) . "')";
        if (mysqli_query($conn, $sql)) {
            $message = "Brand added successfully.";
            $messageType = "success";
        } else {
            $message = "Failed to add brand: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Brand name is required.";
        $messageType = "warning";
    }
}

$brands = mysqli_query($conn, "SELECT * FROM brands ORDER BY brandID DESC");
$brandsCount = mysqli_query($conn, "SELECT COUNT(*) AS total FROM brands");
$totalBrands = 0;
if ($brandsCount && mysqli_num_rows($brandsCount) > 0) {
    $totalBrands = (int) mysqli_fetch_assoc($brandsCount)['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands | ApnaCart</title>
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
                    <a class="nav-link active" href="brands.php"><i class="fa-solid fa-tags me-2"></i>Brands</a>
                    <a class="nav-link" href="offers.php"><i class="fa-solid fa-percent me-2"></i>Offers</a>
                    <a class="nav-link" href="invoice_report.php"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Profit & Invoice</a>
                    <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
                </nav>
            </aside>

            <main class="col-md-10 p-0">
                <div class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
                    <div><h4 class="mb-0 fw-bold">Brands</h4></div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                </div>

                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Total Brands</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $totalBrands; ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if ($message !== "") : ?>
                        <div class="alert alert-<?php echo $messageType; ?> rounded-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Add Brand</h5>
                            <form method="POST" class="row g-3 align-items-end">
                                <div class="col-md-10">
                                    <label class="form-label fw-semibold">Brand Name</label>
                                    <input type="text" class="form-control" name="brandName" placeholder="Enter brand name" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="add_brand" class="btn btn-primary w-100"><i class="fa-solid fa-plus me-2"></i>Add</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Brand List</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Brand Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($brands && mysqli_num_rows($brands) > 0) : ?>
                                            <?php while ($brand = mysqli_fetch_assoc($brands)) : ?>
                                                <tr>
                                                    <td>#<?php echo $brand['brandID']; ?></td>
                                                    <td><?php echo htmlspecialchars($brand['brandName']); ?></td>
                                                    <td>
                                                        <a href="brands.php?delete=<?php echo $brand['brandID']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this brand?');">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr><td colspan="3" class="text-center text-muted py-4">No brands found.</td></tr>
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
