<?php
session_start();
include("config/db.php");
$page_title = "Shop | ApnaCart";
include("include/header.php"); 
include("include/navbar.php"); 

// Logged-in user ki wishlist products ki list fetch karein (heart toggle karne ke liye)
$userWishlist = [];
if (isset($_SESSION['userID'])) {
    $uID = intval($_SESSION['userID']);
    $wRes = mysqli_query($conn, "SELECT pID FROM wishlist WHERE userID = '$uID'");
    while ($wRow = mysqli_fetch_assoc($wRes)) {
        $userWishlist[] = $wRow['pID'];
    }
}

// Base Query - Table name assume kiya hai 'products' (agar table ka naam kuch aur ho jaise 'tbl_products', toh yahan change kar lena)
$sql = "SELECT * FROM products WHERE status = 'active'";

// Search Query Filter (agar navbar search se query aayi ho)
$search_query = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = trim($_GET['search']);
    $search_safe = mysqli_real_escape_string($conn, $search_query);
    $sql .= " AND (pTitle LIKE '%$search_safe%' OR Pdescription LIKE '%$search_safe%')";
}

$sql .= " ORDER BY pID DESC";
$result = mysqli_query($conn, $sql);
?>

<!-- Shop Page Custom Styling -->
<style>
    .shop-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
        color: #fff;
        border-radius: 16px;
        padding: 35px 30px;
        margin-bottom: 35px;
    }
    .product-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }
    .product-img-wrapper {
        height: 210px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        position: relative;
    }
    .product-img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .product-card:hover .product-img-wrapper img {
        transform: scale(1.05);
    }
    .price-tag {
        font-size: 1.1rem;
        font-weight: 700;
        color: #16a34a;
    }
    .wishlist-btn {
        transition: all 0.2s ease;
    }
    .wishlist-btn:hover {
        transform: scale(1.1);
        background-color: #fff !important;
    }
</style>

<!-- Shop Page Layout -->
<div class="container my-4">
    
    <!-- Hero / Banner Header -->
    <div class="shop-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h2 class="fw-bold mb-1">
                <?php echo !empty($search_query) ? 'Search Results for "' . htmlspecialchars($search_query) . '"' : 'All Products'; ?>
            </h2>
            <p class="text-white-50 mb-0 small">Explore our entire collection of fashion, electronics & accessories</p>
        </div>
        <?php if(!empty($search_query)) { ?>
            <div class="mt-3 mt-md-0">
                <a href="shop.php" class="btn btn-light rounded-pill px-3 py-1.5 fw-semibold small">
                    <i class="fa fa-arrow-left me-1"></i> Clear Search
                </a>
            </div>
        <?php } ?>
    </div>

    <!-- Product Cards Grid (4 columns per row) -->
    <div class="row g-4">
        <?php
        if($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $isWishlisted = in_array($row['pID'], $userWishlist);
        ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden product-card d-flex flex-column">

                        <!-- Product Image with Wrapper & Wishlist Icon Button -->
                        <div class="product-img-wrapper">
                            <img src="images/<?php echo htmlspecialchars($row['Pimage1']); ?>" alt="<?php echo htmlspecialchars($row['pTitle']); ?>">
                            
                            <?php if(isset($row['discount']) && $row['discount'] > 0) { ?>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-3 rounded-pill px-2.5 py-1">
                                    -<?php echo $row['discount']; ?>%
                                </span>
                            <?php } ?>

                            <!-- Wishlist Toggle Button -->
                            <a href="wishlist.php?action=<?php echo $isWishlisted ? 'remove_pid&id=' . $row['pID'] : 'add&id=' . $row['pID']; ?>&return=shop.php" 
                               class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm d-flex align-items-center justify-content-center wishlist-btn" 
                               style="width: 36px; height: 36px; z-index: 5;"
                               title="<?php echo $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>">
                                <i class="<?php echo $isWishlisted ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-dark'; ?>"></i>
                            </a>
                        </div>
                        
                        <div class="card-body d-flex flex-column p-4">
                            <!-- Product Title -->
                            <h5 class="card-title fs-6 fw-bold text-dark mb-2 text-truncate" title="<?php echo htmlspecialchars($row['pTitle']); ?>">
                                <?php echo htmlspecialchars($row['pTitle']); ?>
                            </h5>

                            <?php if (!empty($row['brand'])) { ?>
                                <div class="mb-2 small text-muted">
                                    <span class="fw-semibold">Brand:</span> <?php echo htmlspecialchars($row['brand']); ?>
                                </div>
                            <?php } ?>
                            
                            <!-- Rating if available -->
                            <?php if(isset($row['Prating']) && !empty($row['Prating'])) { ?>
                                <div class="mb-2 text-warning small">
                                    <i class="fa-solid fa-star">0</i> <span><?php echo $row['Prating']; ?></span>
                                </div>
                            <?php } ?>
                            
                            <!-- Product Price -->
                            <div class="d-flex align-items-center justify-content-between mb-3 mt-auto">
                                <span class="price-tag">₹<?php echo number_format($row['Pprice'], 2); ?></span>
                            </div>
                            
                            <!-- View Product Link -->
                            <div class="mt-auto">
                                <a href="product.php?id=<?php echo $row['pID']; ?>" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-eye me-2"></i> View Product
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
        <?php 
            }
        } else {
            // Updated Modern No Products Found Section
            echo '
            <div class="col-12 text-center py-5">
                <div class="card border-0 shadow-sm rounded-4 p-5 mx-auto bg-white" style="max-width: 500px;">
                    <div class="mb-3 text-primary d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto" style="width: 90px; height: 90px; font-size: 2.5rem;">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">No Products Found</h3>
                    <p class="text-muted small mb-4">We couldn\'t find any products matching your search criteria. Try checking your spelling or explore our catalog.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="shop.php" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                            <i class="fa fa-arrow-left me-1"></i> Back to All Products
                        </a>
                    </div>
                </div>
            </div>
            ';
        }
        ?>
    </div>

</div>

<?php include("include/footer.php"); ?>