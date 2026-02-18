<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$full_name1 = trim($_POST['full_name1']);
	$username = trim($_POST['username']);
	$password = md5($_POST['password']);
	$userrole = trim($_POST['userrole']);

	// Prepared statement to prevent SQL injection
	$stmt = $connect->prepare("INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `full_name`, `status`) VALUES (NULL, ?, ?, '', ?, ?)");
	$stmt->bind_param("ssss", $username, $password, $full_name1, $userrole);

	if($stmt->execute()) {
		$valid['success'] = true;
		$valid['messages'] = "ব্যবহারকারী সফলভাবে যোগ হয়েছে";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "ত্রুটি ঘটেছে, আবার চেষ্টা করুন";
	}
	$stmt->close();

	$connect->close();

	echo json_encode($valid);
}
