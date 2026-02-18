<?php
require_once('core.php');

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'অননুমোদিত অ্যাক্সেস']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$returns = isset($_POST['returns']) ? $_POST['returns'] : [];

// Validate input
if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'অবৈধ অর্ডার আইডি']);
    exit;
}

if (empty($returns)) {
    echo json_encode(['success' => false, 'message' => 'কোনো ফেরতযোগ্য পণ্য নির্বাচিত হয়নি']);
    exit;
}

$processed_by = $_SESSION['userId'];
$total_return_amount = 0;
$return_count = 0;

// Start transaction
mysqli_begin_transaction($connection);

try {
    foreach ($returns as $return_item) {
        $order_item_id = intval($return_item['order_item_id']);
        $product_id = intval($return_item['product_id']);
        $return_quantity = floatval($return_item['return_quantity']); // Changed to floatval to support decimals
        
        // Skip if return quantity is 0 or negative
        if ($return_quantity <= 0) {
            continue;
        }
        
        // Get order item details
        $check_sql = "SELECT oi.quantity, oi.rate, p.product_name 
                      FROM order_item oi 
                      JOIN product p ON oi.product_id = p.product_id 
                      WHERE oi.order_item_id = ? AND oi.order_id = ?";
        $stmt = mysqli_prepare($connection, $check_sql);
        mysqli_stmt_bind_param($stmt, "ii", $order_item_id, $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order_item = mysqli_fetch_assoc($result);
        
        if (!$order_item) {
            throw new Exception("অর্ডার আইটেম পাওয়া যায়নি");
        }
        
        // Validate return quantity
        if ($return_quantity > $order_item['quantity']) {
            throw new Exception("ফেরতের পরিমাণ অর্ডার পরিমাণের চেয়ে বেশি হতে পারে না: " . $order_item['product_name']);
        }
        
        // Calculate return amount
        $return_amount = $return_quantity * $order_item['rate'];
        $total_return_amount += $return_amount;
        
        // Insert into order_returns table
        $insert_sql = "INSERT INTO order_returns 
                       (order_id, order_item_id, product_id, return_quantity, return_amount, processed_by) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $insert_sql);
        mysqli_stmt_bind_param($stmt, "iiidds", $order_id, $order_item_id, $product_id, 
                               $return_quantity, $return_amount, $processed_by);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("ফেরতের রেকর্ড সংরক্ষণে ব্যর্থ");
        }
        
        // Update product stock (supports decimal quantities)
        $update_stock_sql = "UPDATE product SET quantity = quantity + ? WHERE product_id = ?";
        $stmt = mysqli_prepare($connection, $update_stock_sql);
        mysqli_stmt_bind_param($stmt, "di", $return_quantity, $product_id); // Changed to 'di' to support decimal
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("স্টক আপডেটে ব্যর্থ");
        }
        
        $return_count++;
    }
    
    if ($return_count === 0) {
        throw new Exception("কোনো বৈধ ফেরতের পণ্য পাওয়া যায়নি");
    }
    
    // Commit transaction
    mysqli_commit($connection);
    
    echo json_encode([
        'success' => true, 
        'message' => $return_count . ' টি পণ্য সফলভাবে ফেরত দেওয়া হয়েছে',
        'return_amount' => $total_return_amount
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($connection);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

mysqli_close($connection);
?>
