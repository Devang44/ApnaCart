<?php
session_start();
include("config/db.php");

// 1. Check karein ki URL me product ID aayi hai ya nahi
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $pID = mysqli_real_escape_string($conn, $_GET['id']);

    // 2. Database se us product ki details fetch karein
    $query = "SELECT * FROM products WHERE pID = '$pID'";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $page_title = $row['pTitle'] . " | ApnaCart";
        $categoryID = $row['categoryID'] ?? ''; // Agar aapke table me categoryID ho toh related ke liye use hoga
    } else {
        // Agar product na mile toh shop page par redirect kar dein
        header("Location: shop.php");
        exit();
    }
} else {
    header("Location: shop.php");
    exit();
}

include("include/header.php");
include("include/navbar.php");

if (isset($_SESSION['cart_message'])) {
    echo '<div class="container my-3"><div class="alert alert-warning rounded-3">' . htmlspecialchars($_SESSION['cart_message']) . '</div></div>';
    unset($_SESSION['cart_message']);
}
?>

<!-- Product View / Details Section -->
<div class="container my-5">
    <div class="row g-5">
        
        <!-- Left Side: Product Images Gallery -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <!-- Main Display Image -->
                <img id="mainImage" src="images/<?php echo $row['Pimage1']; ?>" class="img-fluid rounded-3 mb-3" style="height: 400px; object-fit: contain;" alt="Product Image">
                
                <!-- Thumbnails (Agar Pimage2 ya Pimage3 available ho) -->
                <div class="d-flex justify-content-center gap-2">
                    <?php if(!empty($row['Pimage1'])) { ?>
                        <img src="images/<?php echo $row['Pimage1']; ?>" class="thumbnail-img rounded-2 border cursor-pointer" style="width: 70px; height: 70px; object-fit: contain;" onclick="changeImage(this.src)">
                    <?php } ?>
                    <?php if(!empty($row['Pimage2'])) { ?>
                        <img src="images/<?php echo $row['Pimage2']; ?>" class="thumbnail-img rounded-2 border cursor-pointer" style="width: 70px; height: 70px; object-fit: contain;" onclick="changeImage(this.src)">
                    <?php } ?>
                    <?php if(!empty($row['Pimage3'])) { ?>
                        <img src="images/<?php echo $row['Pimage3']; ?>" class="thumbnail-img rounded-2 border cursor-pointer" style="width: 70px; height: 70px; object-fit: contain;" onclick="changeImage(this.src)">
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Right Side: Product Details & Actions -->
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <h1 class="fw-bold text-dark mb-2"><?php echo $row['pTitle']; ?></h1>
                
                <!-- Rating display agar ho -->
                <?php if(isset($row['Prating'])) { ?>
                    <div class="mb-3 text-warning small">
                        <i class="fa-solid fa-star"></i> <?php echo $row['Prating']; ?> Rating
                    </div>
                <?php } ?>

                <h3 class="text-success fw-bold mb-4">₹<?php echo $row['Pprice']; ?></h3>

                <?php $availableStock = (int)($row['stock'] ?? 0); ?>
                <div class="mb-3">
                    <span class="badge <?php echo $availableStock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?> px-3 py-2 rounded-pill">
                        <?php echo $availableStock > 0 ? 'In Stock: ' . $availableStock . ' left' : 'Out of Stock'; ?>
                    </span>
                </div>

                <div class="mb-4">
                    <h5 class="fw-semibold text-secondary fs-6">Description</h5>
                    <p class="text-muted leading-relaxed"><?php echo nl2br($row['Pdescription']); ?></p>
                </div>

                <!-- Action Buttons (Add to Cart / Buy Now) -->
                <div class="d-flex gap-3 mb-4">
                    <?php if ($availableStock > 0) : ?>
                        <a href="cart.php?action=add&id=<?php echo $row['pID']; ?>" class="btn btn-warning flex-fill py-3 fw-bold rounded-3 shadow-sm">
                            <i class="fa-solid fa-cart-shopping me-2"></i> Add to Cart
                        </a>
                        <a href="checkout.php?buyNow=1&id=<?php echo $row['pID']; ?>" class="btn btn-primary flex-fill py-3 fw-bold rounded-3 shadow-sm">
                            <i class="fa-solid fa-bolt me-2"></i> Buy Now
                        </a>
                    <?php else : ?>
                        <button class="btn btn-secondary flex-fill py-3 fw-bold rounded-3 shadow-sm" disabled>
                            <i class="fa-solid fa-cart-shopping me-2"></i> Out of Stock
                        </button>
                    <?php endif; ?>
                </div>

                <div class="border-top pt-3 text-muted small">
                    <p class="mb-1"><i class="fa-solid fa-shield-halved text-success me-2"></i> 100% Original Products</p>
                    <p class="mb-0"><i class="fa-solid fa-rotate-left text-primary me-2"></i> Easy 7-day return and exchange policy</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Related Products Section Start -->
    <div class="mt-5 pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Related Products</h3>
                <p class="text-muted small mb-0">You might also like these products</p>
            </div>
            <a href="shop.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">View All</a>
        </div>

        <div class="row g-4">
            <?php
            // Related products query (current pID ko exclude karke 4 products fetch karein)
            $relatedQuery = "SELECT * FROM products WHERE status = 'active' AND pID != '$pID' ORDER BY RAND() LIMIT 4";
            $relatedResult = mysqli_query($conn, $relatedQuery);

            if($relatedResult && mysqli_num_rows($relatedResult) > 0) {
                while($relRow = mysqli_fetch_assoc($relatedResult)) {
            ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden product-card position-relative">
                            <img src="images/<?php echo $relRow['Pimage1']; ?>" class="card-img-top p-3" style="height: 190px; object-fit: contain;" alt="Related Product">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fs-6 fw-bold text-dark text-truncate"><?php echo $relRow['pTitle']; ?></h5>
                                <p class="card-text text-success fw-bold mb-3">₹<?php echo $relRow['Pprice']; ?></p>
                                <div class="mt-auto">
                                    <a href="product.php?id=<?php echo $relRow['pID']; ?>" class="btn btn-primary w-100 rounded-3 btn-sm fw-semibold">View Product</a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                }
            } else {
                echo "<div class='col-12 text-center text-muted py-3'>No related products found.</div>";
            }
            ?>
        </div>
    </div>
    <!-- Related Products Section End -->

</div>

<!-- JavaScript to change main image on thumbnail click -->
<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src;
}
</script>

<?php include("include/footer.php"); ?>