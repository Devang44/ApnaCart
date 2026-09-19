<?php
session_start();
include("config/db.php");

// Login Check
if(!isset($_SESSION['userID']))
{
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE userID='$userID'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | ApnaCart</title>

    <!-- Bootstrap 5 CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (Ye navbar ke icons ke liye zaroori hai) -->
    <link rel="stylesheet" href="bootstrap-icons/bootstrap-icons.css">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/profile.css">
</head>

<body class="bg-light">

    <?php include("include/navbar.php"); ?>

    <div class="container py-5">
        <!-- Back Button Row -->
        <div class="row justify-content-center mb-3">
            <div class="col-lg-8">
                <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> Back to Home
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    
                    <!-- Top Gradient Header -->
                    <div class="bg-primary text-white p-4 text-center position-relative" style="background: linear-gradient(135deg, #2874f0 0%, #10439f 100%);">
                        <div class="mb-3 mt-2">
                            <div class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: bold;">
                                <?php 
                                    // User ke naam ka pehla letter dikhayega agar image na ho
                                    echo strtoupper(substr($user['fullName'], 0, 1)); 
                                ?>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($user['fullName']); ?></h3>
                        <p class="text-white-50 mb-0 small"><i class="fa fa-envelope me-1"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>

                    <!-- Profile Details Body -->
                    <div class="card-body p-4 p-md-5">
                        
                        <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">
                            <i class="fa-solid fa-user-circle text-primary me-2"></i> Personal Information
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="d-block text-muted small mb-1">Full Name</span>
                                    <span class="fw-semibold text-dark">
                                        <i class="fa fa-user text-primary me-2"></i>
                                        <?php echo htmlspecialchars($user['fullName']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="d-block text-muted small mb-1">Email Address</span>
                                    <span class="fw-semibold text-dark">
                                        <i class="fa fa-envelope text-primary me-2"></i>
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="d-block text-muted small mb-1">Mobile Number</span>
                                    <span class="fw-semibold text-dark">
                                        <i class="fa fa-phone text-primary me-2"></i>
                                        <?php echo htmlspecialchars($user['mobile']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="d-block text-muted small mb-1">Gender</span>
                                    <span class="fw-semibold text-dark">
                                        <i class="fa fa-venus-mars text-primary me-2"></i>
                                        <?php echo htmlspecialchars($user['gender'] ?? 'Not Specified'); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="d-block text-muted small mb-1">Member Since (Joined On)</span>
                                    <span class="fw-semibold text-dark">
                                        <i class="fa fa-calendar-days text-primary me-2"></i>
                                        <?php echo date("d M Y", strtotime($user['CreatedAt'])); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-2 mt-4">
                            <div class="col-md-3 col-6">
                                <a href="edit-profile.php" class="btn btn-primary w-100 py-2 fw-semibold">
                                    <i class="fa fa-user-pen me-1"></i> Edit Profile
                                </a>
                            </div>

                            <div class="col-md-3 col-6">
                                <a href="change-password.php" class="btn btn-warning w-100 py-2 fw-semibold text-dark">
                                    <i class="fa fa-lock me-1"></i> Password
                                </a>
                            </div>

                            <div class="col-md-3 col-6">
                                <a href="index.php" class="btn btn-outline-secondary w-100 py-2 fw-semibold">
                                    <i class="fa fa-house me-1"></i> Home
                                </a>
                            </div>

                            <div class="col-md-3 col-6">
                                <a href="logout.php" class="btn btn-danger w-100 py-2 fw-semibold">
                                    <i class="fa fa-right-from-bracket me-1"></i> Logout
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("include/footer.php"); ?>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>