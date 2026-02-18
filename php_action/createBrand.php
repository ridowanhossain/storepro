<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$brandName = trim($_POST['brandName']);
	$brandStatus = trim($_POST['brandStatus']); 

	$stmt = $connect->prepare("INSERT INTO brands (brand_name, brand_active, brand_status) VALUES (?, ?, 1)");
	$stmt->bind_param("ss", $brandName, $brandStatus);

	if($stmt->execute()) {
	 	$valid['success'] = true;
		$valid['messages'] = "সফলভাবে যুক্ত হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	$stmt->close();

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST