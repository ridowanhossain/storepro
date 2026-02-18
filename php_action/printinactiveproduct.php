<?php

require_once 'core.php';
$orderId = $_POST['1'];

$sql = "SELECT product.product_name, product.clor, product.quantity, brands.brand_name FROM product 
	INNER JOIN brands ON product.brand_id = brands.brand_id
	WHERE product.status = 2
	ORDER BY brands.brand_name ASC, product.product_name ASC ";
$orderResult = $connect->query($sql);

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
</style>
<table width="100%;">
	<tbody>
		<tr>
			<th>##</th>
			<th>ব্র্যান্ডের নাম</th>
			<th>পণ্যের নাম</th>
			<th>পরিমাণ</th>
		</tr>';

		$x = 1;


	$currentBrand = '';
	while($rows = $orderResult->fetch_array()) {
		// If the brand changes, reset serial
		if ($currentBrand !== $rows['brand_name']) {
			$currentBrand = $rows['brand_name'];
			$x = 1;
		}
		$table .= '<tr>
			<th>'.$x.'</th>
			<th>'.$rows['brand_name'].'</th>
			<th>'.$rows['product_name'].'</th>
			<th>'.$rows['quantity'].' '.$rows['clor'].'</th>
		</tr>';
		$x++;
	}

		$table .= '
	</tbody>
</table>
 ';


$connect->close();

echo $table;
