<?php 	

require_once 'core.php';

 $sql = "SELECT order_item.order_id,order_item.order_date,product.product_name,brands.brand_name,orders.client_name,order_item.quantity FROM order_item   inner join product
						  on order_item.product_id = product.product_id
						  inner join orders 
						  on order_item.order_id = orders.order_id
						  inner join brands
							on product.brand_id = brands.brand_id
				  order by  order_item.order_id desc ";
$result = $connect->query($sql);


$output = array('data' => array());

if($result->num_rows > 0) { 

 while($row = $result->fetch_array()) {
 		$date = $row[1];
 		 $date=date_create("$date");
		 $date =date_format($date,"d/m/Y"); 
 	$output['data'][] = array( 		
 	   $row[0], 
 	   $date,  
 		$row[3],
 		$row[2],
 		$row[4],
 		$row[5],
 		); 	
 } // /while 

}// if num_rows

$connect->close();

echo json_encode($output);