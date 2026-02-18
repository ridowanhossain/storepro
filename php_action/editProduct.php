<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
	$productId = $_POST['productId'];
	$productName 		= $_POST['editProductName']; 
   $quantity 			= $_POST['editQuantity'];
   $addquantity 		= $_POST['addpro'];
   $rate 					= $_POST['editRate'];
   $brandName 			= $_POST['editBrandName'];
   $categoryName 	= $_POST['editCategoryName'];
   $productStatus 	= $_POST['editProductStatus'];
   $bRate 		= $_POST['editbRate'];
   $editclor 		= $_POST['editclor'];
   $editfeature 		= $_POST['editfeature'];
   $post_date = date('Y-m-d');
    $addquantity = str_replace(' ','',$addquantity); 
	$total_pro= $quantity+$addquantity;
 

	$sql = "UPDATE product SET product_name = '$productName', brand_id = '$brandName', categories_id = '$categoryName', quantity = '$total_pro', rate = '$rate', active = '$productStatus', status = 1, brate='$bRate',clor='$editclor',features='$editfeature' WHERE product_id = $productId ";
	if($addquantity !=''){
	$prosql = "INSERT INTO pro (pro_name, brand_name, cat_name, qty, pdate,brate,clor) 
		VALUES ('$productName', '$brandName', '$categoryName', '$addquantity', '$post_date', '$bRate','$editclor')";
		$connect->query($prosql);}

	if($connect->query($sql) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating product info";
	}

} // /$_POST
	 
$connect->close();

echo json_encode($valid);

