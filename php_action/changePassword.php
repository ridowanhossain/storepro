<?php 

require_once 'core.php';

if($_POST) {

	$valid['success'] = array('success' => false, 'messages' => array());

	$currentPassword = md5($_POST['password']);
	$newPassword = md5($_POST['npassword']);
	$conformPassword = md5($_POST['cpassword']);
	$userId = intval($_POST['user_id']);

	// Prepared statement to prevent SQL injection
	$stmt = $connect->prepare("SELECT * FROM users WHERE user_id = ?");
	$stmt->bind_param("i", $userId);
	$stmt->execute();
	$query = $stmt->get_result();
	$result = $query->fetch_assoc();
	$stmt->close();

	if($currentPassword == $result['password']) {

		if($newPassword == $conformPassword) {

			// Prepared statement for update
			$updateStmt = $connect->prepare("UPDATE users SET password = ? WHERE user_id = ?");
			$updateStmt->bind_param("si", $newPassword, $userId);
			if($updateStmt->execute()) {
				$valid['success'] = true;
				$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while updating the password";	
			}
			$updateStmt->close();

		} else {
			$valid['success'] = false;
			$valid['messages'] = "New password does not match with Conform password";
		}

	} else {
		$valid['success'] = false;
		$valid['messages'] = "Current password is incorrect";
	}

	$connect->close();

	echo json_encode($valid);

}

?>