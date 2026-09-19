<?php 
session_start();
include('config/db.php'); // <--- Cart counter & database queries ke liye ye zaroori hai

if(isset($_SESSION['success'])) {
    unset($_SESSION['success']);
}

$page_title = 'ApnaCart - Home';
include('include/header.php');
include('include/navbar.php');
?>

<?php if(isset($_SESSION['loginSuccess'])) { unset($_SESSION['loginSuccess']); ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Welcome',
    text: 'Login Successful',
    confirmButtonColor: '#2563eb'
});
</script>
<?php } ?>

<main class="flex-grow-1 home-page-shell">

    <div class="container py-4">
        <div class="promo-strip d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 py-3 mb-4">
            <div class="promo-item">
                <span class="promo-icon"><i class="fa-solid fa-truck-fast"></i></span>
                <span>Free Shipping</span>
            </div>
            <div class="promo-item">
                <span class="promo-icon"><i class="fa-solid fa-shield-heart"></i></span>
                <span>Secure Checkout</span>
            </div>
            <div class="promo-item">
                <span class="promo-icon"><i class="fa-solid fa-rotate-left"></i></span>
                <span>Easy Returns</span>
            </div>
            <div class="promo-item">
                <span class="promo-icon"><i class="fa-solid fa-headset"></i></span>
                <span>24/7 Support</span>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div id="heroSlider" class="carousel slide carousel-fade shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="hero-slide hero-slide-dark">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <span class="hero-tag">Premium Grooming</span>
                            <h1>Premium Grooming Collection</h1>
                            <h2>Look sharp. Feel confident.</h2>
                            <div class="hero-price">Up to 40% OFF</div>
                            <a href="shop.php" class="btn btn-light btn-lg rounded-pill px-4 fw-bold hero-btn">Shop Now</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="hero-slide hero-slide-tech">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <span class="hero-tag">Smart Tech Deals</span>
                            <h1>Power Your Everyday</h1>
                            <h2>Latest gadgets and accessories</h2>
                            <div class="hero-price">Save up to 35% OFF</div>
                            <a href="shop.php" class="btn btn-light btn-lg rounded-pill px-4 fw-bold hero-btn">Explore Deals</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="hero-slide hero-slide-fashion">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <span class="hero-tag">New Season Picks</span>
                            <h1>Style That Stands Out</h1>
                            <h2>Fresh essentials for every day</h2>
                            <div class="hero-price">Flat 30% OFF</div>
                            <a href="shop.php" class="btn btn-light btn-lg rounded-pill px-4 fw-bold hero-btn">Shop Collection</a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev" aria-label="Previous slide">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next" aria-label="Next slide">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    <div class="container my-5">
        <div class="section-heading d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="eyebrow">Browse by interest</p>
                <h3 class="fw-bold text-dark mb-0">Shop by Category</h3>
            </div>
            <a href="shop.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">View all</a>
        </div>
        <div class="row g-4 text-center">
            <div class="col-lg-2 col-md-4 col-6">
                <a href="shop.php?category=mobiles" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-150 category-card">
                        <img src="images/mobile.png" class="img-fluid mx-auto mb-2" style="height: 70px; object-fit: contain;" alt="Mobiles">
                        <h6 class="fw-semibold text-dark mb-0">Mobiles</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="shop.php?category=laptops" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-150 category-card">
                        <img src="images/laptop.png" class="img-fluid mx-auto mb-2" style="height: 70px; object-fit: contain;" alt="Laptops">
                        <h6 class="fw-semibold text-dark mb-0">Laptops</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="shop.php?category=watch" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-150 category-card">
                        <img src="images/watch.png" class="img-fluid mx-auto mb-2" style="height: 70px; object-fit: contain;" alt="Smart Watch">
                        <h6 class="fw-semibold text-dark mb-0">Smart Watch</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="shop.php?category=travel" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-150 category-card">
                        <img src="images/travel.png" class="img-fluid mx-auto mb-2" style="height: 70px; object-fit: contain;" alt="Travel">
                        <h6 class="fw-semibold text-dark mb-0">Travel</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="shop.php?category=fragrances" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-150 category-card">
                        <img src="images/perfume.png" class="img-fluid mx-auto mb-2" style="height: 70px; object-fit: contain;" alt="Fragrances">
                        <h6 class="fw-semibold text-dark mb-0">Fragrances</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="shop.php?category=facecare" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-150 category-card">
                        <img src="images/facecare.png" class="img-fluid mx-auto mb-2" style="height: 70px; object-fit: contain;" alt="Face Care">
                        <h6 class="fw-semibold text-dark mb-0">Face Care</h6>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="deal-banner deal-banner-left">
                    <div class="deal-content">
                        <p class="eyebrow text-white-50">Best sellers</p>
                        <h4 class="fw-bold text-white mb-3">Smart tech at your fingertips</h4>
                        <a href="best-sellers.php" class="btn btn-light rounded-pill px-4 fw-semibold">Explore now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="deal-banner deal-banner-right">
                    <div class="deal-content">
                        <p class="eyebrow text-white-50">Fresh picks</p>
                        <h4 class="fw-bold text-white mb-3">Beauty essentials under one roof</h4>
                        <a href="fresh-picks.php" class="btn btn-light rounded-pill px-4 fw-semibold">Shop essentials</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="section-heading d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="eyebrow">Trending now</p>
                <h3 class="fw-bold text-dark mb-0">Featured Products</h3>
            </div>
            <a href="shop.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">Explore all</a>
        </div>

        <div class="row g-4">
            <?php
            $prodQuery = mysqli_query($conn, "SELECT * FROM products WHERE status = 'active' ORDER BY pID DESC LIMIT 4");
            if($prodQuery && mysqli_num_rows($prodQuery) > 0) {
                while($prod = mysqli_fetch_assoc($prodQuery)) {
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden product-card d-flex flex-column">
                        <div class="product-image-wrap">
                            <img src="images/<?php echo htmlspecialchars($prod['Pimage1']); ?>" class="img-fluid" alt="Product">
                            <span class="badge badge-offer">Hot Deal</span>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fs-6 fw-bold text-dark mb-2"><?php echo htmlspecialchars($prod['pTitle']); ?></h5>
                            <?php if (!empty($prod['brand'])) : ?>
                                <div class="text-muted small mb-2">Brand: <?php echo htmlspecialchars($prod['brand']); ?></div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="price-tag">₹<?php echo number_format((float)$prod['Pprice'], 2); ?></span>
                                <?php if (!empty($prod['Prating'])) : ?>
                                    <span class="rating-pill"><i class="fa-solid fa-star"></i> <?php echo htmlspecialchars($prod['Prating']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="mt-auto d-flex gap-2">
                                <a href="product.php?id=<?php echo $prod['pID']; ?>" class="btn btn-outline-primary btn-sm flex-fill rounded-3 fw-semibold">View</a>
                                <a href="cart.php?action=add&id=<?php echo $prod['pID']; ?>" class="btn btn-primary btn-sm flex-fill rounded-3 fw-semibold">Add</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center text-muted py-4">No products available at the moment.</div>';
            }
            ?>
        </div>
    </div>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="info-box">
                    <div class="info-icon"><i class="fa-solid fa-bolt"></i></div>
                    <h5>Fast Delivery</h5>
                    <p>Quick shipping across India with real-time order tracking.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="info-box">
                    <div class="info-icon"><i class="fa-solid fa-wallet"></i></div>
                    <h5>Secure Payment</h5>
                    <p>Trusted payment options with smooth and protected checkout.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="info-box">
                    <div class="info-icon"><i class="fa-solid fa-medal"></i></div>
                    <h5>Quality Assured</h5>
                    <p>Curated and verified products for better customer confidence.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="newsletter-box">
            <div>
                <p class="eyebrow mb-2">Stay updated</p>
                <h3 class="fw-bold text-dark mb-0">Get exclusive offers in your inbox</h3>
            </div>
            <form class="newsletter-form d-flex gap-2">
                <input type="email" class="form-control" placeholder="Enter your email address" aria-label="Email">
                <button type="submit" class="btn btn-primary px-4 rounded-pill">Subscribe</button>
            </form>
        </div>
    </div>

</main>

<style>
.home-page-shell {
    background: #f5f7fb;
}

.promo-strip {
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
}

.promo-item {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #1f2937;
    font-weight: 600;
    font-size: 0.93rem;
}

.promo-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #edf4ff;
    color: #2563eb;
}

.section-heading {
    padding-bottom: 8px;
}

.eyebrow {
    margin: 0 0 8px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.72rem;
    font-weight: 700;
    color: #2563eb;
}

.category-card {
    transition: all 0.25s ease;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.05);
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(37, 99, 235, 0.12) !important;
    border-color: rgba(37, 99, 235, 0.18);
}

.deal-banner {
    min-height: 180px;
    border-radius: 24px;
    padding: 28px;
    display: flex;
    align-items: end;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
}

.deal-banner::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(0,0,0,0.15), transparent);
}

.deal-content {
    position: relative;
    z-index: 1;
}

.deal-banner-left {
    background: linear-gradient(135deg, #1d4ed8 0%, #0f172a 100%);
}

.deal-banner-right {
    background: linear-gradient(135deg, #ad6e1f 0%, #2a1c1b 100%);
}

.hero-slide {
    position: relative;
    min-height: 430px;
    overflow: hidden;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
}

.hero-slide-dark {
    background: radial-gradient(circle at 20% 20%, rgba(255, 214, 122, 0.22), transparent 18%),
                radial-gradient(circle at 65% 25%, rgba(255,255,255,0.14), transparent 12%),
                linear-gradient(120deg, #090909 0%, #1b1b1d 30%, #120d08 60%, #1a1412 100%);
}

.hero-slide-tech {
    background: radial-gradient(circle at 75% 18%, rgba(96, 165, 250, 0.35), transparent 18%),
                radial-gradient(circle at 35% 80%, rgba(14, 165, 233, 0.2), transparent 20%),
                linear-gradient(120deg, #0b1220 0%, #16263c 38%, #0f172a 100%);
}

.hero-slide-fashion {
    background: radial-gradient(circle at 18% 24%, rgba(251, 191, 36, 0.24), transparent 18%),
                radial-gradient(circle at 78% 18%, rgba(255,255,255,0.10), transparent 15%),
                linear-gradient(120deg, #1d1d1d 0%, #2c2c2c 26%, #442d29 62%, #1a1918 100%);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(0,0,0,0.76) 0%, rgba(0,0,0,0.52) 38%, rgba(0,0,0,0.16) 100%);
}

.hero-content {
    position: relative;
    z-index: 1;
    width: min(54%, 650px);
    padding: 48px 54px;
    color: #fff;
}

.hero-tag {
    display: inline-block;
    font-size: 0.76rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.2);
    padding: 8px 14px;
    border-radius: 999px;
    margin-bottom: 16px;
    backdrop-filter: blur(3px);
}

.hero-content h1 {
    font-size: clamp(2.1rem, 4vw, 4rem);
    line-height: 1.02;
    font-weight: 800;
    margin: 0 0 12px;
    letter-spacing: -0.04em;
}

.hero-content h2 {
    font-size: clamp(1.1rem, 2vw, 2rem);
    font-weight: 400;
    margin: 0 0 20px;
    opacity: 0.95;
}

.hero-price {
    font-size: clamp(1.2rem, 2vw, 2.6rem);
    font-weight: 800;
    color: #f9d67a;
    margin-bottom: 26px;
}

.hero-btn {
    border: 0;
    box-shadow: 0 12px 24px rgba(0,0,0,0.18);
    padding: 12px 26px;
    font-size: 1rem;
}

#heroSlider .carousel-control-prev,
#heroSlider .carousel-control-next {
    width: 42px;
    height: 42px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50%;
    opacity: 0.9;
}

#heroSlider .carousel-control-prev {
    left: 18px;
}

#heroSlider .carousel-control-next {
    right: 18px;
}

#heroSlider .carousel-indicators {
    bottom: 14px;
}

#heroSlider .carousel-indicators [data-bs-target] {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.7);
    margin: 0 6px;
}

#heroSlider .carousel-indicators .active {
    background-color: #fff;
    width: 28px;
    border-radius: 999px;
}

.product-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border: 1px solid rgba(15, 23, 42, 0.05);
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08) !important;
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
    background: #ff5a5f;
    color: white;
    border-radius: 999px;
    padding: 6px 10px;
    font-size: 0.68rem;
    font-weight: 700;
}

.price-tag {
    font-size: 1.15rem;
    font-weight: 800;
    color: #16a34a;
}

.rating-pill {
    background: #fff7db;
    color: #b7791f;
    border-radius: 999px;
    padding: 5px 8px;
    font-size: 0.76rem;
    font-weight: 700;
}

.info-box {
    background: #fff;
    border-radius: 22px;
    padding: 28px 24px;
    text-align: center;
    border: 1px solid rgba(15, 23, 42, 0.04);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.03);
}

.info-icon {
    width: 58px;
    height: 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    border-radius: 18px;
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
    color: #2563eb;
    font-size: 1.4rem;
}

.newsletter-box {
    background: linear-gradient(135deg, #eef4ff 0%, #ffffff 100%);
    border: 1px solid rgba(37, 99, 235, 0.12);
    border-radius: 28px;
    padding: 28px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.newsletter-form {
    min-width: min(100%, 420px);
}

.newsletter-form .form-control {
    height: 46px;
    border-radius: 999px;
    border: 1px solid rgba(15, 23, 42, 0.08);
}

@media (max-width: 768px) {
    .promo-strip {
        justify-content: center;
    }

    .hero-slide {
        min-height: 360px;
    }

    .hero-content {
        width: 100%;
        padding: 24px 22px;
    }

    .hero-content h1 {
        font-size: 2.2rem;
    }

    .hero-content h2 {
        font-size: 1.1rem;
    }

    .hero-price {
        font-size: 1.5rem;
        margin-bottom: 18px;
    }

    .newsletter-box {
        flex-direction: column;
        text-align: center;
    }

    .newsletter-form {
        width: 100%;
    }
}
</style>

<?php include('include/footer.php'); ?>