<?php 
require_once 'db_connect.php';

if(isset($_POST['division_id']) && isset($_POST['order_id'])) {
    // First get customer data
    $productSql = "SELECT * FROM sr WHERE sr_id='".$_POST['division_id']."'";
    $productData = $connect->query($productSql);
    $row = $productData->fetch_array();
    
    // Update the orders table
    $updateSql = "UPDATE orders SET 
        client_name = '".$row['name']."',
        client_contact = '".$row['nmbr']."',
        address = '".$row['address']."',
        sr_id = '".$row['sr_id']."'
        WHERE order_id = '".$_POST['order_id']."'";
    
    $result = $connect->query($updateSql);
    
    $response = array(
        'name' => $row['name'],
        'nmbr' => $row['nmbr'],
        'address' => $row['address'],
        'sr_id' => $row['sr_id'],
        'success' => $result ? true : false
    );
    
    echo json_encode($response);
}
?>