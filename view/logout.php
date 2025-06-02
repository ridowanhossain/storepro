<?php 

require_once 'php_action/core.php';

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy();

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: https://$host$uri");
exit;

?>