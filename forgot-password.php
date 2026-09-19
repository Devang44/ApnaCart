<?php
session_start();
include("config/db.php");

$error = "";
$success = "";

if(isset($_POST['reset_password']))
{
    $username = trim($_POST['username']);
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if(empty($username) || empty($newPassword) || empty($confirmPassword))
    {
        $error = "All fields are required.";
    }
    elseif($newPassword != $confirmPassword)
    {
        $error = "New Password and Confirm Password do not match.";
    }
    elseif(strlen($newPassword) < 8)
    {
        $error = "Password must be at least 8 characters long.";
    }
    else
    {
        // Check karo ki user email ya mobile se exist karta hai ya nahi
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$username' OR mobile='$username'");

        if(mysqli_num_rows($sql) == 1)
        {
            $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = mysqli_query($conn, "UPDATE users SET password='$hashPassword' WHERE email='$username' OR mobile='$username'");

            if($update)
            {
                $success = "Password updated successfully! You can now sign in.";
            }
            else
            {
                $error = "Something went wrong. Please try again.";
            }
        }
        else
        {
            $error = "No account found with this Email or Mobile Number.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | ApnaCart</title>

    <!-- Local Bootstrap 5 CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Local Font Awesome -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <!-- Modern Styling Matching Login Page -->
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
        }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px 15px;
        }
        .login-card {
            max-width: 950px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }
        .login-banner {
            background: linear-gradient(145deg, #2563eb, #1d4ed8);
            color: #ffffff;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .login-form-side {
            padding: 40px 40px;
            flex: 1;
        }
        .form-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.85rem;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 10px;
            padding: 11px 14px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.95rem;
            transition: all 0.25s ease;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }
        .input-group-text-btn {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-left: none;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            cursor: pointer;
            color: #64748b;
        }
        .btn-custom-primary {
            background: #2563eb;
            border: none;
            color: #fff;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .btn-custom-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }
        .brand-badge {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .feature-box {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            font-size: 0.92rem;
            color: rgba(255, 255, 255, 0.9);
        }
        .feature-box i {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <!-- Left Brand Banner -->
        <div class="col-lg-5 d-none d-lg-flex login-banner">
            <div>
                <div class="brand-badge">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h2 class="fw-bold mb-2">Password Recovery</h2>
                <p class="text-white-50 small mb-4">Reset your ApnaCart account password easily and securely.</p>
                
                <div class="mt-4">
                    <div class="feature-box">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Verified Account Recovery</span>
                    </div>
                    <div class="feature-box">
                        <i class="fa-solid fa-lock"></i>
                        <span>Encrypted Password Update</span>
                    </div>
                    <div class="feature-box">
                        <i class="fa-solid fa-headset"></i>
                        <span>24/7 Security Support</span>
                    </div>
                </div>
            </div>

            <div class="border-top border-light border-opacity-25 pt-3 small text-white-50">
                Remember your password? <a href="login.php" class="text-white fw-bold text-decoration-none">Sign In</a>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="login-form-side">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Reset Password</h3>
                    <p class="text-muted small mb-0">Enter your email/mobile and set a new password.</p>
                </div>
                <a href="login.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> Back to Login
                </a>
            </div>

            <?php if(!empty($error)) { ?>
                <div class="alert alert-danger d-flex align-items-center py-2 px-3 mb-3 rounded-3" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <div class="small fw-semibold"><?php echo $error; ?></div>
                </div>
            <?php } ?>

            <?php if(!empty($success)) { ?>
                <div class="alert alert-success d-flex align-items-center py-2 px-3 mb-3 rounded-3" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    <div class="small fw-semibold"><?php echo $success; ?></div>
                </div>
            <?php } ?>

            <form method="POST" action="" autocomplete="off">

                <!-- Registered Email or Mobile -->
                <div class="mb-3">
                    <label class="form-label">Registered Email or Mobile Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="username" placeholder="Enter your email or mobile" required>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="newPassword" name="newPassword" placeholder="Enter new password" required>
                        <button class="btn input-group-text-btn" type="button" onclick="togglePass('newPassword', 'eyeIcon1')">
                            <i class="fa-solid fa-eye" id="eyeIcon1"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="confirmPassword" name="confirmPassword" placeholder="Re-enter new password" required>
                        <button class="btn input-group-text-btn" type="button" onclick="togglePass('confirmPassword', 'eyeIcon2')">
                            <i class="fa-solid fa-eye" id="eyeIcon2"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="reset_password" class="btn btn-custom-primary w-100 mb-3">
                    <i class="fa-solid fa-rotate-right me-2"></i> Update Password
                </button>

                <!-- Back to Login for Mobile -->
                <div class="text-center small text-muted d-lg-none mt-3">
                    Remember your password? <a href="login.php" class="text-decoration-none fw-bold text-primary">Sign In</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Local Bootstrap JS -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Password Toggle Script -->
    <script>
        function togglePass(fieldId, iconId) {
            var field = document.getElementById(fieldId);
            var icon = document.getElementById(iconId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>