<?php
include "config/db.php";
include "include/header.php";
include "include/navbar.php";

$brand = isset($_GET['brand']) ? trim((string) $_GET['brand']) : '';
$brandSafe = mysqli_real_escape_string($conn, $brand);

$product = mysqli_query($conn, "SELECT * FROM products WHERE TRIM(COALESCE(brand, '')) = '$brandSafe' ORDER BY pID DESC");
?>

<div class="container my-4">
    <div class="mb-4">
        <h2 class="fw-bold">Brand: <?php echo htmlspecialchars($brand); ?></h2>
    </div>

    <div class="row">
        <?php 
        if($product && mysqli_num_rows($product) > 0) {
            while($row = mysqli_fetch_assoc($product)){ 
        ?>

        <div class="col-md-3 mb-4">

            <a href="product.php?id=<?php echo $row['pID']; ?>" class="text-decoration-none text-dark">

                <div class="card shadow h-100 product-card">

                    <img src="images/<?php echo htmlspecialchars($row['Pimage1']); ?>"
                         class="card-img-top"
                         style="height:220px; object-fit:contain;">

                    <div class="card-body">

                        <h5><?php echo htmlspecialchars($row['pTitle']); ?></h5>

                        <h4 class="text-primary">
                            ₹<?php echo number_format($row['Pprice']); ?>
                        </h4>

                    </div>

                </div>

            </a>

        </div>

        <?php 
            } 
        } else {
            echo '<div class="col-12 text-center py-5"><p class="text-muted">No products found for this brand.</p></div>';
        }
        ?>
    </div>
</div>

<?php include "include/footer.php"; ?>