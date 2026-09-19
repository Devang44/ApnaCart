<?php
session_start();
include("config/db.php");
if(isset($_COOKIE['rememberUser']))
{
    $_SESSION['userID']=$_COOKIE['rememberUser'];
}

$error="";

if(isset($_POST['login']))
{
    $username=trim($_POST['username']);
    $password=$_POST['password'];

    if(empty($username) || empty($password))
    {
        $error="All Fields are Required.";
    }
    else
    {
        $sql=mysqli_query($conn,"
        SELECT * FROM users
        WHERE email='$username'
        OR mobile='$username'
        ");

        if(mysqli_num_rows($sql)==1)
        {
            $user=mysqli_fetch_assoc($sql);

            if($user['status']=="Blocked")
            {
                $error="Your Account has been Blocked.";
            }
            else
            {
                if(password_verify($password,$user['password']))
                {
                    $_SESSION['userID']=$user['userID'];
                    $_SESSION['userName']=$user['fullName'];
                    $_SESSION['userEmail']=$user['email'];
                    
                    if(isset($_POST['remember']))
                    {
                        setcookie(
                            "rememberUser",
                            $user['userID'],
                            time()+60*60*24*30,
                            "/"
                        );
                    }

                    echo "<script>
                    window.location='index.php';
                    </script>";
                    exit();
                }
                else
                {
                    $error="Invalid Password.";
                }
            }
        }
        else
        {
            $error="Invalid Email or Mobile Number.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ApnaCart</title>

    <!-- Local Bootstrap 5 CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Local Font Awesome -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <!-- Modern Custom Styling -->
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px 15px;
        }
        .login-card {
            max-width: 920px;
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
            padding: 45px 40px;
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
            padding: 12px 14px;
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
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h2 class="fw-bold mb-2">ApnaCart</h2>
                <p class="text-white-50 small mb-4">Your trusted destination for fashion, electronics, and accessories.</p>
                
                <div class="mt-4">
                    <div class="feature-box">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Fast & Reliable Local Deliveries</span>
                    </div>
                    <div class="feature-box">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>100% Secure Checkout & Privacy</span>
                    </div>
                    <div class="feature-box">
                        <i class="fa-solid fa-tags"></i>
                        <span>Exclusive Deals & Daily Discounts</span>
                    </div>
                </div>
            </div>

            <div class="border-top border-light border-opacity-25 pt-3 small text-white-50">
                New to ApnaCart? <a href="register.php" class="text-white fw-bold text-decoration-none">Create an account</a>
            </div>
        </div>

        <!-- Right Login Form Section -->
        <div class="login-form-side">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
                    <p class="text-muted small mb-0">Please sign in to access your dashboard.</p>
                </div>
                <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> Back to Home
                </a>
            </div>

            <?php if($error != "") { ?>
                <div class="alert alert-danger d-flex align-items-center py-2 px-3 mb-3 rounded-3" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <div class="small fw-semibold"><?php echo $error; ?></div>
                </div>
            <?php } ?>

            <form method="POST" action="" autocomplete="off">

                <!-- Email or Mobile -->
                <div class="mb-3">
                    <label class="form-label">Email or Mobile Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="username" name="username" placeholder="Enter email or mobile" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" placeholder="Enter password" required>
                        <button class="btn input-group-text-btn" type="button" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            Remember Me
                        </label>
                    </div>
                    <div>
                        <a href="forgot-password.php" class="small text-decoration-none fw-semibold text-primary">Forgot Password?</a>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" name="login" class="btn btn-custom-primary w-100 mb-3">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
                </button>

                <!-- Mobile Register Link -->
                <div class="text-center small text-muted d-lg-none mt-3">
                    Don't have an account? <a href="register.php" class="text-decoration-none fw-bold text-primary">Register Now</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Local Bootstrap JS -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            var password = document.getElementById("password");
            var icon = document.getElementById("eyeIcon");
            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <!-- Mobile Number Restriction Script -->
    <script>
        document.getElementById("username").addEventListener("input", function () {
            let value = this.value;
            if (/^\d+$/.test(value)) {
                if (value.length > 10) {
                    this.value = value.slice(0, 10);
                }
            }
        });
    </script>
</body>
</html>