<?php
session_start();
include("config/db.php");
include("include/offer_functions.php");

if(!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Agar shipping address set nahi hai toh pehle checkout.php par bhej do
if(!isset($_SESSION['shipping_address'])) {
    header("Location: checkout.php");
    exit();
}

$userID = intval($_SESSION['userID']);
$buyNowProductID = intval($_SESSION['buy_now_product_id'] ?? 0);
$cartItemsList = [];

if ($buyNowProductID > 0) {
    $productRes = mysqli_query($conn, "SELECT pID, pTitle, Pprice, stock FROM products WHERE pID = '$buyNowProductID' LIMIT 1");
    if (!$productRes || mysqli_num_rows($productRes) === 0) {
        header("Location: shop.php");
        exit();
    }

    $product = mysqli_fetch_assoc($productRes);
    if ((int)($product['stock'] ?? 0) <= 0) {
        $_SESSION['cart_message'] = "This product is out of stock.";
        header("Location: product.php?id=$buyNowProductID");
        exit();
    }

    $cartItemsList[] = [
        'pID' => $product['pID'],
        'pTitle' => $product['pTitle'],
        'Pprice' => $product['Pprice'],
        'quantity' => 1
    ];
} else {
    $query = "SELECT cart.quantity, products.pID, products.pTitle, products.Pprice 
              FROM cart 
              JOIN products ON cart.pID = products.pID 
              WHERE cart.userID = '$userID'";
    $cartRes = mysqli_query($conn, $query);

    while($item = mysqli_fetch_assoc($cartRes)) {
        $cartItemsList[] = $item;
    }
}

$subtotal = 0;
foreach ($cartItemsList as $item) {
    $subtotal += ((float)($item['Pprice'] ?? 0) * (int)($item['quantity'] ?? 1));
}

$bestOffer = getApplicableOfferForCart($conn, $userID, $subtotal);
$discountAmount = (float) ($bestOffer['amount'] ?? 0);
$finalTotal = max(0, $subtotal - $discountAmount);

if(empty($cartItemsList)) {
    header("Location: cart.php");
    exit();
}

$page_title = "Payment | ApnaCart";
include("include/header.php");
include("include/navbar.php");
?>

<div class="container my-5">
    <div class="row g-4">
        <!-- Left: Payment Options -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: bold;">
                        2
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">Select Payment Method</h3>
                        <p class="text-muted small mb-0">Choose how you want to pay for your order</p>
                    </div>
                </div>

                <form id="paymentForm" action="place_order.php" method="POST">
                    <input type="hidden" name="paymentMethod" id="selectedPaymentMethod" value="COD">

                    <!-- Option 1: Cash on Delivery -->
                    <div class="payment-option mb-3 p-3 border rounded-3 cursor-pointer active-option" onclick="selectPayment('COD')">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payRadio" id="codRadio" checked>
                            <label class="form-check-label fw-bold d-flex align-items-center" for="codRadio">
                                <i class="fa-solid fa-truck-fast text-primary fs-4 me-3"></i>
                                <div>
                                    <span>Cash on Delivery (COD)</span>
                                    <div class="text-muted small fw-normal">Pay with cash when your order is delivered.</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Option 2: Razorpay (Online Payment / Test Mode) -->
                    <div class="payment-option mb-4 p-3 border rounded-3 cursor-pointer" onclick="selectPayment('Razorpay')">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payRadio" id="razorpayRadio">
                            <label class="form-check-label fw-bold d-flex align-items-center" for="razorpayRadio">
                                <i class="fa-solid fa-credit-card text-success fs-4 me-3"></i>
                                <div>
                                    <span>Razorpay (UPI / Debit / Credit Card / NetBanking)</span>
                                    <div class="text-muted small fw-normal">Safe & Instant Online Payment (Razorpay Offline / Test Mode)</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="checkout.php" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fa fa-arrow-left me-1"></i> Edit Address
                        </a>
                        <button type="submit" id="payButton" class="btn btn-success rounded-pill px-5 py-2.5 fw-bold shadow-sm">
                            <i class="fa fa-lock me-1"></i> Place Order (COD) - ₹<?php echo number_format($finalTotal, 2); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Order Summary Sidebar -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h5 class="fw-bold mb-3">Order Summary</h5>
                <div class="shipping-info mb-3 small bg-white p-3 rounded-3 border">
                    <div class="fw-bold text-dark mb-1">Delivering to:</div>
                    <div><?php echo htmlspecialchars($_SESSION['shipping_address']['fullName']); ?> (<?php echo htmlspecialchars($_SESSION['shipping_address']['phone']); ?>)</div>
                    <div class="text-muted">
                        <?php echo htmlspecialchars($_SESSION['shipping_address']['address'] . ', ' . $_SESSION['shipping_address']['city'] . ' - ' . $_SESSION['shipping_address']['pincode']); ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold">₹<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <?php if ($discountAmount > 0) : ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Offer Discount</span>
                        <span class="fw-semibold text-success">-₹<?php echo number_format($discountAmount, 2); ?></span>
                    </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Shipping</span>
                    <span class="text-success fw-semibold">Free</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Total Payable</span>
                    <span class="fw-bold fs-5 text-success">₹<?php echo number_format($finalTotal, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Checkout Script (Offline / Test Integration) -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function selectPayment(method) {
        document.getElementById('selectedPaymentMethod').value = method;
        const payBtn = document.getElementById('payButton');
        if (method === 'Razorpay') {
            document.getElementById('razorpayRadio').checked = true;
            document.getElementById('codRadio').checked = false;
            payBtn.innerHTML = '<i class="fa fa-bolt me-1"></i> Pay with Razorpay - ₹<?php echo number_format($finalTotal, 2); ?>';
            payBtn.className = 'btn btn-primary rounded-pill px-5 py-2.5 fw-bold shadow-sm';
        } else {
            document.getElementById('codRadio').checked = true;
            document.getElementById('razorpayRadio').checked = false;
            payBtn.innerHTML = '<i class="fa fa-lock me-1"></i> Place Order (COD) - ₹<?php echo number_format($finalTotal, 2); ?>';
            payBtn.className = 'btn btn-success rounded-pill px-5 py-2.5 fw-bold shadow-sm';
        }
    }

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const method = document.getElementById('selectedPaymentMethod').value;
        if (method === 'Razorpay') {
            e.preventDefault();
            var options = {
                "key": "rzp_test_TTHUNOsQeex9Az",
                "amount": "<?php echo ($finalTotal * 100); ?>",
                "currency": "INR",
                "name": "ApnaCart",
                "description": "Order Payment",
                "handler": function (response){
                    // Payment successful hone par hidden input ya form submit kar do
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'razorpay_payment_id';
                    input.value = response.razorpay_payment_id;
                    document.getElementById('paymentForm').appendChild(input);
                    document.getElementById('paymentForm').submit();
                },
                "prefill": {
                    "name": "<?php echo htmlspecialchars($_SESSION['shipping_address']['fullName']); ?>",
                    "contact": "<?php echo htmlspecialchars($_SESSION['shipping_address']['phone']); ?>"
                },
                "theme": {
                    "color": "#2563eb"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        }
    });
</script>

<?php include("include/footer.php"); ?>