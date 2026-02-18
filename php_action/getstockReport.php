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

	$style = '
<style>
@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap");
@page { margin: 10mm; }
table{
	border-collapse: collapse;
	border: 1px solid #000;
	margin-bottom: 20px;
	width: 100%;
}
table>thead>tr>th, table>tbody>tr>td, table>tbody>tr>th{
	font-family: "Noto Serif Bengali", serif;
	font-size: 10px;
	border: 1px solid #000;
	padding: 2px;
	text-align: center;
}
h3 {
	font-family: "Noto Serif Bengali", serif;
	font-size: 14px;
	margin-bottom: 10px;
}
</style>';

	$table = $style;
	$hasContent = false;

	// New Stock List Section
	if($query->num_rows > 0) {
		$hasContent = true;
		$table .= '
		<center><h3>নতুন স্টকের তালিকা</h3></center>
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
		</table>';
	}

	// Returned Product List Section
	$returnSql = "SELECT or_ret.*, p.product_name, b.brand_name 
				  FROM order_returns or_ret
				  INNER JOIN product p ON or_ret.product_id = p.product_id
				  INNER JOIN brands b ON p.brand_id = b.brand_id
				  WHERE DATE(or_ret.return_date) >= '$start_date' AND DATE(or_ret.return_date) <= '$end_date'
				  ORDER BY or_ret.return_date DESC";
	$returnQuery = $connect->query($returnSql);

	if($returnQuery->num_rows > 0) {
		$hasContent = true;
		$table .= '
		<center><h3>ফেরতকৃত পণ্যের তালিকা</h3></center>
		<table width="100%">
			<thead>
				<tr>
					<th>ফেরতের তারিখ</th>
					<th>অর্ডার নং</th>
					<th>ব্র্যান্ড</th>
					<th>পণ্যের নাম</th>
					<th>পরিমাণ</th>
				</tr>
			</thead>
			<tbody>';
				while ($returnResult = $returnQuery->fetch_assoc()) {
					$retDate = date_create($returnResult['return_date']);
					$retDateFormatted = date_format($retDate, "d/m/Y");
					
					$table .= '<tr>
						<td><center>'.$retDateFormatted.'</center></td>
						<td><center>#'.$returnResult['order_id'].'</center></td>
						<td><center>'.$returnResult['brand_name'].'</center></td>
						<td><center>'.$returnResult['product_name'].'</center></td>
						<td><center>'.$returnResult['return_quantity'].'</center></td>
					</tr>';
				}
				$table .= '
			</tbody>
		</table>';
	}

	if(!$hasContent) {
		$table .= '<center><h3 style="color:red; margin-top:50px;">দুঃখিত, এই সময়ের মধ্যে কোনো স্টক বা ফেরতের তথ্য পাওয়া যায়নি।</h3></center>';
	}

	echo $table;

}

?>
