<?php 	
require_once 'core.php';

$spendId = $_POST['spendId'];
$spendId = $connect->real_escape_string($spendId);

$sql = "SELECT spend.id, spend.spend_date, spend.c_name, spend.total FROM spend WHERE spend.id = '$spendId'";
$result = $connect->query($sql);

$response = array();

if($result->num_rows > 0) { 
    $row = $result->fetch_assoc();
    
    $c_name = $row['c_name'];
    
    // Parse category and description from c_name format: [category] description
    $category = 'অন্যান্য';
    $description = $c_name;
    
    if(preg_match('/^\[(.+?)\]\s*(.*)$/', $c_name, $matches)) {
        $category = $matches[1];
        $description = $matches[2];
    }
    
    // Format date to dd/mm/yyyy for the form
    $dateObj = date_create($row['spend_date']);
    $formattedDate = date_format($dateObj, "d/m/Y");
    
    $response['id'] = $row['id'];
    $response['spend_date'] = $formattedDate;
    $response['category'] = $category;
    $response['description'] = $description;
    $response['total'] = $row['total'];
    $response['c_name'] = $c_name;
}

$connect->close();
echo json_encode($response);