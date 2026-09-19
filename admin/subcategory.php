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
    $subCategoryID = intval($_GET['delete']);
    if ($subCategoryID > 0) {
        mysqli_query($conn, "DELETE FROM subcategory WHERE catID = '$subCategoryID'");
    }
    header("Location: subcategory.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subcategory'])) {
    $parentCategoryID = intval($_POST['parent_category_id']);
    $subCategoryTitle = trim($_POST['categoryTitle']);

    if ($parentCategoryID > 0 && $subCategoryTitle !== '') {
        $sql = "INSERT INTO subcategory (id, categoryTitle) VALUES ('" . mysqli_real_escape_string($conn, $parentCategoryID) . "', '" . mysqli_real_escape_string($conn, $subCategoryTitle) . "')";
        if (mysqli_query($conn, $sql)) {
            $message = "Subcategory added successfully.";
            $messageType = "success";
        } else {
            $message = "Failed to add subcategory: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Please select a category and enter a subcategory name.";
        $messageType = "warning";
    }
}

$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY id ASC");
$subcategories = mysqli_query($conn, "SELECT s.*, c.category_name FROM subcategory s LEFT JOIN category c ON c.id = s.id ORDER BY s.catID DESC");
$totalSubcategories = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM subcategory"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategories | ApnaCart</title>
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
                    <a class="nav-link active" href="subcategory.php"><i class="fa-solid fa-layer-group me-2"></i>Subcategories</a>
                    <a class="nav-link" href="brands.php"><i class="fa-solid fa-tags me-2"></i>Brands</a>
                    <a class="nav-link" href="offers.php"><i class="fa-solid fa-percent me-2"></i>Offers</a>
                    <a class="nav-link" href="invoice_report.php"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Profit & Invoice</a>
                    <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
                </nav>
            </aside>

            <main class="col-md-10 p-0">
                <div class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
                    <div><h4 class="mb-0 fw-bold">Subcategories</h4></div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                </div>

                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card stat-box p-3 border-0">
                                <div class="text-muted small">Total Subcategories</div>
                                <div class="fs-3 fw-bold mt-1"><?php echo $totalSubcategories; ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if ($message !== "") : ?>
                        <div class="alert alert-<?php echo $messageType; ?> rounded-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Add Subcategory</h5>
                            <form method="POST" class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Parent Category</label>
                                    <select class="form-select" name="parent_category_id" required>
                                        <option value="">Select Category</option>
                                        <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
                                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Subcategory Name</label>
                                    <input type="text" class="form-control" name="categoryTitle" placeholder="Enter subcategory name" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="add_subcategory" class="btn btn-primary w-100"><i class="fa-solid fa-plus me-2"></i>Add</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Subcategory List</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Parent Category</th>
                                            <th>Subcategory Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($subcategories && mysqli_num_rows($subcategories) > 0) : ?>
                                            <?php while ($sub = mysqli_fetch_assoc($subcategories)) : ?>
                                                <tr>
                                                    <td>#<?php echo $sub['catID']; ?></td>
                                                    <td><?php echo htmlspecialchars($sub['category_name'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($sub['categoryTitle']); ?></td>
                                                    <td>
                                                        <a href="subcategory.php?delete=<?php echo $sub['catID']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this subcategory?');">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr><td colspan="4" class="text-center text-muted py-4">No subcategories found.</td></tr>
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
