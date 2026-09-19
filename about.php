<?php
session_start();
include("config/db.php");
$page_title = "About Us | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold mb-3">Our Story</span>
            <h1 class="fw-bold mb-3">Welcome to ApnaCart — Your Trusted Shopping Destination</h1>
            <p class="text-muted mb-4">
                Founded with a mission to bring high-quality fashion, electronics, and lifestyle accessories right to your doorstep, ApnaCart combines affordability with seamless online shopping.
            </p>
            <div class="row g-3">
                <div class="col-6">
                    <div class="p-3 border rounded-4 bg-light">
                        <h3 class="fw-bold text-primary mb-1">10K+</h3>
                        <p class="text-muted small mb-0">Happy Customers</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 border rounded-4 bg-light">
                        <h3 class="fw-bold text-success mb-1">100%</h3>
                        <p class="text-muted small mb-0">Genuine Products</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white">
                <i class="fa-solid fa-bag-shopping display-1 mb-3 text-warning"></i>
                <h3 class="fw-bold">ApnaCart</h3>
                <p class="text-white-50 mb-0">Look Sharp. Feel Confident. Shop Smart.</p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="my-5 py-4">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Why Choose ApnaCart?</h3>
            <p class="text-muted">We care about your shopping experience from click to delivery</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="text-primary fs-2 mb-3"><i class="fa-solid fa-truck-fast"></i></div>
                    <h5 class="fw-bold">Fast Delivery</h5>
                    <p class="text-muted small mb-0">Quick and reliable local & nationwide shipping.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="text-success fs-2 mb-3"><i class="fa-solid fa-shield-halved"></i></div>
                    <h5 class="fw-bold">100% Safe Checkout</h5>
                    <p class="text-muted small mb-0">Secure payment gateways and cash on delivery available.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="text-warning fs-2 mb-3"><i class="fa-solid fa-tags"></i></div>
                    <h5 class="fw-bold">Best Prices</h5>
                    <p class="text-muted small mb-0">Daily discounts and special festival offers.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="text-danger fs-2 mb-3"><i class="fa-solid fa-headset"></i></div>
                    <h5 class="fw-bold">24/7 Support</h5>
                    <p class="text-muted small mb-0">Dedicated customer care ready to assist you anytime.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>