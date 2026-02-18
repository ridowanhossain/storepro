<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$fullname = trim($_POST['full_name2']);
	$username = trim($_POST['username']);
	$userid = intval($_POST['userid']);
	$userrole = trim($_POST['userrole']);
	$password = $_POST['password'];

	if($password != '') {
		$password = md5($password);
		// Prepared statement to prevent SQL injection
		$stmt = $connect->prepare("UPDATE `users` SET username=?, `full_name` = ?, password = ?, status = ? WHERE `user_id` = ?");
		$stmt->bind_param("ssssi", $username, $fullname, $password, $userrole, $userid);
	} else {
		// Prepared statement to prevent SQL injection
		$stmt = $connect->prepare("UPDATE `users` SET username=?, `full_name` = ?, status = ? WHERE `user_id` = ?");
		$stmt->bind_param("sssi", $username, $fullname, $userrole, $userid);
	}

	if($stmt->execute()) {
		$valid['success'] = true;
		$valid['messages'] = "ব্যবহারকারী সফলভাবে সম্পাদিত হয়েছে";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "ত্রুটি ঘটেছে, আবার চেষ্টা করুন";
	}
	$stmt->close();

	$connect->close();

	echo json_encode($valid);
}
