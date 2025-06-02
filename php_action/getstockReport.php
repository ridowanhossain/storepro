<?php

require_once 'core.php';

if($_POST) {

    $startDate = $_POST['startDate'];
	$date = DateTime::createFromFormat('d/m/Y',$startDate);
	$start_date = $date->format("Y-m-d");


	$endDate = $_POST['endDate'];
	$format = DateTime::createFromFormat('d/m/Y',$endDate);
	$end_date = $format->format("Y-m-d");

	$sql = "SELECT pro.*,brands.brand_name  FROM pro
		INNER JOIN brands
		on pro.brand_name = brands.brand_id
	 WHERE pdate >= '$start_date' AND pdate <= '$end_date' ";
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
</style>
	<table width="100%">
		<thead>
			<tr>
				<th>তারিখ</th>
				<th>ব্র্যান্ডের নাম</th>
				<th>পণ্যের নাম</th>
				<th>পরিমাণ</th>
			</tr>
		</thead>
		<tbody>';
			while ($result = $query->fetch_assoc()) {
					$date = $result['pdate'];
					 $date=date_create("$date");
					$date =date_format($date,"d/m/Y");
				$table .= '<tr>
					<td><center>'.$date.'</center></td>
					<td><center>'.$result['brand_name'].'</center></td>
					<td><center>'.$result['pro_name'].'</center></td>
					<td><center>'.$result['qty'].'</center></td>
				</tr>';
			}
			$table .= '
		</tbody>
	</table>
	';
	echo $table;

}

?>
