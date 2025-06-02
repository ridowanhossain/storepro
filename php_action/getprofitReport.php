<?php

require_once 'core.php';

if($_POST) {

    $startDate = $_POST['startDate'];
	$date = DateTime::createFromFormat('d/m/Y',$startDate);
	$start_date = $date->format("Y-m-d");


	$endDate = $_POST['endDate'];
	$format = DateTime::createFromFormat('d/m/Y',$endDate);
	$end_date = $format->format("Y-m-d");
		$sql = "SELECT * FROM orders 	WHERE orders.order_date >= '$start_date' AND orders.order_date <= '$end_date'  ";

$query = $connect->query($sql);
$bsql = "SELECT * FROM order_item 	WHERE order_item.order_date >= '$start_date' AND order_item.order_date <= '$end_date'  ";

$bquery = $connect->query($bsql);
	    echo '<p>'. $startDate. '  থেকে '. $endDate.' পর্যন্ত লাভের বিবরণ'.'</p>';
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

	<table style="width:50%;">

		<tr>';
		$totalAmount = "";
		while ($rows = $query->fetch_assoc()) {
			    $date = $rows['order_date'];
				 $date=date_create("$date");
				 $date =date_format($date,"d/m/Y");
				 $total = $rows['grand_total'];
			$table .= '<tr>
			</tr>';
			$totalAmount += $rows['grand_total'];
		}
	  $kroy = "";
		while ($row = $bquery->fetch_assoc()) {
			$kroy += $row['brate'];
		}
		$lav =$totalAmount-$kroy;
		$table .= '
		</tr>

		<tr>
			<td colspan="1" class="text-right">সর্বমোট বিক্রয় মুল্য</td>
			<td><center>'.$totalAmount.'</center></td>
		</tr>
		<tr>
			<td colspan="1" class="text-right">সর্বমোট ক্রয় মুল্য</td>
			<td><center>'.$kroy.'</center></td>
		</tr>
		<tr>
			<td colspan="1" class="text-right">লাভ</td>
			<td><center>'.$lav.'</center></td>
		</tr>
	</table>
	';

	echo $table;

}

?>
