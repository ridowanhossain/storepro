<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$tdue = $_POST['tdue'];
    $Id = $_POST['Id'];

    // Always insert new record
    $sql = "INSERT INTO due (sr_id, due, date) VALUES ('$Id', '$tdue', NOW())";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "বাঁকি সফলভাবে আপডেট করা হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "ত্রুটি হয়েছে";
	}
	 
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST