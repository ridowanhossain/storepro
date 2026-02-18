<?php 

require_once 'core.php';

if($_POST) {

	$valid['success'] = array('success' => false, 'messages' => array());

	$username = trim($_POST['username']);
	$fullname = trim($_POST['fullname']);
	$userId = intval($_POST['user_id']);

	// Prepared statement to prevent SQL injection
	$stmt = $connect->prepare("UPDATE users SET username = ?, full_name = ? WHERE user_id = ?");
	$stmt->bind_param("ssi", $username, $fullname, $userId);
	if($stmt->execute()) {
		$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating product info";
	}
	$stmt->close();

	$connect->close();

	echo json_encode($valid);

}

?>