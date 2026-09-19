<?php
session_start();
include('config/db.php');
$page_title = 'Fresh Picks | ApnaCart';
include('include/header.php');
include('include/navbar.php');

$query = "SELECT * FROM products WHERE status = 'active' ORDER BY pID DESC LIMIT 12";
$result = mysqli_query($conn, $query);
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="eyebrow mb-2">Fresh arrivals</p>
            <h2 class="fw-bold mb-0">Fresh Picks</h2>
        </div>
        <a href="shop.php" class="btn btn-outline-primary rounded-pill px-3">Explore more</a>
    </div>

    <div class="row g-4">
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                        <div class="product-image-wrap">
                            <img src="images/<?php echo htmlspecialchars($row['Pimage1']); ?>" alt="<?php echo htmlspecialchars($row['pTitle']); ?>">
                            <span class="badge badge-offer">New</span>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold fs-6 mb-2"><?php echo htmlspecialchars($row['pTitle']); ?></h5>
                            <?php if (!empty($row['brand'])): ?>
                                <div class="small text-muted mb-2">Brand: <?php echo htmlspecialchars($row['brand']); ?></div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price-tag">₹<?php echo number_format((float)$row['Pprice'], 2); ?></span>
                                <?php if (!empty($row['Prating'])): ?>
                                    <span class="rating-pill"><i class="fa-solid fa-star"></i> <?php echo htmlspecialchars($row['Prating']); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="product.php?id=<?php echo $row['pID']; ?>" class="btn btn-primary w-100 rounded-3">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 text-muted">No fresh picks available right now.</div>
        <?php endif; ?>
    </div>
</div>

<style>
.eyebrow {
    letter-spacing: 0.08em;
    text-transform: uppercase;
    font-size: 0.72rem;
    color: #2563eb;
    font-weight: 700;
}
.product-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08) !important;
}
.product-image-wrap {
    height: 220px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px;
    position: relative;
}
.product-image-wrap img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}
.badge-offer {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #22c55e;
    border-radius: 999px;
    padding: 6px 10px;
    font-size: 0.68rem;
    font-weight: 700;
}
.price-tag {
    font-size: 1.1rem;
    font-weight: 800;
    color: #16a34a;
}
.rating-pill {
    background: #fff7db;
    color: #b7791f;
    border-radius: 999px;
    padding: 5px 8px;
    font-size: 0.75rem;
    font-weight: 700;
}
</style>

<?php include('include/footer.php'); ?>
