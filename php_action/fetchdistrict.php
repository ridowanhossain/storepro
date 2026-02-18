<?php 
require_once 'db_connect.php';

if(isset($_POST['division_id'])) {
    // Get customer data
    $productSql = "SELECT * FROM sr WHERE sr_id='".$_POST['division_id']."'";
    $productData = $connect->query($productSql);
    $row = $productData->fetch_array();
    
    $response = array(
        'name' => $row['name'],
        'nmbr' => $row['nmbr'],
        'address' => $row['address'],
        'sr_id' => $row['sr_id']
    );
    
    echo json_encode($response);
}
?>