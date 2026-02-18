<?php
require_once 'db_connect.php';

if(isset($_POST['customer_id'])) {
    $id = $_POST['customer_id'];
    
    // Prevent caching
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 1. Calculate Grand Total from Orders
    $orderSql = "SELECT SUM(grand_total) as grand_total FROM orders WHERE sr_id='$id'";
    $orderResult = $connect->query($orderSql);
    $orderRow = $orderResult->fetch_assoc();
    $grandTotalAll = floatval($orderRow['grand_total'] ?? 0);

    // 3. Calculate Total Returns (Using Exact Logic from customer-report.php with Joins)
    $returnSql = "SELECT SUM(or_ret.return_amount) as total_return 
                  FROM order_returns or_ret
                  INNER JOIN orders o ON or_ret.order_id = o.order_id
                  INNER JOIN product p ON or_ret.product_id = p.product_id
                  INNER JOIN brands b ON p.brand_id = b.brand_id
                  INNER JOIN order_item oi ON or_ret.order_item_id = oi.order_item_id
                  WHERE o.sr_id='$id'";
    $returnResult = $connect->query($returnSql);
    $returnRow = $returnResult->fetch_assoc();
    $totalReturnAll = floatval($returnRow['total_return'] ?? 0);

    // Get formatted payment rows AND Calculate Total Payment from rows to ensure consistency
    $sqlp = "SELECT p.*, u.full_name as collector_name FROM `pement_details` p 
            LEFT JOIN users u ON p.s_name = u.user_id 
            WHERE p.sr_id='$id' ORDER BY p.pement_id ASC";
    $run = $connect->query($sqlp);
    
    $paymentRows = '';
    $serial = 1;
    $totalPaymentAll = 0;

    while ($row = $run->fetch_array()) {
        $totalPaymentAll += $row['pement']; // Accumulate total payment from fetched rows
        
        $date = date_format(date_create($row['date']),"d/m/Y h:i A");
        $desc = ($row['order_id'] > 0) ? "অর্ডার #".$row['order_id'] : "সরাসরি জমা";
        $collector = $row['collector_name'] ? $row['collector_name'] : "N/A";
        
        $paymentRows .= '<tr>
            <td style="text-align: center;">' . $serial . '</td>
            <td style="text-align: center;">' . $date . '</td>
            <td style="text-align: left;">' . $desc . '</td>
            <td style="text-align: left;">' . $collector . '</td>
            <td style="text-align: right;">' . number_format($row['pement'], 2) . ' ৳</td>
        </tr>';
        $serial++;
    }

    // 4. Calculate Overall Due
    // Formula: (Total Price - Total Return) - Total Payment
    $overallDue = ($grandTotalAll - $totalReturnAll) - $totalPaymentAll;

    
    // Determine Label and Formatting for Due/Excess
    $dueLabel = ($overallDue < 0) ? "অতিরিক্ত জমা" : "বাঁকি";
    $displayDue = abs($overallDue);

    // Return JSON response
    echo json_encode([
        'success' => true,
        'paymentRows' => $paymentRows,
        'totalPayment' => number_format($totalPaymentAll, 2),
        'grandTotal' => number_format($grandTotalAll, 2), 
        'totalReturn' => number_format($totalReturnAll, 2),
        'dueLabel' => $dueLabel,
        'dueAmount' => number_format($displayDue, 2),
        'dueAmountRaw' => $overallDue // Send raw value for JS logic if needed
    ]);
    
    $connect->close();
}
?>
