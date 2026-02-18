<?php 	

require_once 'core.php';

$productId = $_POST['productId'];

$sql = "SELECT product.product_id, product.product_name, product.product_image, product.brand_id, product.categories_id, product.quantity, product.rate, product.active, product.status,product.brate,product.clor,product.features,brands.brand_name  FROM product inner join brands on 
		brands.brand_id= product.brand_id
 WHERE product_id = $productId";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);