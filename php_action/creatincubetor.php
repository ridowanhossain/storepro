<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

   $startDate = $_POST['startDate'];
	$date = DateTime::createFromFormat('d/m/Y',$startDate);
	$startDate = $date->format("Y-m-d");
	$qty = $_POST['qty'];
   $price = $_POST['price']; 
   $aqty = $_POST['aqty']; 
   $eunit = $_POST['eunit']; 
   $b_status = $_POST['b_status']; 

	$sql = "INSERT INTO incubetor (startdate, qty, price,aqty,eunit,b_status) VALUES ('$startDate', '$qty',' $price', '$aqty','$eunit','$b_status')";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "সফলভাবে যুক্ত হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST