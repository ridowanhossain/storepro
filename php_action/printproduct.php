<?php

require_once 'core.php';
$orderId = $_POST['1'];

$sql = "SELECT product.product_name,product.clor,product.quantity,brands.brand_name from product      inner join brands on
   product.brand_id = brands.brand_id
	where   product.status = 1
    order by product.product_id desc 
  ";
$orderResult = $connect->query($sql);

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
<table width="100%;">
	<tbody>
		<tr>
			<th>##</th>
			<th>ব্র্যান্ডের নাম</th>
			<th>পণ্যের নাম</th>
			<th>পরিমাণ</th>
			<th>পরিমাপ</th>
		</tr>';

		$x = 1;

		    while($rows = $orderResult->fetch_array()) {

			 $table .= '<tr>
				<th>'.$x.'</th>
				<th>'.$rows['brand_name'].'</th>
				<th>'.$rows['product_name'].'</th>
				<th>'.$rows['quantity'].'</th>
				<th>'.$rows['clor'].'</th>
			</tr>
			';
		$x++;
		 	} // /while

		$table .= '
	</tbody>
</table>
 ';


$connect->close();

echo $table;
