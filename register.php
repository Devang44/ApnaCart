<?php
session_start();
include("config/db.php");

$success = "";
$error = "";

if(isset($_POST['register']))
{
    // Get Form Data
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $gender = $_POST['gender'];

    /* -----------------------------
        Full Name Validation
    ------------------------------*/
    if(!preg_match("/^[a-zA-Z ]{3,50}$/",$fullName))
    {
        $error = "Full Name should contain only letters and spaces (3-50 characters).";
    }

    /* -----------------------------
        Email Validation
    ------------------------------*/
    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        $error = "Enter a valid Email Address.";
    }

    /* -----------------------------
        Mobile Validation
    ------------------------------*/
    elseif(!preg_match('/^[6-9][0-9]{9}$/',$mobile))
    {
        $error = "Enter a valid 10-digit Mobile Number.";
    }

    /* -----------------------------
        Password Validation
    ------------------------------*/
    elseif(
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/',$password) ||
        !preg_match('/[a-z]/',$password) ||
        !preg_match('/[0-9]/',$password) ||
        !preg_match('/[\W]/',$password)
    )
    {
        $error = "Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.";
    }

    /* -----------------------------
        Confirm Password
    ------------------------------*/
    elseif($password != $confirmPassword)
    {
        $error = "Passwords do not match.";
    }

    else
    {
        // Duplicate Email
        $checkEmail = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($checkEmail) > 0)
        {
            $error = "Email already registered.";
        }

        else
        {
            // Duplicate Mobile
            $checkMobile = mysqli_query($conn,"SELECT * FROM users WHERE mobile='$mobile'");

            if(mysqli_num_rows($checkMobile) > 0)
            {
                $error = "Mobile Number already registered.";
            }

            else
            {
                // Encrypt Password
                $hashPassword = password_hash($password,PASSWORD_DEFAULT);

                // Insert User
                $insert = mysqli_query($conn,"INSERT INTO users
                (fullName,email,mobile,password,gender)
                VALUES
                ('$fullName','$email','$mobile','$hashPassword','$gender')");

                if($insert)
                {
                   echo "
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
                    text: 'Now Login to Continue',
                    confirmButtonColor: '#2563eb'
                }).then(function () {
                    window.location='login.php';
                });
            });
            </script>";
                }
                else
                {
                    $error="Registration Failed.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register | ApnaCart</title>

<!-- Local Bootstrap 5 CSS -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Local FontAwesome Icons -->
<link rel="stylesheet" href="fontawesome/css/all.min.css">

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
    /* Left Brand Banner */
    .login-banner {
        background: linear-gradient(145deg, #2563eb, #1d4ed8);
        color: #ffffff;
        padding: 45px 35px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    /* Right Form Side */
    .login-form-side {
        padding: 35px 40px;
        flex: 1;
        max-height: 92vh;
        overflow-y: auto;
    }
    .login-form-side::-webkit-scrollbar {
        width: 5px;
    }
    .login-form-side::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .form-label {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.85rem;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1.5px solid #cbd5e1;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        background-color: #ffffff;
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
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
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
        
        <!-- Left Side: Brand Panel with Features -->
        <div class="col-lg-5 d-none d-lg-flex login-banner">
            <div>
                <div class="brand-badge">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h2 class="fw-bold mb-2">ApnaCart</h2>
                <p class="text-white-50 small mb-4">Your Ultimate Shopping Destination</p>
                
                <div class="mt-4">
                    <div class="feature-box">
                        <i class="fa-solid fa-bolt"></i>
                        <span>Lightning Fast Delivery & Checkout</span>
                    </div>
                    <div class="feature-box">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>100% Secure Payments & Data Privacy</span>
                    </div>
                    <div class="feature-box">
                        <i class="fa-solid fa-tags"></i>
                        <span>Exclusive Deals & Daily Discounts</span>
                    </div>
                </div>
            </div>

            <div class="border-top border-light border-opacity-25 pt-3 small text-white-50">
                Already have an account? <a href="login.php" class="text-white fw-bold text-decoration-none">Sign In</a>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="login-form-side">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Create Account</h3>
                    <p class="text-muted small mb-0">Please enter your details to sign up.</p>
                </div>
                <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> Back to Home
                </a>
            </div>

            <?php if($error!=""){ ?>
                <div class="alert alert-danger d-flex align-items-center py-2 px-3 mb-3 rounded-3" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <div class="small fw-semibold"><?php echo $error; ?></div>
                </div>
            <?php } ?>

            <?php if($success!=""){ ?>
                <div class="alert alert-success d-flex align-items-center py-2 px-3 mb-3 rounded-3" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    <div class="small fw-semibold"><?php echo $success; ?></div>
                </div>
            <?php } ?>

            <form action="" method="POST" autocomplete="off">

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="fullName" placeholder="e.g. John Doe" value="<?php echo isset($fullName)?htmlspecialchars($fullName):''; ?>" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 ps-0" name="email" placeholder="name@example.com" value="<?php echo isset($email)?htmlspecialchars($email):''; ?>" required>
                    </div>
                </div>

                <!-- Mobile -->
                <div class="mb-3">
                    <label class="form-label">Mobile Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-phone"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" maxlength="10" name="mobile" placeholder="10-digit mobile number" value="<?php echo isset($mobile)?htmlspecialchars($mobile):''; ?>" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" onkeyup="checkStrength()" placeholder="Create a strong password" required>
                        <button class="btn input-group-text-btn" type="button" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div id="strength" class="form-text mt-1 small"></div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 ps-0" name="confirmPassword" placeholder="Re-enter your password" required>
                    </div>
                </div>

                <!-- Gender -->
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-venus-mars"></i></span>
                        <select class="form-select border-start-0 ps-0" name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Terms -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label small text-muted" for="terms">
                        I agree to the <a href="#" class="text-decoration-none text-primary">Terms & Conditions</a>
                    </label>
                </div>

                <button type="submit" name="register" class="btn btn-custom-primary w-100 mb-3">
                    <i class="fa-solid fa-user-plus me-2"></i> Create Account
                </button>

                <div class="text-center mt-2 small text-muted d-lg-none">
                    Already have an account? <a href="login.php" class="text-decoration-none fw-bold text-primary">Login</a>
                </div>

            </form>
        </div>

    </div>

<!-- Local Scripts -->
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="sweetalert2/sweetalert2.all.min.js"></script>
<script>
function togglePassword(){
    var x = document.getElementById("password");
    var icon = document.getElementById("eyeIcon");
    if(x.type === "password") {
        x.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        x.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

function checkStrength(){
    var password = document.getElementById("password").value;
    var strength = document.getElementById("strength");
    var strong = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}$/;
    var medium = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;

    if(password.length === 0){
        strength.innerHTML = "";
    } else if(strong.test(password)){
        strength.innerHTML = "<span class='text-success fw-semibold'>Strong Password ✔</span>";
    } else if(medium.test(password)){
        strength.innerHTML = "<span class='text-warning fw-semibold'>Medium Password</span>";
    } else {
        strength.innerHTML = "<span class='text-danger fw-semibold'>Weak Password</span>";
    }
}
</script>
</body>
</html>