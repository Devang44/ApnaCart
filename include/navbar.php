<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

// Database connection ko navbar me safely include karne ke liye
if(!isset($conn)) {
    @include(__DIR__ . '/../config/db.php');
}

$cartCount = 0;
$wishlistCount = 0;

if(isset($_SESSION['userID']) && isset($conn)) {
    $uid = intval($_SESSION['userID']);
    
    // Cart Count (Unique Products)
    $cQuery = mysqli_query($conn, "SELECT COUNT(*) as totalProducts FROM cart WHERE userID = '$uid'");
    if($cQuery) {
        $cData = mysqli_fetch_assoc($cQuery);
        $cartCount = $cData['totalProducts'] ?? 0;
    }

    // Wishlist Count
    $wQuery = mysqli_query($conn, "SELECT COUNT(*) as totalWishlist FROM wishlist WHERE userID = '$uid'");
    if($wQuery) {
        $wData = mysqli_fetch_assoc($wQuery);
        $wishlistCount = $wData['totalWishlist'] ?? 0;
    }
}
?>
   
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">

<div class="container">

<a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="index.php">
<i class="bi bi-bag-heart-fill me-2 fs-4"></i>
ApnaCart
</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbar">

<ul class="navbar-nav mx-auto align-items-lg-center">

<li class="nav-item">
<a class="nav-link active" href="index.php">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="shop.php">Shop</a>
</li>

<!-- Categories -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
Categories
</a>
<ul class="dropdown-menu">
<?php
global $conn;
if(isset($conn)) {
    // category table se id aur category_name fetch kar rahe hain
    $catQuery = mysqli_query($conn, "SELECT id, category_name FROM category");
    if($catQuery && mysqli_num_rows($catQuery) > 0) {
        while($catRow = mysqli_fetch_assoc($catQuery)) {
            $catID = $catRow['id'];
            $catName = htmlspecialchars($catRow['category_name']); // <-- Yahan 'category_name' use karna hai
            echo '<li><a class="dropdown-item" href="category.php?id=' . $catID . '">' . $catName . '</a></li>';
        }
    } else {
        echo '<li><a class="dropdown-item" href="#">No Categories Available</a></li>';
    }
}
?>
</ul>
</li>


<!-- Brands -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        Brands
    </a>
    <ul class="dropdown-menu">
        <?php
        global $conn;
        if(isset($conn)) {
            $brandQuery = mysqli_query($conn, "SELECT DISTINCT brandName FROM (
                SELECT TRIM(brand) AS brandName FROM products WHERE brand IS NOT NULL AND TRIM(brand) <> ''
                UNION
                SELECT TRIM(brandName) AS brandName FROM brands WHERE brandName IS NOT NULL AND TRIM(brandName) <> ''
            ) AS combined_brands ORDER BY brandName ASC");
            if($brandQuery && mysqli_num_rows($brandQuery) > 0) {
                while($bRow = mysqli_fetch_assoc($brandQuery)) {
                    $brandName = trim((string)$bRow['brandName']);
                    if($brandName === '') {
                        continue;
                    }
                    echo '<li><a class="dropdown-item" href="brand.php?brand=' . urlencode($brandName) . '">' . htmlspecialchars($brandName) . '</a></li>';
                }
            } else {
                echo '<li><a class="dropdown-item" href="#">No Brands Available</a></li>';
            }
        }
        ?>
    </ul>
</li>

<li class="nav-item">
<a class="nav-link" href="offers.php">Offers</a>
</li>

<li class="nav-item">
<a class="nav-link" href="about.php">About</a>
</li>

<li class="nav-item">
<a class="nav-link" href="contact.php">Contact</a>
</li>

</ul>

<!-- Balanced / Normal Size Search Bar -->
<form class="d-flex me-3 align-items-center" action="shop.php" method="GET">
    <div class="input-group" style="width: 230px;">
        <input class="form-control form-control-sm ps-3" type="search" name="search" placeholder="Search Products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="border-radius: 20px 0 0 20px; font-size: 0.85rem;">
        <button class="btn btn-primary btn-sm px-3" type="submit" style="border-radius: 0 20px 20px 0;">
            <i class="bi bi-search"></i>
        </button>
    </div>
</form>

<div class="d-flex align-items-center">

<a href="wishlist.php" class="btn btn-light position-relative me-2 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px;">
<i class="bi bi-heart" style="font-size: 0.9rem;"></i>
<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
    <?php echo $wishlistCount; ?>
</span>
</a>

<a href="cart.php" class="btn btn-light position-relative me-2 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px;">
<i class="bi bi-cart3" style="font-size: 0.9rem;"></i>
<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 0.65rem;">
    <?php echo $cartCount; ?>
</span>
</a>

<div class="dropdown">

<?php if(isset($_SESSION['userID'])){ ?>

<button class="btn btn-primary btn-sm dropdown-toggle rounded-pill px-3 py-1.5 fw-semibold" type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem;">
<i class="fa fa-user me-1"></i>
<?php echo htmlspecialchars($_SESSION['userName']); ?>
</button>

<ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
<li><a class="dropdown-item" href="profile.php"><i class="fa fa-user me-2 text-primary"></i>My Profile</a></li>
<li><a class="dropdown-item" href="orders.php"><i class="fa fa-box me-2 text-primary"></i>My Orders</a></li>
<li><a class="dropdown-item" href="wishlist.php"><i class="fa fa-heart me-2 text-danger"></i>Wishlist</a></li>
<li><a class="dropdown-item" href="cart.php"><i class="fa fa-shopping-cart me-2 text-warning"></i>Cart</a></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-right-from-bracket me-2"></i>Logout</a></li>
</ul>

<?php } else { ?>

<button class="btn btn-primary btn-sm dropdown-toggle rounded-pill px-3 py-1.5 fw-semibold" type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem;">
<i class="fa fa-user me-1"></i> My Account
</button>

<ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
<li><a class="dropdown-item" href="login.php"><i class="fa fa-right-to-bracket me-2 text-primary"></i>Sign In</a></li>
<li><a class="dropdown-item" href="register.php"><i class="fa fa-user-plus me-2 text-success"></i>Sign Up</a></li>
</ul>

<?php } ?>

</div>

</div>

</div>

</div>

</nav>