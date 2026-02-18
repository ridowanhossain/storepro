<?php

require_once 'core.php';

if($_POST) {

    $startDate = $_POST['startDate'];
	$date = DateTime::createFromFormat('d/m/Y',$startDate);
	if(!$date) { die("তারিখ বিন্যাস সঠিক নয় (Start Date)"); }
	$start_date = $date->format("Y-m-d");


	$endDate = $_POST['endDate'];
	$format = DateTime::createFromFormat('d/m/Y',$endDate);
	if(!$format) { die("তারিখ বিন্যাস সঠিক নয় (End Date)"); }
	$end_date = $format->format("Y-m-d");
		$sql = "SELECT * FROM orders 	WHERE orders.order_date >= '$start_date' AND orders.order_date <= '$end_date'  ";

$query = $connect->query($sql);
if (!$query) {
    die("Error in Orders Query: " . $connect->error);
}

$bsql = "SELECT * FROM order_item 	WHERE order_item.order_date >= '$start_date' AND order_item.order_date <= '$end_date'  ";
$bquery = $connect->query($bsql);

if (!$bquery) {
    die("Error in Order Item Query: " . $connect->error . " SQL: " . $bsql);
}

    echo '<p>'. $startDate. '  থেকে '. $endDate.' পর্যন্ত লাভের বিবরণ'.'</p>';
	$table = '
<style>
@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap");
@page { margin: 10mm; }
table{
	border-collapse: collapse;
	border: 1px solid #000;
	width: 100%;
}
table>thead>tr>th, table>tbody>tr>td, table>tbody>tr>th{
	font-family: "Noto Serif Bengali", serif;
	font-size: 10px;
	border: 1px solid #000;
	padding: 2px;
	text-align: center;
}
.text-right{
	text-align:right;
	padding-right: 15px;
}
</style>

	<table style="width:100%; text-align: center;">
		<thead>
			<tr>
				<th>তারিখ</th>
				<th>অর্ডার আইডি</th>
				<th>বিক্রয় মূল্য</th>
				<th>ক্রয় মূল্য</th>
				<th>লাভ</th>
			</tr>
		</thead>
		<tbody>';
		
		$totalSale = 0;
		$totalBuy = 0;
		$totalProfit = 0;

		$combinedSql = "SELECT 
							orders.order_id, 
							orders.order_date, 
							orders.grand_total as sale_amount, 
							SUM(order_item.brate) as buy_amount 
						FROM orders 
						INNER JOIN order_item ON orders.order_id = order_item.order_id 
						WHERE orders.order_date >= '$start_date' AND orders.order_date <= '$end_date' 
						GROUP BY orders.order_id";

		$combinedQuery = $connect->query($combinedSql);

		if (!$combinedQuery) {
			die("Query Failed: " . $connect->error);
		}

		while ($row = $combinedQuery->fetch_assoc()) {
			$date = date('d/m/Y', strtotime($row['order_date']));
			$orderId = $row['order_id'];
			$sale = $row['sale_amount'];
			$buy = $row['buy_amount'];
			$profit = $sale - $buy;

			$totalSale += $sale;
			$totalBuy += $buy;
			$totalProfit += $profit;

			$table .= '<tr>
				<td>'.$date.'</td>
				<td>'.$orderId.'</td>
				<td>'.$sale.'</td>
				<td>'.$buy.'</td>
				<td>'.$profit.'</td>
			</tr>';
		}

		$table .= '
		</tbody>
		<tfoot>
			<tr style="font-weight: bold; background-color: #f0f0f0;">
				<td colspan="2" class="text-right">সর্বমোট</td>
				<td>'.$totalSale.'</td>
				<td>'.$totalBuy.'</td>
				<td>'.$totalProfit.'</td>
			</tr>
		</tfoot>
	</table>
	';

	echo $table;

}

?>
