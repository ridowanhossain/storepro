<?php 

session_start();
date_default_timezone_set('Asia/Dhaka');

// Disable caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require_once 'db_connect.php';


// echo $_SESSION['userId'];

if(!$_SESSION['userId']) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: https://$host$uri");
	exit;	
} 

$soft_update = preg_replace('#\{.*?\}#si', $soft_version, '
	<div class="text-center" style="padding:90px 20%; color:green;">
	<h2>সন্মানিত গ্রাহক আপনি যে সেবার অনুসন্ধান করছেন বর্তমানে আমরা সেই সেবাটি প্রদান করতে না পারায় আন্তরিকভাবে দুঃখিত। </h2>
	<h2>পরবর্তী হালনাগাদে আপনাকে এই সেবাটি প্রদান করা হবে। </h2>
	<h2>আমাদের সাথে থাকার জন্য ধন্যবাদ। </h2>
	<h4 style="color:red;">VERSION: {$soft_version} </h4>
	</div>'); //Update
$e_message = '';
//$e_message = file_get_contents('https://demo.techogram.com/message/message.txt');

?>