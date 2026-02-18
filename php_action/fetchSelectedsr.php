<?php 	

require_once 'core.php';

$brandId = $_POST['brandId'];

 $sql = "SELECT sr.name,sr.nmbr,sr.address,sr.b_status,sr.sr_id, IFNULL(due.due, 0) as due FROM sr 
		 LEFT JOIN due ON sr.sr_id = due.sr_id
		 WHERE sr.sr_id='$brandId'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);