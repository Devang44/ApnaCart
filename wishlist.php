<?php
session_start();
include("config/db.php");

// Check karein ki user login hai ya nahi
if(!isset($_SESSION['userID'])) {
    $_SESSION['redirect_after_login'] = "wishlist.php";
    header("Location: login.php");
    exit();
}

$userID = intval($_SESSION['userID']);

// 1. Add to Wishlist Action
if(isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $pID = intval($_GET['id']);

    // Check karo ki product pehle se wishlist me hai ya nahi
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE userID = '$userID' AND pID = '$pID'");
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO wishlist (userID, pID) VALUES ('$userID', '$pID')");
    }
    header("Location: wishlist.php");
    exit();
}

// 2. Remove from Wishlist Action
if(isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['wishlistID'])) {
    $wishlistID = intval($_GET['wishlistID']);
    mysqli_query($conn, "DELETE FROM wishlist WHERE wishlistID = '$wishlistID' AND userID = '$userID'");
    header("Location: wishlist.php");
    exit();
}

// 3. Move to Cart Action (Wishlist se seedha Cart me bhejna)
if(isset($_GET['action']) && $_GET['action'] == 'move_to_cart' && isset($_GET['wishlistID']) && isset($_GET['pID'])) {
    $wishlistID = intval($_GET['wishlistID']);
    $pID = intval($_GET['pID']);

    // Check karo product cart me pehle se hai ya nahi
    $checkCart = mysqli_query($conn, "SELECT * FROM cart WHERE userID = '$userID' AND pID = '$pID'");
    if(mysqli_num_rows($checkCart) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE userID = '$userID' AND pID = '$pID'");
    } else {
        mysqli_query($conn, "INSERT INTO cart (userID, pID, quantity) VALUES ('$userID', '$pID', 1)");
    }

    // Wishlist se remove kar do
    mysqli_query($conn, "DELETE FROM wishlist WHERE wishlistID = '$wishlistID' AND userID = '$userID'");

    header("Location: wishlist.php");
    exit();
}

$page_title = "My Wishlist | ApnaCart";
include("include/header.php");
include("include/navbar.php");

// Fetch wishlist items for logged in user
$query = "SELECT wishlist.wishlistID, products.pID, products.pTitle, products.Pprice, products.Pimage1 
          FROM wishlist 
          JOIN products ON wishlist.pID = products.pID 
          WHERE wishlist.userID = '$userID' 
          ORDER BY wishlist.wishlistID DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">My Wishlist</h2>
            <p class="text-muted small mb-0">Items you have saved for later</p>
        </div>
        <a href="shop.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">
            <i class="fa fa-shopping-bag me-1"></i> Continue Shopping
        </a>
    </div>

    <?php if($result && mysqli_num_rows($result) > 0) { ?>
        <div class="row g-4">
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden product-card d-flex flex-column">
                        
                        <!-- Product Image -->
                        <div style="height: 200px; background: #f8fafc; display: flex; align-items: center; justify-content: center; padding: 15px; position: relative;">
                            <img src="images/<?php echo htmlspecialchars($row['Pimage1']); ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;" alt="Product">
                            <a href="wishlist.php?action=remove&wishlistID=<?php echo $row['wishlistID']; ?>" class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm text-danger" title="Remove from Wishlist" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-heart"></i>
                            </a>
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fs-6 fw-bold text-dark text-truncate mb-2" title="<?php echo htmlspecialchars($row['pTitle']); ?>">
                                <?php echo htmlspecialchars($row['pTitle']); ?>
                            </h5>
                            <p class="card-text text-success fw-bold fs-5 mb-3">₹<?php echo number_format($row['Pprice'], 2); ?></p>
                            
                            <div class="mt-auto d-flex flex-column gap-2">
                                <a href="wishlist.php?action=move_to_cart&wishlistID=<?php echo $row['wishlistID']; ?>&pID=<?php echo $row['pID']; ?>" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Move to Cart
                                </a>
                                <a href="product-details.php?id=<?php echo $row['pID']; ?>" class="btn btn-outline-secondary w-100 rounded-3 py-1.5 btn-sm fw-semibold">
                                    View Details
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="text-center py-5 card border-0 shadow-sm rounded-4">
            <div class="card-body py-5">
                <div class="mb-3 text-danger d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto" style="width: 85px; height: 85px; font-size: 2.2rem;">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Your Wishlist is Empty</h4>
                <p class="text-muted mb-4">You haven't added any items to your wishlist yet. Explore our shop and save your favorite items!</p>
                <a href="shop.php" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                    Explore Shop
                </a>
            </div>
        </div>
    <?php } ?>
</div>

<?php include("include/footer.php"); ?>