<?php

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "store";

// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

// Use the same connection variables for procedural connection
$connection = mysqli_connect($localhost, $username, $password, $dbname);

?>