<?php
require_once('core.php');

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'অননুমোদিত অ্যাক্সেস']);
    exit;
}

// Get order ID
$orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : 0;

if ($orderId <= 0) {
    echo json_encode(['success' => false, 'message' => 'অবৈধ অর্ডার আইডি']);
    exit;
}

// Fetch order details
$orderSql = "SELECT client_name FROM orders WHERE order_id = ?";
$stmt = mysqli_prepare($connection, $orderSql);
mysqli_stmt_bind_param($stmt, "i", $orderId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'অর্ডার পাওয়া যায়নি']);
    exit;
}


// Fetch order items with returnable quantity
$itemsSql = "SELECT 
                oi.order_item_id,
                oi.product_id,
                oi.quantity as original_quantity,
                oi.rate,
                oi.total,
                p.product_name,
                p.clor,
                b.brand_name,
                COALESCE(SUM(or_ret.return_quantity), 0) as returned_quantity,
                (oi.quantity - COALESCE(SUM(or_ret.return_quantity), 0)) as available_quantity
             FROM order_item oi
             INNER JOIN product p ON oi.product_id = p.product_id
             INNER JOIN brands b ON p.brand_id = b.brand_id
             LEFT JOIN order_returns or_ret ON oi.order_item_id = or_ret.order_item_id
             WHERE oi.order_id = ?
             GROUP BY oi.order_item_id
             HAVING available_quantity > 0";

$stmt = mysqli_prepare($connection, $itemsSql);
mysqli_stmt_bind_param($stmt, "i", $orderId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Update quantity to show available quantity for return
    $row['quantity'] = $row['available_quantity'];
    $items[] = $row;
}

if (empty($items)) {
    echo json_encode(['success' => false, 'message' => 'কোনো বৈধ ফেরতযোগ্য পণ্য পাওয়া যায়নি']);
    exit;
}

echo json_encode([
    'success' => true,
    'client_name' => $order['client_name'],
    'items' => $items
]);

mysqli_close($connection);
?>
