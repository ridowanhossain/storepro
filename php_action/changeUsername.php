<?php 

require_once 'core.php';

if($_POST) {

	$valid['success'] = array('success' => false, 'messages' => array());

	$username = $_POST['username'];
	$fullname = $_POST['fullname'];
	$userId = $_POST['user_id'];

	$sql = "UPDATE users SET username = '$username',full_name='$fullname'  WHERE user_id = {$userId}";
	if($connect->query($sql) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating product info";
	}

	$connect->close();

	echo json_encode($valid);

}

?>