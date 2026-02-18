<?php
header('Content-Type: application/json');
require_once 'core.php';

// Reading value
$draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
$row = isset($_POST['start']) ? $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? $_POST['length'] : 10;
$columnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'order_id';
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Search condition
$searchQuery = "";
if($searchValue != '') {
    $searchQuery = " AND (orders.order_id LIKE '%".$searchValue."%' OR 
        orders.client_name LIKE '%".$searchValue."%' OR 
        orders.client_contact LIKE '%".$searchValue."%' OR 
        orders.address LIKE '%".$searchValue."%' OR 
        users.full_name LIKE '%".$searchValue."%' OR
        orders.o_feature LIKE '%".$searchValue."%' OR
        CASE 
            WHEN orders.due <= 0 THEN 'পরিশোধ'
            ELSE 'বাঁকি'
        END LIKE '%".$searchValue."%')";
}

// Date range filter
$dateQuery = "";
if(isset($_POST['fromDate']) && $_POST['fromDate'] != '' && isset($_POST['toDate']) && $_POST['toDate'] != '') {
    // Convert dd/mm/yyyy to yyyy-mm-dd for MySQL
    $fromDate = DateTime::createFromFormat('d/m/Y', $_POST['fromDate'])->format('Y-m-d');
    $toDate = DateTime::createFromFormat('d/m/Y', $_POST['toDate'])->format('Y-m-d');
    $dateQuery = " AND DATE(order_date) BETWEEN '$fromDate' AND '$toDate'";
}

// Total number of records without filtering
$totalRecords = $connect->query("SELECT COUNT(*) AS allcount FROM orders WHERE order_status = 1 AND sr_id != 0")->fetch_assoc();
$totalRecords = $totalRecords['allcount'];

// Total number of records with filtering
$totalRecordwithFilter = $connect->query("SELECT COUNT(*) AS allcount FROM orders 
    INNER JOIN users ON users.user_id = orders.s_name 
    WHERE orders.order_status = 1 AND sr_id != 0".$searchQuery.$dateQuery)->fetch_assoc();
$totalRecordwithFilter = $totalRecordwithFilter['allcount'];

// Fetch records
$sql = "SELECT orders.order_id, orders.order_date, orders.client_name, 
        orders.client_contact, orders.payment_status, orders.address, 
        users.full_name, orders.o_feature, orders.sr_id, orders.due, orders.paid, orders.grand_total 
    FROM orders 
    INNER JOIN users ON users.user_id = orders.s_name 
    WHERE orders.order_status = 1 AND sr_id != 0".$searchQuery.$dateQuery."
    ORDER BY orders.order_id ".$columnSortOrder." 
    LIMIT ".$row.",".$rowperpage;

$result = $connect->query($sql);
$data = array();
$counter = $row + 1;

while($row = $result->fetch_array()) {
    $orderId = $row[0];
    
    // Calculate total returns for this order
    $retSql = "SELECT COALESCE(SUM(return_amount), 0) as total_ret FROM order_returns WHERE order_id = '$orderId'";
    $retRes = $connect->query($retSql);
    $retRow = $retRes->fetch_assoc();
    $totalReturn = floatval($retRow['total_ret']);
    $payableAmount = floatval($row['grand_total']) - $totalReturn;
    
    // Remove this section as it's no longer needed
    // Get item count
    // $countOrderItemSql = "SELECT count(*) FROM order_item WHERE order_id = $orderId";
    // $itemCountResult = $connect->query($countOrderItemSql);
    // $itemCountRow = $itemCountResult->fetch_row();
    
    // Payment status based on due amount
    if($row[9] <= 0) {
        $paymentStatus = "<label class='label label-success'>পরিশোধ</label>";
    } else {
        $paymentStatus = "<label class='label label-danger'>বাঁকি</label>";
    }
    
    // Action buttons
    $button = '<!-- Single button -->
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle btn-a" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ব্যবস্থা <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">';
    if($_SESSION['Status']=='5' || $_SESSION['Status']=='2'){
        // $button .= '<li><a href="regular-order-edit='.$orderId.'" id="editOrderModalBtn"> <i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
        $button .= '<li><a type="button" style="cursor: not-allowed; color: #999; opacity: 0.6;" title="সাময়িকভাবে বন্ধ"> <i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
    }
    $button .='<li><a type="button" href="regular-order-view=' . $orderId . '" target="_blank"> <i class="fa fa-file-text"></i> প্রতিবেদন</a></li>
        <li><a type="button" onclick="printOrder(' . $orderId . ')"> <i class="glyphicon glyphicon-print"></i> প্রিন্ট </a></li>
        <li><a type="button" onclick="openReturnModal(' . $orderId . ')"> <i class="glyphicon glyphicon-retweet"></i> ফেরত</a></li>';
    if($_SESSION['Status']=='5'){
        // $button .='<li><a type="button" data-toggle="modal" data-target="#removeOrderModal" id="removeOrderModalBtn" onclick="removeOrder('.$orderId.')"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';
        $button .='<li><a type="button" style="cursor: not-allowed; color: #999; opacity: 0.6;" title="সাময়িকভাবে বন্ধ"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';
    }
    $button .=' </ul></div>';

    $date = date_create($row[1]);
    $formattedDate = date_format($date,"d/m/Y h:i A");
    
    $refundAmount = max(0, floatval($row['paid']) - $payableAmount);
    
    $data[] = array(
        "0" => "<div class='text-center'>".$counter."</div>",
        "1" => "<div class='text-center'>".$row[0]."</div>",
        "2" => "<div class='text-center'>".$formattedDate."</div>",
        "3" => "<div class='text-center'>".$row[2]."</div>",
        "4" => "<div class='text-center'>".$row[3]."</div>",
        "5" => "<div class='text-center'>".$row[5]."</div>",
        "6" => "<div class='text-right'>".number_format($row['grand_total'], 2) . ' ৳'."</div>",
        "7" => "<div class='text-right'>".number_format($totalReturn, 2) . ' ৳'."</div>",
        "8" => "<div class='text-center'>".$button."</div>"
    );
    $counter++;
}

// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
$connect->close();
