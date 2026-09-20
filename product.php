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
        $categoryID = $row['catID'] ?? ($row['categoryID'] ?? '');
    } else {
        // Agar product na mile toh shop page par redirect kar dein
        header("Location: shop.php");
        exit();
    }
} else {
    header("Location: shop.php");
    exit();
}

$reviewMessage = "";
$reviewType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    if (!isset($_SESSION['userID'])) {
        $reviewMessage = "Please login to submit a review.";
        $reviewType = "warning";
    } else {
        $userID = intval($_SESSION['userID']);
        $rating = isset($_POST['rating']) ? max(1, min(5, floatval($_POST['rating']))) : 5;
        $reviewText = trim($_POST['review'] ?? '');

        if ($reviewText === '') {
            $reviewMessage = "Please write a review before submitting.";
            $reviewType = "warning";
        } else {
            $alreadyReviewed = mysqli_query($conn, "SELECT reviewID FROM product_reviews WHERE productID = '$pID' AND userID = '$userID' LIMIT 1");

            if ($alreadyReviewed && mysqli_num_rows($alreadyReviewed) > 0) {
                $reviewMessage = "You have already reviewed this product.";
                $reviewType = "warning";
            } else {
                $insertQuery = "INSERT INTO product_reviews (productID, userID, rating, review, createdAt) VALUES ('$pID', '$userID', '$rating', '" . mysqli_real_escape_string($conn, $reviewText) . "', NOW())";

                if (mysqli_query($conn, $insertQuery)) {
                    $avgQuery = mysqli_query($conn, "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM product_reviews WHERE productID = '$pID'");
                    if ($avgQuery && $avgRow = mysqli_fetch_assoc($avgQuery)) {
                        $avgRating = round((float) $avgRow['avg_rating'], 1);
                        mysqli_query($conn, "UPDATE products SET Prating = '$avgRating' WHERE pID = '$pID'");
                    }
                    $reviewMessage = "Thank you! Your review has been submitted.";
                    $reviewType = "success";
                } else {
                    $reviewMessage = "Unable to submit review. Please try again.";
                    $reviewType = "danger";
                }
            }
        }
    }
}

include("include/header.php");
include("include/navbar.php");

if (isset($_SESSION['cart_message'])) {
    echo '<div class="container my-3"><div class="alert alert-warning rounded-3">' . htmlspecialchars($_SESSION['cart_message']) . '</div></div>';
    unset($_SESSION['cart_message']);
}

if ($reviewMessage !== '') {
    echo '<div class="container my-3"><div class="alert alert-' . htmlspecialchars($reviewType) . ' rounded-3">' . htmlspecialchars($reviewMessage) . '</div></div>';
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

    <!-- Reviews Section Start -->
    <div class="mt-5 pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Customer Reviews</h3>
                <p class="text-muted small mb-0">Share your feedback for this product</p>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-light h-100">
                    <h5 class="fw-bold text-dark mb-3">Write a Review</h5>

                    <?php if (isset($_SESSION['userID'])) : ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Your Rating</label>
                                <select class="form-select" name="rating" required>
                                    <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="3">3 - Good</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="1">1 - Poor</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Your Review</label>
                                <textarea class="form-control" name="review" rows="4" placeholder="Write your review here..." required></textarea>
                            </div>

                            <button type="submit" name="add_review" class="btn btn-primary rounded-3 px-4">Submit Review</button>
                        </form>
                    <?php else : ?>
                        <div class="alert alert-info rounded-3 mb-0">
                            Please <a href="login.php" class="alert-link">login</a> to write a review.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-7">
                <?php
                $reviewsQuery = "SELECT r.*, u.fullName FROM product_reviews r LEFT JOIN users u ON u.userID = r.userID WHERE r.productID = '$pID' ORDER BY r.createdAt DESC";
                $reviewsResult = mysqli_query($conn, $reviewsQuery);

                if ($reviewsResult && mysqli_num_rows($reviewsResult) > 0) {
                    while ($reviewRow = mysqli_fetch_assoc($reviewsResult)) {
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            $stars .= $i <= round($reviewRow['rating']) ? '<i class="fa-solid fa-star text-warning"></i>' : '<i class="fa-regular fa-star text-warning"></i>';
                        }
                ?>
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($reviewRow['fullName'] ?? 'Customer'); ?></h6>
                                    <div class="text-warning small"><?php echo $stars; ?> <span class="text-muted ms-2"><?php echo number_format((float)$reviewRow['rating'], 1); ?>/5</span></div>
                                </div>
                                <small class="text-muted"><?php echo date('d M Y', strtotime($reviewRow['createdAt'])); ?></small>
                            </div>
                            <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($reviewRow['review'])); ?></p>
                        </div>
                <?php
                    }
                } else {
                    echo "<div class='alert alert-light border rounded-3 text-muted mb-0'>No reviews yet. Be the first to review this product.</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Reviews Section End -->

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
            $relatedQuery = "SELECT * FROM products WHERE status = 'active' AND pID != '$pID'";

            if (!empty($categoryID)) {
                $relatedQuery .= " AND catID = '" . mysqli_real_escape_string($conn, $categoryID) . "'";
            }

            $relatedQuery .= " ORDER BY pID DESC LIMIT 4";
            $relatedResult = mysqli_query($conn, $relatedQuery);

            if (!$relatedResult || mysqli_num_rows($relatedResult) === 0) {
                $fallbackQuery = "SELECT * FROM products WHERE status = 'active' AND pID != '$pID' ORDER BY pID DESC LIMIT 4";
                $relatedResult = mysqli_query($conn, $fallbackQuery);
            }

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