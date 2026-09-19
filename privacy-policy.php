<?php
session_start();
include("config/db.php");
$page_title = "Privacy Policy | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h2 class="fw-bold mb-3">Privacy Policy</h2>
                <p class="text-muted small mb-4">Last updated: September 2026</p>
                <hr class="mb-4">

                <h5 class="fw-bold text-dark mt-4">1. Information We Collect</h5>
                <p class="text-muted">We collect information you provide directly to us when you register an account, place an order, or contact our support team (such as your name, email, phone number, and shipping address).</p>

                <h5 class="fw-bold text-dark mt-4">2. How We Use Your Information</h5>
                <p class="text-muted">Your information is used solely to process your orders, manage your account, and improve your shopping experience on ApnaCart.</p>

                <h5 class="fw-bold text-dark mt-4">3. Data Security</h5>
                <p class="text-muted">We implement standard security measures to protect your personal data and transaction history.</p>

                <h5 class="fw-bold text-dark mt-4">Contact Us</h5>
                <p class="text-muted">If you have any questions, please reach out to us at <span class="fw-semibold text-primary">mrdevang06@gmail.com</span>.</p>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>