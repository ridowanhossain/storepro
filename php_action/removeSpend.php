<?php

require_once 'core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$id = $_POST['spendId'];

if($id) {

 $sql = "DELETE FROM `spend` WHERE `spend`.`id` = $id;";

 if($connect->query($sql) === TRUE) {
 	$sqlreport = "DELETE FROM `spend_report` WHERE `spend_report`.`spend_id` = $id;";
 	$connect->query($sqlreport);
   $valid['success'] = true;
	$valid['messages'] = "সফলভাবে অপসারিত হয়েছে";
 } else {
 	$valid['success'] = false;
 	$valid['messages'] = "Error while remove the brand";
 }

 $connect->close();

 echo json_encode($valid);

} // /if $_POST
