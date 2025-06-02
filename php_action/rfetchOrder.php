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

// Total number of records without filtering
$totalRecords = $connect->query("SELECT COUNT(*) AS allcount FROM orders WHERE order_status = 1 AND sr_id != 0")->fetch_assoc();
$totalRecords = $totalRecords['allcount'];

// Total number of records with filtering
$totalRecordwithFilter = $connect->query("SELECT COUNT(*) AS allcount FROM orders 
    INNER JOIN users ON users.user_id = orders.s_name 
    WHERE orders.order_status = 1 AND sr_id != 0".$searchQuery)->fetch_assoc();
$totalRecordwithFilter = $totalRecordwithFilter['allcount'];

// Fetch records
$sql = "SELECT orders.order_id, orders.order_date, orders.client_name, 
        orders.client_contact, orders.payment_status, orders.address, 
        users.full_name, orders.o_feature, orders.sr_id, orders.due 
    FROM orders 
    INNER JOIN users ON users.user_id = orders.s_name 
    WHERE orders.order_status = 1 AND sr_id != 0".$searchQuery."
    ORDER BY orders.order_id ".$columnSortOrder." 
    LIMIT ".$row.",".$rowperpage;

$result = $connect->query($sql);
$data = array();
$counter = $row + 1;

while($row = $result->fetch_array()) {
    $orderId = $row[0];
    
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
        $button .= '<li><a href="regular-order-edit='.$orderId.'" id="editOrderModalBtn"> <i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
    }
    $button .='<li><a type="button" data-toggle="modal" id="paymentOrderModalBtn" data-target="#paymentOrderModal" onclick="paymentOrder('.$orderId.')"> <i class="glyphicon glyphicon-save"></i> পরিশোধ</a></li>
        <li><a type="button" href="regular-order-view='.$orderId.'" target="_blank"> <i class="fa fa-file-text"></i> প্রতিবেদন</a></li>
        <li><a type="button" onclick="printOrder('.$orderId.')"> <i class="glyphicon glyphicon-print"></i> প্রিন্ট </a></li>';
    if($_SESSION['Status']=='5'){
        $button .='<li><a type="button" data-toggle="modal" data-target="#removeOrderModal" id="removeOrderModalBtn" onclick="removeOrder('.$orderId.')"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';
    }
    $button .=' </ul></div>';

    $date = date_create($row[1]);
    $formattedDate = date_format($date,"d/m/Y");
    
    $data[] = array(
        "0" => $counter,
        "1" => $row[0],
        "2" => $formattedDate,
        "3" => $row[2],
        "4" => $row[3],
        "5" => $row[5],
        "6" => $row[7],
        "7" => number_format($row[9], 2) . ' ৳',  // Due amount with Taka symbol
        "8" => $paymentStatus,
        "9" => $row[6],
        "10" => $button
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
