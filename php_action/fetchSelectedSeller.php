<?php 	

require_once 'core.php';

$brandId = $_POST['brandId'];

 $sql = "SELECT brands.brand_id,product.product_id,users.user_id,seller.qty,seller.seller_id FROM seller  inner join brands
		  on seller.brand_id = brands.brand_id
		  inner join product 
		  on seller.product_id = product.product_id
		  inner join users
			on seller.user_id = users.user_id
				 where seller.seller_id='$brandId'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);