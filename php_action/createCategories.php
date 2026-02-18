<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$categoriesName = trim($_POST['categoriesName']);
	$categoriesStatus = trim($_POST['categoriesStatus']); 

	$stmt = $connect->prepare("INSERT INTO categories (categories_name, categories_active, categories_status) VALUES (?, ?, 1)");
	$stmt->bind_param("ss", $categoriesName, $categoriesStatus);

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