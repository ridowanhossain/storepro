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
		$sql = "SELECT pement_details.*,users.full_name FROM pement_details inner join users on
		 users.user_id = pement_details.s_name
		 WHERE pement_details.date >= '$start_date' AND pement_details.date <= '$end_date' ";
	}else{
		$sql = "SELECT pement_details.*,users.full_name FROM pement_details inner join users on
		 users.user_id = pement_details.s_name WHERE pement_details.date >= '$start_date' AND pement_details.date <= '$end_date' and pement_details.s_name ='$sname' ";
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
			<th>অর্ডার আইডি</th>
			<th>তারিখ</th>
			<th>বিক্রেতার নাম</th>
			<th>মোট</th>
		</tr>

		<tr>';
		$totalAmount = "";
		while ($result = $query->fetch_assoc()) {
			$date = $result['date'];
			$date=date_create("$date");
		 $date =date_format($date,"d-m-Y");
			$table .= '<tr>
				<td><center>'.$result['order_id'].'</center></td>
				<td><center>'.$date.'</center></td>
				<td><center>'.$result['full_name'].'</center></td>
				<td><center>'.$result['pement'].'</center></td>

			</tr>';
			$totalAmount += $result['pement'];
		}
		$table .= '
		</tr>

		<tr>
			<td colspan="3" class="text-right">সর্বমোট</td>
			<td><center>'.$totalAmount.'</center></td>
		</tr>
	</table>
	';

	echo $table;

}

?>
