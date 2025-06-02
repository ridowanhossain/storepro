<?php 
require_once 'db_connect.php';

// Prevent any unwanted output before JSON response
ob_start();

// Reading value
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$row = isset($_POST['start']) ? intval($_POST['start']) : 0;
$rowperpage = isset($_POST['length']) ? intval($_POST['length']) : 10;
$columnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 1;
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'order_id';
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Sanitize search value
$searchValue = mysqli_real_escape_string($connect, $searchValue);

// Search condition
$searchQuery = "";
if($searchValue != ''){
    $searchQuery = " AND (order_id LIKE '%".$searchValue."%' OR 
        client_name LIKE '%".$searchValue."%' OR 
        client_contact LIKE '%".$searchValue."%' OR 
        address LIKE '%".$searchValue."%' OR 
        o_feature LIKE '%".$searchValue."%' OR 
        s_name LIKE '%".$searchValue."%')";
}

// Total number of records without filtering
$totalRecords = $connect->query("SELECT COUNT(*) AS allcount FROM orders")->fetch_assoc();
$totalRecords = $totalRecords['allcount'];

// Total number of records with filtering
$totalRecordwithFilter = $connect->query("SELECT COUNT(*) AS allcount FROM orders WHERE 1 ".$searchQuery)->fetch_assoc();
$totalRecordwithFilter = $totalRecordwithFilter['allcount'];

// Column sorting
$orderBy = " ORDER BY ";
switch($columnIndex) {
    case 1: $orderBy .= "order_id"; break;
    case 2: $orderBy .= "order_date"; break;
    case 3: $orderBy .= "client_name"; break;
    case 4: $orderBy .= "client_contact"; break;
    case 5: $orderBy .= "address"; break;
    case 6: $orderBy .= "o_feature"; break;
    case 7: $orderBy .= "payment_status"; break;
    case 8: $orderBy .= "s_name"; break;
    default: $orderBy .= "order_id";
}
$orderBy .= " ".$columnSortOrder;

// Fetch records
$sql = "SELECT order_id, order_date, client_name, client_contact, address, o_feature, payment_status, s_name 
        FROM orders 
        WHERE 1 ".$searchQuery.$orderBy." 
        LIMIT ".$row.",".$rowperpage;
$result = $connect->query($sql);

$data = array();
$counter = $row + 1;

while($row = $result->fetch_array()) {
    $orderId = $row[0];
    $orderDate = date('d-m-Y', strtotime($row[1]));
    
    // payment status
    $paymentStatus = "";
    if($row[6] == 1) {
        $paymentStatus = "<label class='label label-success'>Full Payment</label>";
    } else if($row[6] == 2) {
        $paymentStatus = "<label class='label label-info'>Advance Payment</label>";
    } else {
        $paymentStatus = "<label class='label label-warning'>No Payment</label>";
    }

    // button
    $button = '
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ব্যবস্থা <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="editOrder.php?orderId='.$orderId.'" type="button">Edit</a></li>
            <li><a type="button" data-toggle="modal" data-target="#removeOrderModal" onclick="removeOrder('.$orderId.')">Remove</a></li>
        </ul>
    </div>';

    $data[] = array(
        $counter,
        $orderId,
        $orderDate,
        $row[2],
        $row[3],
        $row[4],
        $row[5],
        $paymentStatus,
        $row[7],
        $button
    );
    $counter++;
}

$response = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $data
);

// Clear any output buffering before sending JSON
ob_clean();

// Set proper JSON header
header('Content-Type: application/json');

// Encode with proper options
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit();
$connect->close();