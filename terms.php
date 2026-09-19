<?php
session_start();
include("config/db.php");
$page_title = "Terms & Conditions | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h2 class="fw-bold mb-3">Terms & Conditions</h2>
                <p class="text-muted small mb-4">Last updated: September 2026</p>
                <hr class="mb-4">

                <h5 class="fw-bold text-dark mt-4">1. Introduction</h5>
                <p class="text-muted">By accessing or using ApnaCart, you agree to be bound by these Terms & Conditions.</p>

                <h5 class="fw-bold text-dark mt-4">2. User Account</h5>
                <p class="text-muted">You are responsible for maintaining the confidentiality of your account credentials and password.</p>

                <h5 class="fw-bold text-dark mt-4">3. Pricing & Orders</h5>
                <p class="text-muted">All prices listed on ApnaCart are subject to change without notice. We reserve the right to cancel any order due to stock issues or pricing errors.</p>

                <h5 class="fw-bold text-dark mt-4">Contact Us</h5>
                <p class="text-muted">If you have any questions, please reach out to us at <span class="fw-semibold text-primary">apanacart06@gmail.com</span>.</p>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>