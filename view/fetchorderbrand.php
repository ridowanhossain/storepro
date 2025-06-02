<?php require_once 'php_action/core.php';
	$output = '';
	$sql = "SELECT  brands.brand_name FROM brands	INNER JOIN product ON brands.brand_id = product.brand_id  WHERE product.product_id = '".$_POST['proid']."'";
	$result = $connect->query($sql);
  while($run =$result->fetch_array()) {
  			$output =$run['brand_name'];
  	 }	
  	 
  	 echo $output;
   
 ?>