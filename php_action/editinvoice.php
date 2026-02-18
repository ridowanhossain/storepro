<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$invoice_date = date('Y-m-d');
	$editcompany = $_POST['editcompany'];
	$totalamount = $_POST['edit_totalamount'];
	$editpaid = $_POST['editpaid'];
	$paidnow = $_POST['paidnow'];
	$due = $_POST['editdue'];
	$editp_name = $_POST['editp_name'];
	$Id = $_POST['Id'];
	$totalpaid = $editpaid+$paidnow;
	$sql = "UPDATE invoice SET company_id = '$editcompany', total = '$totalamount', paid= '$totalpaid', due= '$due', p_name='$editp_name' WHERE invoice_id = '$Id'";

	if($connect->query($sql) === TRUE) {
	  if($paidnow !='' && $paidnow !=0):
		$sqlreport = "INSERT INTO  invoice_report (paid_date, invoice_id,  paid) VALUES ('$invoice_date', '$Id',
		 '$paidnow')";
		 $connect->query($sqlreport);
		endif;
	 	$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST