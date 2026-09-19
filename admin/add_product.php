<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $title = trim($_POST['pTitle']);
    $description = trim($_POST['Pdescription']);
    $keywords = trim($_POST['Pkeywords']);
    $category = intval($_POST['catID']);
    $brandName = trim($_POST['brand']);
    $price = floatval($_POST['Pprice']);
    $costPrice = floatval($_POST['costPrice']);
    $stock = max(0, intval($_POST['stock'] ?? 0));
    $status = $_POST['status'];
    $rating = floatval($_POST['Prating']);

    $image1 = "default.png";
    if (!empty($_FILES['Pimage1']['name'])) {
        $image1 = basename($_FILES['Pimage1']['name']);
        move_uploaded_file($_FILES['Pimage1']['tmp_name'], "../images/" . $image1);
    }

    $image2 = "default.png";
    if (!empty($_FILES['Pimage2']['name'])) {
        $image2 = basename($_FILES['Pimage2']['name']);
        move_uploaded_file($_FILES['Pimage2']['tmp_name'], "../images/" . $image2);
    }

    $image3 = "default.png";
    if (!empty($_FILES['Pimage3']['name'])) {
        $image3 = basename($_FILES['Pimage3']['name']);
        move_uploaded_file($_FILES['Pimage3']['tmp_name'], "../images/" . $image3);
    }

    $sql = "INSERT INTO products (pTitle, Pdescription, Pkeywords, id, catID, Pimage1, Pimage2, Pimage3, Pprice, costPrice, stock, status, Prating, brand)
            VALUES ('" . mysqli_real_escape_string($conn, $title) . "', '" . mysqli_real_escape_string($conn, $description) . "', '" . mysqli_real_escape_string($conn, $keywords) . "', '$category', '$category', '" . mysqli_real_escape_string($conn, $image1) . "', '" . mysqli_real_escape_string($conn, $image2) . "', '" . mysqli_real_escape_string($conn, $image3) . "', '$price', '$costPrice', '$stock', '" . mysqli_real_escape_string($conn, $status) . "', '$rating', '" . mysqli_real_escape_string($conn, $brandName) . "')";

    if (mysqli_query($conn, $sql)) {
        $message = "Product added successfully.";
        $messageType = "success";
    } else {
        $message = "Failed to add product: " . mysqli_error($conn);
        $messageType = "danger";
    }
}

$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY id ASC");
$brands = mysqli_query($conn, "SELECT * FROM brands ORDER BY brandID ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | ApnaCart</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6fb; }
        .sidebar { background: linear-gradient(180deg, #0f172a, #111827); min-height: 100vh; }
        .nav-link { color: #dbeafe; border-radius: 10px; padding: 10px 14px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.08); color: #fff; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .form-control, .form-select { border-radius: 12px; }
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
                    <a class="nav-link active" href="add_product.php"><i class="fa-solid fa-plus me-2"></i>Add Product</a>
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
                    <div><h4 class="mb-0 fw-bold">Add Product</h4></div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Admin</span>
                </div>

                <div class="p-4">
                    <?php if ($message !== "") : ?>
                        <div class="alert alert-<?php echo $messageType; ?> rounded-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Product Title</label>
                                        <input type="text" class="form-control" name="pTitle" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Category</label>
                                        <select class="form-select" name="catID" required>
                                            <?php while ($cat = mysqli_fetch_assoc($categories)) : ?>
                                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Brand</label>
                                        <select class="form-select" name="brand" required>
                                            <?php while ($brand = mysqli_fetch_assoc($brands)) : ?>
                                                <option value="<?php echo htmlspecialchars($brand['brandName']); ?>"><?php echo htmlspecialchars($brand['brandName']); ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Price</label>
                                        <input type="number" step="0.01" class="form-control" name="Pprice" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" rows="4" name="Pdescription" required></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Keywords</label>
                                        <input type="text" class="form-control" name="Pkeywords" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Rating</label>
                                        <input type="number" step="0.1" min="1" max="5" class="form-control" name="Prating" value="4.5" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Cost Price</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="costPrice" value="0" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Stock</label>
                                        <input type="number" min="0" class="form-control" name="stock" value="0" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" name="status" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Image 1</label>
                                        <input type="file" class="form-control" name="Pimage1" accept="image/*">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Image 2</label>
                                        <input type="file" class="form-control" name="Pimage2" accept="image/*">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Image 3</label>
                                        <input type="file" class="form-control" name="Pimage3" accept="image/*">
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button type="submit" name="add_product" class="btn btn-primary px-4 py-2 rounded-pill"><i class="fa-solid fa-plus me-2"></i>Add Product</button>
                                        <a href="products.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill ms-2">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
