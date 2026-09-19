<?php
session_start();
include("config/db.php");

// Check karein user login hai ya nahi
if(!isset($_SESSION['userID'])) {
    $_SESSION['redirect_after_login'] = "checkout.php";
    header("Location: login.php");
    exit();
}

$userID = intval($_SESSION['userID']);
$buyNowProductID = isset($_GET['buyNow']) ? intval($_GET['id'] ?? 0) : 0;

if ($buyNowProductID > 0) {
    $_SESSION['buy_now_product_id'] = $buyNowProductID;
    $productCheck = mysqli_query($conn, "SELECT stock FROM products WHERE pID = '$buyNowProductID' LIMIT 1");
    if (!$productCheck || mysqli_num_rows($productCheck) === 0) {
        header("Location: shop.php");
        exit();
    }

    $productData = mysqli_fetch_assoc($productCheck);
    if ((int)($productData['stock'] ?? 0) <= 0) {
        $_SESSION['cart_message'] = "This product is out of stock.";
        header("Location: product.php?id=$buyNowProductID");
        exit();
    }
} else {
    unset($_SESSION['buy_now_product_id']);
    $cartCheck = mysqli_query($conn, "SELECT * FROM cart WHERE userID = '$userID'");
    if(mysqli_num_rows($cartCheck) == 0) {
        header("Location: cart.php");
        exit();
    }
}

// Form submit hone par address session me save karke payment.php par bhej do
if(isset($_POST['save_address'])) {
    $_SESSION['shipping_address'] = [
        'fullName' => trim($_POST['fullName']),
        'phone' => trim($_POST['phone']),
        'address' => trim($_POST['address']),
        'city' => trim($_POST['city']),
        'state' => trim($_POST['state']),
        'pincode' => trim($_POST['pincode'])
    ];
    header("Location: payment.php");
    exit();
}

$page_title = "Shipping Address | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: bold;">
                        1
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">Shipping Address</h3>
                        <p class="text-muted small mb-0">Enter your delivery details where you want to receive your order</p>
                    </div>
                </div>

                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control rounded-3 py-2" name="fullName" placeholder="Enter full name" required value="<?php echo htmlspecialchars($_SESSION['userName'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="tel" class="form-control rounded-3 py-2" name="phone" placeholder="Enter mobile number" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Street Address / House No.</label>
                            <textarea class="form-control rounded-3" name="address" rows="3" placeholder="House no, street name, locality..." required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" class="form-control rounded-3 py-2" name="city" placeholder="City" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" class="form-control rounded-3 py-2" name="state" placeholder="State" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" class="form-control rounded-3 py-2" name="pincode" placeholder="Pincode" required>
                        </div>
                        <div class="col-12 mt-4 d-flex justify-content-between align-items-center">
                            <a href="cart.php" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="fa fa-arrow-left me-1"></i> Back to Cart
                            </a>
                            <button type="submit" name="save_address" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold">
                                Proceed to Payment <i class="fa fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>