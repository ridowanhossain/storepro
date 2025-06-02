<?php 	

require_once 'core.php';

$spendId = $_POST['spendId'];

 $sql = "SELECT spend.spend_date,spend.c_name,spend.total,spend.paid,spend.due,spend.id FROM spend where spend.id='$spendId'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);