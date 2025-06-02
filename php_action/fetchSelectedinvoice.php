<?php 	

require_once 'core.php';

$brandId = $_POST['brandId'];

 $sql = "SELECT invoice.company_id,invoice.total,invoice.paid, invoice.due,invoice.invoice_id, invoice.c_date,invoice.p_name, company.company_id,company.name FROM invoice  
   inner join company on invoice.company_id=company.company_id where invoice.invoice_id='$brandId'";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);