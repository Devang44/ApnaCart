<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['toggle_status'])) {
    $pid = intval($_GET['toggle_status']);
    $product = mysqli_query($conn, "SELECT status FROM products WHERE pID = '$pid' LIMIT 1");
    if ($product && mysqli_num_rows($product) > 0) {
        $row = mysqli_fetch_assoc($product);
        $newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';
        mysqli_query($conn, "UPDATE products SET status = '$newStatus' WHERE pID = '$pid'");
    }
    header("Location: products.php");
    exit();
}

if (isset($_GET['delete'])) {
    $pid = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE pID = '$pid'");
    header("Location: products.php");
    exit();
}

$result = mysqli_query($conn, "SELECT p.*, c.category_name, b.brandName FROM products p LEFT JOIN category c ON c.id = COALESCE(p.catID, p.id) LEFT JOIN brands b ON LOWER(TRIM(b.brandName)) = LOWER(TRIM(p.brand)) ORDER BY p.pID DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | ApnaCart</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6fb; }
        .sidebar { background: linear-gradient(180deg, #0f172a, #111827); min-height: 100vh; }
        .nav-link { color: #dbeafe; border-radius: 10px; padding: 10px 14px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.08); color: #fff; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
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
                    <a class="nav-link active" href="products.php"><i class="fa-solid fa-box me-2"></i>Products</a>
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
                    <div><h4 class="mb-0 fw-bold">Products</h4></div>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="add_product.php" class="btn btn-primary btn-sm rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Add Product</a>
                        <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                    </div>
                </div>

                <div class="p-4">
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Inventory Overview</h5>
                                <span class="badge bg-light text-dark border"><?php echo ($result && mysqli_num_rows($result) > 0) ? mysqli_num_rows($result) : 0; ?> Products</span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                                            <?php while ($product = mysqli_fetch_assoc($result)) : ?>
                                                <tr>
                                                    <td>#<?php echo $product['pID']; ?></td>
                                                    <td>
                                                        <?php if (!empty($product['Pimage1'])) : ?>
                                                            <img src="../images/<?php echo htmlspecialchars($product['Pimage1']); ?>" alt="" style="width: 52px; height: 52px; object-fit: cover; border-radius: 10px;">
                                                        <?php else : ?>
                                                            <span class="text-muted">No image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold"><?php echo htmlspecialchars($product['pTitle']); ?></div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></td>
                                                    <td><?php echo htmlspecialchars($product['brandName'] ?? 'N/A'); ?></td>
                                                    <td>₹<?php echo number_format($product['Pprice'], 2); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo ((int)($product['stock'] ?? 0) > 0) ? 'primary' : 'secondary'; ?> rounded-pill">
                                                            <?php echo (int)($product['stock'] ?? 0); ?> left
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?php echo ($product['status'] === 'active') ? 'success' : 'secondary'; ?> rounded-pill">
                                                            <?php echo htmlspecialchars($product['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="edit_product.php?id=<?php echo $product['pID']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                            <a href="products.php?toggle_status=<?php echo $product['pID']; ?>" class="btn btn-sm btn-outline-<?php echo ($product['status'] === 'active') ? 'warning' : 'success'; ?>"><?php echo ($product['status'] === 'active') ? 'Deactivate' : 'Activate'; ?></a>
                                                            <a href="products.php?delete=<?php echo $product['pID']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?');">Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr><td colspan="9" class="text-center text-muted py-4">No products available.</td></tr>
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
