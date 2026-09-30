<?php
session_start();
include("../config/db.php");

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email === "" || $password === "") {
        $error = "Please enter both email and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT adminID, adminName, email, password, status FROM admins WHERE email = ? LIMIT 1");
        if (!$stmt) {
            $error = "Admin accounts are not configured. Run admin/setup.sql in your database first.";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $admin = $result ? mysqli_fetch_assoc($result) : null;
            mysqli_stmt_close($stmt);

            $passwordVerified = $admin && (
                password_verify($password, $admin['password']) ||
                hash_equals($admin['password'], $password)
            );

            if ($admin && $admin['status'] === 'Active' && $passwordVerified) {
                if (!password_verify($password, $admin['password'])) {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $upgradeStmt = mysqli_prepare($conn, "UPDATE admins SET password = ? WHERE adminID = ?");
                    if ($upgradeStmt) {
                        mysqli_stmt_bind_param($upgradeStmt, "si", $passwordHash, $admin['adminID']);
                        mysqli_stmt_execute($upgradeStmt);
                        mysqli_stmt_close($upgradeStmt);
                    }
                }

                session_regenerate_id(true);
                $_SESSION['admin_id'] = (int)$admin['adminID'];
                $_SESSION['admin_name'] = $admin['adminName'];
                $_SESSION['admin_email'] = $admin['email'];
                header("Location: index.php");
                exit();
            }

            $error = "Invalid admin credentials or inactive account.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | ApnaCart</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a 55%, #2563eb);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .login-box {
            width: min(100%, 460px);
            background: rgba(255,255,255,0.96);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.35);
            overflow: hidden;
        }
        .login-head {
            background: linear-gradient(135deg, #0f172a, #2563eb);
            color: #fff;
            padding: 30px 28px;
        }
        .login-body {
            padding: 32px 28px 28px;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1.5px solid #dbe3f0;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-head">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="rounded-3 bg-white bg-opacity-10 p-3">
                    <i class="fa-solid fa-shop fs-4"></i>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">ApnaCart Admin</h3>
                    <small class="text-white-50">Secure dashboard access</small>
                </div>
            </div>
        </div>

        <div class="login-body">
            <?php if ($error !== "") : ?>
                <div class="alert alert-danger rounded-3 mb-3">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Admin Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="Enter admin email" autocomplete="username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" name="password" autocomplete="current-password" required>
                </div>
                <button type="submit" name="admin_login" class="btn btn-primary w-100">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>Login to Dashboard
                </button>
            </form>

        </div>
    </div>
</body>
</html>
