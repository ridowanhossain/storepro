<?php 	

require_once 'core.php';

 $sql = "SELECT orders.order_id,orders.client_name,orders.client_contact,orders.address,orders.due FROM orders where orders.payment_status=3  order by  orders.order_id desc ";
$result = $connect->query($sql);


$output = array('data' => array());

if($result->num_rows > 0) { 

 while($row = $result->fetch_array()) {

 	$output['data'][] = array( 		
 	   $row[0], 
 	   $row[1],
 		$row[2], 
 		$row[3],
 		$row[4],
 		$button = '<a target="_blank" href="single-report.php?id='.$row[0].'">প্রতিবেদন</a>'
 		); 	
 } // /while 

}// if num_rows

$connect->close();

echo json_encode($output);