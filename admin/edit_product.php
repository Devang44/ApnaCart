<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$productID = intval($_GET['id']);
$product = mysqli_query($conn, "SELECT * FROM products WHERE pID = '$productID' LIMIT 1");
if (!$product || mysqli_num_rows($product) === 0) {
    header("Location: products.php");
    exit();
}

$productData = mysqli_fetch_assoc($product);
$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
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

    $img1 = $productData['Pimage1'];
    if (!empty($_FILES['Pimage1']['name'])) {
        $img1 = basename($_FILES['Pimage1']['name']);
        move_uploaded_file($_FILES['Pimage1']['tmp_name'], "../images/" . $img1);
    }

    $img2 = $productData['Pimage2'];
    if (!empty($_FILES['Pimage2']['name'])) {
        $img2 = basename($_FILES['Pimage2']['name']);
        move_uploaded_file($_FILES['Pimage2']['tmp_name'], "../images/" . $img2);
    }

    $img3 = $productData['Pimage3'];
    if (!empty($_FILES['Pimage3']['name'])) {
        $img3 = basename($_FILES['Pimage3']['name']);
        move_uploaded_file($_FILES['Pimage3']['tmp_name'], "../images/" . $img3);
    }

    $sql = "UPDATE products SET
            pTitle = '" . mysqli_real_escape_string($conn, $title) . "',
            Pdescription = '" . mysqli_real_escape_string($conn, $description) . "',
            Pkeywords = '" . mysqli_real_escape_string($conn, $keywords) . "',
            catID = '$category',
            brand = '" . mysqli_real_escape_string($conn, $brandName) . "',
            Pimage1 = '" . mysqli_real_escape_string($conn, $img1) . "',
            Pimage2 = '" . mysqli_real_escape_string($conn, $img2) . "',
            Pimage3 = '" . mysqli_real_escape_string($conn, $img3) . "',
            Pprice = '$price',
            costPrice = '$costPrice',
            stock = '$stock',
            status = '" . mysqli_real_escape_string($conn, $status) . "',
            Prating = '$rating'
            WHERE pID = '$productID'";

    if (mysqli_query($conn, $sql)) {
        $message = "Product updated successfully.";
        $messageType = "success";
        $productData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE pID = '$productID' LIMIT 1"));
    } else {
        $message = "Update failed: " . mysqli_error($conn);
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
    <title>Edit Product | ApnaCart</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6fb; }
        .sidebar { background: linear-gradient(180deg, #0f172a, #111827); min-height: 100vh; }
        .nav-link { color: #dbeafe; border-radius: 10px; padding: 10px 14px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.08); color: #fff; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .form-control, .form-select, textarea { border-radius: 12px; }
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
                    <a class="nav-link active" href="edit_product.php?id=<?php echo $productID; ?>"><i class="fa-solid fa-pen me-2"></i>Edit Product</a>
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
                    <div><h4 class="mb-0 fw-bold">Edit Product</h4></div>
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
                                        <input type="text" class="form-control" name="pTitle" value="<?php echo htmlspecialchars($productData['pTitle']); ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Category</label>
                                        <select class="form-select" name="catID" required>
                                            <?php while ($cat = mysqli_fetch_assoc($categories)) : ?>
                                                <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $productData['catID']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Brand</label>
                                        <select class="form-select" name="brand" required>
                                            <?php while ($brand = mysqli_fetch_assoc($brands)) : ?>
                                                <option value="<?php echo htmlspecialchars($brand['brandName']); ?>" <?php echo ($brand['brandName'] == $productData['brand']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($brand['brandName']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Price</label>
                                        <input type="number" step="0.01" class="form-control" name="Pprice" value="<?php echo $productData['Pprice']; ?>" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" rows="4" name="Pdescription" required><?php echo htmlspecialchars($productData['Pdescription']); ?></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Keywords</label>
                                        <input type="text" class="form-control" name="Pkeywords" value="<?php echo htmlspecialchars($productData['Pkeywords']); ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Rating</label>
                                        <input type="number" step="0.1" min="1" max="5" class="form-control" name="Prating" value="<?php echo $productData['Prating']; ?>" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Cost Price</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="costPrice" value="<?php echo number_format((float)($productData['costPrice'] ?? 0), 2, '.', ''); ?>" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Stock</label>
                                        <input type="number" min="0" class="form-control" name="stock" value="<?php echo (int)($productData['stock'] ?? 0); ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select class="form-select" name="status" required>
                                            <option value="active" <?php echo ($productData['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="inactive" <?php echo ($productData['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Image 1</label>
                                        <input type="file" class="form-control" name="Pimage1" accept="image/*">
                                        <small class="text-muted">Current: <?php echo htmlspecialchars($productData['Pimage1']); ?></small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Image 2</label>
                                        <input type="file" class="form-control" name="Pimage2" accept="image/*">
                                        <small class="text-muted">Current: <?php echo htmlspecialchars($productData['Pimage2']); ?></small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Image 3</label>
                                        <input type="file" class="form-control" name="Pimage3" accept="image/*">
                                        <small class="text-muted">Current: <?php echo htmlspecialchars($productData['Pimage3']); ?></small>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button type="submit" name="update_product" class="btn btn-primary px-4 py-2 rounded-pill"><i class="fa-solid fa-save me-2"></i>Update Product</button>
                                        <a href="products.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill ms-2">Cancel</a>
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
