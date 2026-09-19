<?php
session_start();
include("config/db.php");

$msg = "";
if(isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if(!empty($name) && !empty($email) && !empty($message)) {
        // Aap chahein toh ise database table me save kar sakte hain ya email bhej sakte hain
        $msg = "Thank you! Your message has been received. Our team will contact you shortly.";
    }
}

$page_title = "Contact Us | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5">
    <div class="row g-4">
        <!-- Left: Contact Details -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 h-150 bg-primary text-white">
                <h3 class="fw-bold mb-3">Get in Touch</h3>
                <p class="text-white-50 mb-4">Have questions about your order, products, or anything else? Feel free to reach out to us.</p>

                <div class="d-flex align-items-start mb-4">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Our Office</h6>
                        <p class="text-white-50 small mb-0">Shop 4, MG Road, Rajkot, Gujarat - 360001</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email Us</h6>
                        <p class="text-white-50 small mb-0">apanacart06@gmail.com</p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Call Us</h6>
                        <p class="text-white-50 small mb-0">+91 98765 43210</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Contact Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h3 class="fw-bold mb-2">Send Us a Message</h3>
                <p class="text-muted small mb-4">Fill out the form below and we will get back to you soon.</p>

                <?php if(!empty($msg)) { ?>
                    <div class="alert alert-success rounded-3 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> <?php echo $msg; ?>
                    </div>
                <?php } ?>

                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Your Name</label>
                            <input type="text" class="form-control rounded-3 py-2" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Your Email</label>
                            <input type="email" class="form-control rounded-3 py-2" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Subject</label>
                            <input type="text" class="form-control rounded-3 py-2" name="subject" placeholder="What is this regarding?" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea class="form-control rounded-3" name="message" rows="4" placeholder="Write your message here..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="send_message" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold">
                                Send Message <i class="fa fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>