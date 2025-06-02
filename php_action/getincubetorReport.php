<?php

require_once 'core.php';

if($_POST) {

    $startDate = $_POST['startDate'];
    $date = DateTime::createFromFormat('d/m/Y',$startDate);
	$start_date = $date->format("Y-m-d");


	$endDate = $_POST['endDate'];
	$format = DateTime::createFromFormat('d/m/Y',$endDate);
	$end_date = $format->format("Y-m-d");

	$sname = $_POST['sellername'];
	if($sname==''){
		$sql = "SELECT * from incubetor
		 WHERE incubetor.startdate >= '$start_date' AND incubetor.startdate <= '$end_date' ";
	}else{
		$sql = "SELECT * from incubetor
		 WHERE incubetor.startdate >= '$start_date' AND incubetor.startdate <= '$end_date' and incubetor.eunit ='$sname' ";
	}

	$query = $connect->query($sql);

	$table = '
<style>
table{
	border-collapse: collapse;
	border: 1px solid #000;
}
table>thead>tr>th, table>tbody>tr>td, table>tbody>tr>th{
	font-family: solaimanlipi;
	font-size: 10px;
	border: 1px solid #000;
}
.text-right{
	text-align:right;
	padding-right: 15px;
}
</style>
	<table style="width:100%;">
		<tr>
			<th><center>ইউনিট</center></th>
			<th><center>সেটার তারিখ</center></th>
			<th><center>ডেলিভারি তারিখ</center></th>
			<th><center>পরিমান</center></th>
			<th><center>নষ্ট</center></th>
		   <th><center>গড় নষ্ট</center></th>
		   <th><center>সর্বশেষ পরিমান</center></th>
			<th><center>দাম</center></th>
			<th><center>গড় দাম</center></th>
			<th><center>অবস্থা</center></th>
		</tr>

		<tr>';
		$totalAmount = "";
		while ($rows = $query->fetch_assoc()) {
			    $date = $rows['startdate'];
				 $delivery = date('Y-m-d', strtotime($date .' +21 day'));
			    $delivery=date_create("$delivery");
				 $delivery =date_format($delivery,"d/m/Y");
		        $qty = $rows['qty'];
		        $eunit = $rows['eunit'];
		        $price = $rows['price'];
		        $aqty = $rows['aqty'];
		          $status = $rows['b_status'];
			        if( $status ==1)
			        {
			          $b_status ='Running';
			        }else
			        {
			        	$b_status ='Delivered';
			        }
				$date=date_create("$date");
				 $date =date_format($date,"d/m/Y");
		       $prcntdamage = ($aqty*100)/$qty;
		       $prcntprice = $price/($qty-$aqty);
		       $lastqty = ($qty-$aqty);

			$table .= '<tr>
				<td><center> '.$eunit.'</center></td>
				<td><center> '.$date.'</center></td>
				<td><center> '.$delivery.'</center></td>
				<td><center> '.$qty.'</center></td>
				<td><center> '.$aqty.'</center></td>
				<td><center> '.$prcntdamage.' %</center></td>
				<td><center> '.$lastqty.'</center></td>
				<td><center> '.$price.'</center></td>
				<td><center> '.$prcntprice.'</center></td>
				<td><center> '.$b_status.'</center></td>
			</tr>';
			//$totalAmount += $result['pement'];
		}


	echo $table;

}

?>
