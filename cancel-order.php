<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['userID'])) {
    $_SESSION['redirect_after_login'] = "orders.php";
    header("Location: login.php");
    exit();
}

$userID = (int)$_SESSION['userID'];
$orderID = (int)($_POST['orderID'] ?? $_GET['orderID'] ?? 0);
$reasons = [
    'Changed my mind',
    'Found a better price',
    'Ordered by mistake',
    'Delivery is taking too long',
    'Ordered the wrong item',
    'Other'
];
$error = '';
$selectedReason = '';
$details = '';

$tableReady = mysqli_query($conn, "CREATE TABLE IF NOT EXISTS order_cancellations (
    cancellationID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    orderID INT NOT NULL UNIQUE,
    userID INT NOT NULL,
    reason VARCHAR(100) NOT NULL,
    details VARCHAR(500) DEFAULT NULL,
    canceledAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

if (!$tableReady) {
    $error = "Cancellation details could not be saved. Please try again later.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tableReady) {
    $selectedReason = trim($_POST['reason'] ?? '');
    $details = trim($_POST['details'] ?? '');

    if (!in_array($selectedReason, $reasons, true)) {
        $error = "Please select a cancellation reason.";
    } elseif ($selectedReason === 'Other' && $details === '') {
        $error = "Please tell us why you are cancelling this order.";
    } else {
        $orderCheck = mysqli_query($conn, "SELECT orderID, orderStatus FROM orders WHERE orderID = '$orderID' AND userID = '$userID' LIMIT 1");
        if (!$orderCheck || mysqli_num_rows($orderCheck) === 0) {
            $error = "Order not found.";
        } else {
            $order = mysqli_fetch_assoc($orderCheck);
            if (in_array($order['orderStatus'], ['Delivered', 'Cancelled'], true)) {
                $error = "This order can no longer be cancelled.";
            } else {
                mysqli_begin_transaction($conn);
                $statusUpdate = mysqli_query($conn, "UPDATE orders SET orderStatus = 'Cancelled' WHERE orderID = '$orderID' AND userID = '$userID' AND orderStatus NOT IN ('Delivered', 'Cancelled')");
                if ($statusUpdate && mysqli_affected_rows($conn) === 1) {
                    $safeReason = mysqli_real_escape_string($conn, $selectedReason);
                    $safeDetails = mysqli_real_escape_string($conn, $details);
                    $detailsSql = $details === '' ? 'NULL' : "'$safeDetails'";
                    $savedReason = mysqli_query($conn, "INSERT INTO order_cancellations (orderID, userID, reason, details) VALUES ('$orderID', '$userID', '$safeReason', $detailsSql)");
                    if ($savedReason) {
                        mysqli_commit($conn);
                        $_SESSION['order_message'] = "Order #$orderID has been cancelled successfully.";
                        header("Location: orders.php");
                        exit();
                    }
                }
                mysqli_rollback($conn);
                $error = "The order could not be cancelled. Please try again.";
            }
        }
    }
}

$orderResult = mysqli_query($conn, "SELECT orderID, orderDate, totalAmount, orderStatus FROM orders WHERE orderID = '$orderID' AND userID = '$userID' LIMIT 1");
$order = $orderResult ? mysqli_fetch_assoc($orderResult) : null;
$page_title = "Cancel Order | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>
<main class="container my-5" style="max-width: 760px;">
    <a href="orders.php<?php echo $order ? '?orderID=' . (int)$order['orderID'] : ''; ?>" class="text-decoration-none small">
        <i class="fa fa-arrow-left me-1"></i> Back to order
    </a>
    <h2 class="fw-bold mt-3 mb-1">Cancel order</h2>
    <p class="text-muted mb-4">Please tell us why you are cancelling.</p>

    <?php if ($error !== '') : ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!$order) : ?>
        <div class="alert alert-warning">Order not found.</div>
    <?php elseif (in_array($order['orderStatus'], ['Delivered', 'Cancelled'], true)) : ?>
        <div class="alert alert-warning">This order is <?php echo htmlspecialchars(strtolower($order['orderStatus'])); ?> and cannot be cancelled.</div>
    <?php else : ?>
        <div class="border rounded-3 p-3 mb-4 bg-light">
            <div class="fw-semibold">Order #<?php echo (int)$order['orderID']; ?></div>
            <div class="small text-muted">Placed <?php echo htmlspecialchars(date('d M Y', strtotime($order['orderDate']))); ?> · Total ₹<?php echo htmlspecialchars($order['totalAmount']); ?></div>
        </div>

        <form method="POST" action="cancel-order.php" id="cancelOrderForm">
            <input type="hidden" name="orderID" value="<?php echo (int)$order['orderID']; ?>">
            <fieldset class="mb-3">
                <legend class="fs-6 fw-semibold mb-3">Select a reason</legend>
                <?php foreach ($reasons as $reason) : ?>
                    <label class="d-flex align-items-center gap-2 border rounded-3 p-3 mb-2">
                        <input class="form-check-input mt-0" type="radio" name="reason" value="<?php echo htmlspecialchars($reason); ?>" <?php echo $selectedReason === $reason ? 'checked' : ''; ?> required>
                        <span><?php echo htmlspecialchars($reason); ?></span>
                    </label>
                <?php endforeach; ?>
            </fieldset>
            <div class="mb-4" id="otherReasonWrap" hidden>
                <label for="otherReason" class="form-label fw-semibold">Tell us a little more</label>
                <textarea class="form-control" id="otherReason" name="details" rows="3" maxlength="500" placeholder="Enter your reason"><?php echo htmlspecialchars($details); ?></textarea>
            </div>
            <button type="submit" class="btn btn-danger px-4">Confirm cancellation</button>
            <a href="orders.php?orderID=<?php echo (int)$order['orderID']; ?>" class="btn btn-outline-secondary ms-2">Keep order</a>
        </form>
        <script>
        const otherReasonWrap = document.getElementById('otherReasonWrap');
        const otherReason = document.getElementById('otherReason');
        document.querySelectorAll('input[name="reason"]').forEach((input) => {
            input.addEventListener('change', () => {
                const showOther = input.checked && input.value === 'Other';
                if (showOther) {
                    otherReasonWrap.hidden = false;
                    otherReason.required = true;
                } else if (input.checked) {
                    otherReasonWrap.hidden = true;
                    otherReason.required = false;
                }
            });
        });
        const checkedReason = document.querySelector('input[name="reason"]:checked');
        if (checkedReason && checkedReason.value === 'Other') {
            otherReasonWrap.hidden = false;
            otherReason.required = true;
        }
        </script>
    <?php endif; ?>
</main>
<?php include("include/footer.php"); ?>