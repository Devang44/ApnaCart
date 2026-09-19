<?php
include "config/db.php";
include "include/header.php";
include "include/navbar.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$category = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM category WHERE id='$id'"));

$product = mysqli_query($conn,
"SELECT * FROM products WHERE catID = '$id' ORDER BY pID DESC");
?>



<!-- product details -->
<div class="container my-4">
    <div class="row g-4">
        <?php while($row=mysqli_fetch_assoc($product)){ ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <a href="product.php?id=<?php echo $row['pID']; ?>" class="text-decoration-none text-dark">
                    <div class="card shadow h-100 product-card border-0 rounded-4 overflow-hidden">
                        <img src="images/<?php echo htmlspecialchars($row['Pimage1']); ?>"
                             class="card-img-top"
                             style="height:240px; object-fit:contain; background:#f8fafc;">

                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($row['pTitle']); ?></h5>
                            <h4 class="text-primary mb-0">
                                ₹<?php echo number_format($row['Pprice']); ?>
                            </h4>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<?php include "include/footer.php"; ?>