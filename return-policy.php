<?php
session_start();
include("config/db.php");
$page_title = "Return Policy | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h2 class="fw-bold mb-3">Return & Refund Policy</h2>
                <p class="text-muted small mb-4">Last updated: September 2026</p>
                <hr class="mb-4">

                <h5 class="fw-bold text-dark mt-4">1. Eligibility for Returns</h5>
                <p class="text-muted">Items can be returned within 7 days of delivery if they are unused, undamaged, and in their original packaging.</p>

                <h5 class="fw-bold text-dark mt-4">2. Refund Process</h5>
                <p class="text-muted">Once your return is received and inspected, we will initiate a refund to your original payment method or bank account within 3-5 business days.</p>

                <h5 class="fw-bold text-dark mt-4">3. Non-Returnable Items</h5>
                <p class="text-muted">Personal care, grooming products, and used items are not eligible for return unless received damaged.</p>

                <h5 class="fw-bold text-dark mt-4">Contact Us</h5>
                <p class="text-muted">If you have any questions, please reach out to us at <span class="fw-semibold text-primary">apanacart06@gmail.com</span>.</p>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>