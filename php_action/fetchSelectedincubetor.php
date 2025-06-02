<?php 	

require_once 'core.php';

$brandId = $_POST['brandId'];

 $sql = "SELECT incubetor.startdate,incubetor.qty,incubetor.price,incubetor.aqty,incubetor.eunit,incubetor.b_status, incubetor.in_id FROM incubetor where incubetor.in_id='$brandId'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);