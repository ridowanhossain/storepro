<?php 	

require_once 'core.php';

$brandId = $_POST['brandId'];

 $sql = "SELECT company.name,company.status,company.company_id FROM company  
				 where company.company_id='$brandId'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);