<?php 	

require_once 'core.php';


if($_POST) {	

  $full_name1 = $_POST['full_name1'];
  $username = $_POST['username']; 
  $password = md5($_POST['password']); 
  $userrole = $_POST['userrole']; 

	$sql = "INSERT INTO `stock`.`users` (`user_id`, `username`, `password`, `email`, `full_name`, `status`) VALUES (NULL, '$username', '$password', '', 'md  $full_name1', '$userrole')";

	$connect->query($sql);

 
}  // /if $_POST