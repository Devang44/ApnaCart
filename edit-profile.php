<?php
session_start();
include("config/db.php");
include("include/header.php");

if(!isset($_SESSION['userID']))
{
    header("Location:login.php");
    exit();
}

$userID = $_SESSION['userID'];

$success = "";
$error = "";

// User Data
$query = mysqli_query($conn, "SELECT * FROM users WHERE userID='$userID'");
$user = mysqli_fetch_assoc($query);

if(isset($_POST['update']))
{
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $gender = $_POST['gender'];

    if(empty($fullName) || empty($email) || empty($mobile))
    {
        $error = "All fields are required.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error = "Invalid Email Address.";
    }
    elseif(!preg_match('/^[6-9][0-9]{9}$/', $mobile))
    {
        $error = "Enter Valid 10 Digit Mobile Number.";
    }
    else
    {
        // Check Duplicate Email & Mobile
        $check = mysqli_query($conn, "
            SELECT *
            FROM users
            WHERE
            (email='$email' OR mobile='$mobile')
            AND userID!='$userID'
        ");

        if(mysqli_num_rows($check) > 0)
        {
            $error = "Email or Mobile already exists.";
        }
        else
        {
            $update = mysqli_query($conn, "
                UPDATE users SET
                fullName='$fullName',
                email='$email',
                mobile='$mobile',
                gender='$gender'
                WHERE userID='$userID'
            ");

            if($update)
            {
                $_SESSION['userName'] = $fullName;
                $_SESSION['userEmail'] = $email;
                $_SESSION['success'] = "Profile Updated Successfully";

                header("Location:index.php");
                exit();
            }
            else
            {
                die(mysqli_error($conn));
            }
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <?php if(!empty($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Edit Profile</h3>
                </div>

                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input
                                type="text"
                                class="form-controls"
                                name="fullName"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['fullName']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input
                                type="text"
                                class="form-control"
                                name="mobile"
                                maxlength="10"
                                value="<?php echo htmlspecialchars($user['mobile']); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="Male" <?php if($user['gender']=="Male") echo "selected"; ?>>Male</option>
                                <option value="Female" <?php if($user['gender']=="Female") echo "selected"; ?>>Female</option>
                                <option value="Other" <?php if($user['gender']=="Other") echo "selected"; ?>>Other</option>
                            </select>
                        </div>

                        <!-- Update Button -->
                        <button type="submit" name="update" class="btn btn-primary w-100 mb-2">
                            Update Profile
                        </button>

                        <!-- Back Button -->
                        <a href="index.php" class="btn btn-secondary w-100">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>