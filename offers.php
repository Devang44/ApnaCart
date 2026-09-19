<?php
session_start();
include("config/db.php");
$page_title = "Special Offers & Coupons | ApnaCart";
include("include/header.php");
include("include/navbar.php");

// User ka type determine karein
$audienceCondition = "targetAudience = 'ALL'";

if(isset($_SESSION['userID'])) {
    $userID = intval($_SESSION['userID']);
    
    // Check karein user ka koi past order hai ya nahi (New User vs Old User)
    $orderCheck = mysqli_query($conn, "SELECT COUNT(*) as totalOrders FROM orders WHERE userID = '$userID'");
    $orderData = mysqli_fetch_assoc($orderCheck);
    
    if($orderData['totalOrders'] == 0) {
        // Agar new user hai, toh ALL + NEW_USER wali offers dikhayein
        $audienceCondition = "(targetAudience = 'ALL' OR targetAudience = 'NEW_USER')";
    } else {
        // Agar purana user hai, toh ALL + VIP (agar applicable ho) dikhayein
        $audienceCondition = "(targetAudience = 'ALL' OR targetAudience = 'VIP')";
    }
} else {
    // Guest user ke liye sirf ALL
    $audienceCondition = "targetAudience = 'ALL'";
}

$query = "SELECT * FROM offers WHERE status = 'active' AND $audienceCondition ORDER BY offerID DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold mb-2">Exclusively For You</span>
        <h2 class="fw-bold">Special Offers & Discount Coupons</h2>
        <p class="text-muted">Personalized deals curated specially for your account!</p>
    </div>

    <div class="row g-4">
        <?php if($result && mysqli_num_rows($result) > 0) { 
            while($offer = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="bg-primary text-white p-4">
                        <span class="badge bg-warning text-dark mb-2"><?php echo htmlspecialchars($offer['badgeText']); ?></span>
                        <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($offer['title']); ?></h3>
                        <p class="small mb-0 text-white-50"><?php echo htmlspecialchars($offer['description']); ?></p>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <p class="text-muted small mb-3">
                                <?php 
                                if($offer['minCartValue'] >  0) {
                                    echo "Valid on minimum order value of ₹" . $offer['minCartValue'] . ".";
                                } else {
                                    echo "No minimum order requirement.";
                                }
                                ?>
                            </p>
                            <div class="bg-light border border-dashed rounded-3 p-2 text-center mb-3">
                                <span class="fw-bold text-primary fs-5"><?php echo htmlspecialchars($offer['couponCode']); ?></span>
                            </div>
                        </div>
                        <a href="shop.php" class="btn btn-outline-primary w-100 rounded-pill fw-semibold">Redeem Now</a>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
            echo '<div class="col-12 text-center py-5"><p class="text-muted">No special offers available for you right now.</p></div>';
        } 
        ?>
    </div>
</div>

<?php include("include/footer.php"); ?>