<?php

require_once 'core.php';

if($_POST) {

    $startDate = $_POST['startDate'];
	$date = DateTime::createFromFormat('d/m/Y',$startDate);
	$start_date = $date->format("Y-m-d");


	$endDate = $_POST['endDate'];
	$format = DateTime::createFromFormat('d/m/Y',$endDate);
	$end_date = $format->format("Y-m-d");
		$sql = "SELECT order_item.order_id,order_item.order_date,order_item.total,product.product_name,product.clor,brands.brand_name,orders.client_name,orders.discount,orders.grand_total,order_item.quantity FROM order_item   inner join product
						  on order_item.product_id = product.product_id
						  inner join orders 
						  on order_item.order_id = orders.order_id
						  inner join brands
							on product.brand_id = brands.brand_id
				  WHERE order_item.order_date >= '$start_date' AND order_item.order_date <= '$end_date' order by  order_item.order_id desc ";

	$query = $connect->query($sql);
	


		$discountsql = "SELECT discount from orders
				  WHERE orders.order_date >= '$start_date' AND orders.order_date <= '$end_date' order by  orders.order_id desc ";

	    $disquery = $connect->query($discountsql);
	    $discounts='0';
	    while ($results = $disquery->fetch_assoc()) {
	    	$discounts +=$results['discount']; 
	    }


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
			<th>পণ্যের নাম</th>
			<th>ক্রেতার নাম</th>
			<th>পরিমাণ</th>
			<th>দাম</th>
			<th>ছাড়</th>
			<th>সর্বশেষ দাম</th>
		</tr>

		<tr>';
		$finalprice = "0";
		$totalqty = "0";
		$totalprice = "0";
		$totaldiscount = "0";
		while ($result = $query->fetch_assoc()) {
			$date = $result['order_date'];
			$date=date_create("$date");
			$discount = $result['discount'];
			$total = $result['total'];
			$dis = $total - $discount; 
		   $date =date_format($date,"d-m-Y");
			$table .= '<tr>
				<td><center>'.$result['order_id'].'</center></td>
				<td><center>'.$date.'</center></td>
				<td><center>'.$result['product_name'].'</center></td>
				<td><center>'.$result['client_name'].'</center></td>
				<td><center>'.$result['quantity'].' '.$result['clor'].'</center></td>
				<td><center>'.$result['total'].'</center></td>
				<td><center>'.$result['discount'].'</center></td>
				<td><center>'.$dis.'</center></td>

			</tr>';
			$finalprice += $result['grand_total'];
			$totalqty += $result['quantity'];
			$totalprice += $result['total'];
			$totaldiscount += $result['discount'];
			$finalprice = $totalprice - $discounts;
		}
		$table .= '
		</tr>

		<tr>
			<td colspan="4" class="text-right">সর্বমোট</td>
			<td><center>'.$totalqty.'</center></td>
			<td><center>'.$totalprice.'</center></td>
			<td><center>'.$discounts.'</center></td>
			<td><center>'.$finalprice.'</center></td>
		</tr>
	</table>
	';

	echo $table;

}

?>
