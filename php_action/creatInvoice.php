<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
   $post_date = date('Y-m-d');
	$brand = $_POST['brand'];
   $product = $_POST['product']; 
   $srquantity = $_POST['srquantity']; 
   $user = $_POST['user']; 
   $p_name = $_POST['p_name']; 

	$sql = "INSERT INTO invoice ( company_id, total, paid,due,status, c_date,p_name) VALUES ('$brand', '$product','$srquantity', '$user','1','$post_date','$p_name')";

	if($connect->query($sql) === TRUE) {
		$invoice_id = $connect->insert_id;
		$sqlreport = "INSERT INTO  invoice_report (paid_date, invoice_id,paid) VALUES ('$post_date', '$invoice_id','$srquantity')";
		 $connect->query($sqlreport);
	 	$valid['success'] = true;
		$valid['messages'] = "সফলভাবে যুক্ত হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST