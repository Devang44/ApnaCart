<?php
session_start();
include("config/db.php");
include("include/header.php");

// Login check
if(!isset($_SESSION['userID']))
{
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$error = "";
$success = "";

if(isset($_POST['change_password']))
{
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if(empty($currentPassword) || empty($newPassword) || empty($confirmPassword))
    {
        $error = "All fields are required.";
    }
    elseif($newPassword !== $confirmPassword)
    {
        $error = "New Password and Confirm Password do not match.";
    }
    elseif(strlen($newPassword) < 6)
    {
        $error = "New Password must be at least 6 characters long.";
    }
    else
    {
        // Get user current hashed password from database
        $query = mysqli_query($conn, "SELECT password FROM users WHERE userID='$userID'");
        $user = mysqli_fetch_assoc($query);

        if($user && password_verify($currentPassword, $user['password']))
        {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update in database
            $update = mysqli_query($conn, "UPDATE users SET password='$hashedPassword' WHERE userID='$userID'");

            if($update)
            {
                $_SESSION['success'] = "Password changed successfully!";
                header("Location: profile.php"); // <--- Yahan redirect hoga
                exit();
            }
            else
            {
                $error = "Something went wrong. Please try again.";
            }
        }
        else
        {
            $error = "Current password is incorrect.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0"><i class="fa fa-lock me-2"></i>Change Password</h3>
                </div>

                <div class="card-body p-4 p-md-5">

                    <?php if(!empty($error)) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-circle-exclamation me-1"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>

                    <form method="POST" action="">

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Password</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="change_password" class="btn btn-primary w-100 py-2 fw-semibold mb-2">
                            <i class="fa fa-key me-1"></i> Update Password
                        </button>

                        <!-- Back Button -->
                        <a href="profile.php" class="btn btn-outline-secondary w-100 py-2 fw-semibold">
                            <i class="fa fa-arrow-left me-1"></i> Back to Profile
                        </a>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>