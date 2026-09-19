<?php
function getApplicableOfferForCart($conn, $userID, $subtotal) {
    $subtotal = (float) $subtotal;
    $bestOffer = [
        'amount' => 0,
        'code' => '',
        'title' => '',
        'type' => '',
        'value' => 0
    ];

    if ($subtotal <= 0) {
        return $bestOffer;
    }

    $audienceCondition = "targetAudience = 'ALL'";

    if (!empty($userID)) {
        $userID = (int) $userID;
        $orderCheck = mysqli_query($conn, "SELECT COUNT(*) AS totalOrders FROM orders WHERE userID = '$userID'");
        if ($orderCheck && mysqli_num_rows($orderCheck) > 0) {
            $orderData = mysqli_fetch_assoc($orderCheck);
            $hasPreviousOrder = ((int) ($orderData['totalOrders'] ?? 0)) > 0;
            $audienceCondition = $hasPreviousOrder ? "(targetAudience = 'ALL' OR targetAudience = 'VIP')" : "(targetAudience = 'ALL' OR targetAudience = 'NEW_USER')";
        }
    }

    $offersQuery = mysqli_query($conn, "SELECT * FROM offers WHERE status = 'active' AND $audienceCondition ORDER BY offerID DESC");
    if (!$offersQuery) {
        return $bestOffer;
    }

    while ($offer = mysqli_fetch_assoc($offersQuery)) {
        $minCart = (float) ($offer['minCartValue'] ?? 0);
        if ($minCart > 0 && $subtotal < $minCart) {
            continue;
        }

        $discount = 0;
        $type = strtolower(trim((string) ($offer['discountType'] ?? '')));
        $value = (float) ($offer['discountValue'] ?? 0);

        if ($type === 'percentage') {
            $discount = ($subtotal * $value) / 100;
        } elseif ($type === 'flat' || $type === 'fixed' || $type === 'amount') {
            $discount = $value;
        }

        if ($discount > $bestOffer['amount']) {
            $bestOffer = [
                'amount' => $discount,
                'code' => (string) ($offer['couponCode'] ?? ''),
                'title' => (string) ($offer['title'] ?? ''),
                'type' => $type,
                'value' => $value
            ];
        }
    }

    return $bestOffer;
}
?>
