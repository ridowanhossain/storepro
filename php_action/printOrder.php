<?php

require_once 'core.php';

// Get shop settings data
$settingsSql = "SELECT * FROM shop_settings WHERE id = 1";
$settingsResult = $connect->query($settingsSql);
$settings = $settingsResult->fetch_assoc();

// Store settings in variables
$shop_name = $settings['shop_name'];
$owner_name = $settings['owner_name'];
$allproduct_name = $settings['allproduct_name'];
$shop_address = $settings['shop_address'];
$shop_mobile = $settings['shop_mobile'];
$company_name = $settings['company_name'];
$contact_no = $settings['contact_no'];
$email_addr = $settings['email_addr'];

$orderId = $_POST['orderId'];

$sql = "SELECT orders.*,users.full_name FROM orders inner join users on
users.user_id = orders.s_name
 WHERE orders.order_id = $orderId";

$orderResult = $connect->query($sql);
$orderData = $orderResult->fetch_array();

$orderId = $orderData['order_id'];
$orderDate = $orderData['order_date'];
$date=date_create("$orderDate");
$date =date_format($date,"d/m/Y");
$clientName = $orderData['client_name'];
$clientContact = $orderData['client_contact'];
$subTotal = $orderData['sub_total'];
$vat = $orderData['vat'];
$totalAmount = $orderData['total_amount'];
$discount = $orderData['discount'];
$grandTotal = $orderData['grand_total'];
$paid = $orderData['paid'];
$due = $orderData['due'];
$seller = $orderData['full_name'];
$address = $orderData['address'];
$sr_id = $orderData['sr_id'];
$o_feature = $orderData['o_feature'];
$total_due='0';
$present_jer='0';
if($sr_id !=0 ){
$sqlp = "SELECT due FROM `orders` where sr_id='$sr_id' ";
$run = $connect->query($sqlp);
 while  ($row= $run->fetch_array())
 {
$total_due += $row['due'];
}
$present_jer = $total_due-$due;
}else {
 $total_due =$due;
}

$orderItemSql = "SELECT order_item.product_id, order_item.rate, order_item.quantity, order_item.total,
product.product_name FROM order_item
    INNER JOIN product ON order_item.product_id = product.product_id
 WHERE order_item.order_id = $orderId";
$orderItemResult = $connect->query($orderItemSql);


$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/store/';
$table = '
<style>
@font-face {
    font-family: \'SolaimanLipi\';
    src: url(\''.$baseUrl.'assests/fonts/SolaimanLipiNormal.eot\');
    src: url(\''.$baseUrl.'assests/fonts/SolaimanLipiNormal.eot\') format(\'embedded-opentype\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipiNormal.woff2\') format(\'woff2\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipiNormal.woff\') format(\'woff\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipiNormal.ttf\') format(\'truetype\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipiNormal.svg#SolaimanLipiNormal\') format(\'svg\');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}

p, h2{
    margin: 0;
    padding: 0;
}
th{text-align: left}
.text-right{text-align:right}
table {
    border-collapse: collapse;
    border: 1px solid #000;
}
table.table-bordered{border: 1px solid #000}
table.table-bordered>tbody>tr>th {
    border: 1px solid #000;
}
table>tbody>tr>th{
    padding-left: 15px;
}
table>tbody>tr>th.text-right{
    padding-right: 15px;
}
h2{
    padding-top:10px;
    margin-bottom: 0;
}
body, table>thead>tr>th, table>tbody>tr>td, table>tbody>tr>th{
    font-family: solaimanlipi;
    font-size: 10px;
}
table>tbody>tr>th>h2{
    font-size: 15px;
}
table.table-bordered>tbody>tr>th.no-border{
    border:0;
}
.f-p{
	float: left;
}
.f-pt{
	float: right;
	font-weight: 700;
}
.dokan{
	text-align: center;
	font-size: 24px;
}
.dd{
	position: relative;
	text-align: center;
	height: 50px;
}
.fl{
	position: absolute;
	top: 0;
	left: 0;
	font-size: 28px;
	padding: 0;
}
.fr{
	position: absolute;
	top: 0;
	right: 0;
	font-size: 28px;
	padding: 0;
}

</style>
<div>
	<h2 class="dokan">'.$shop_name.'</h2>
	<div class="dd">
		<p class="">'.$shop_address.' <br>'.$shop_mobile.'</p>
	</div>
</div>
<table class="" width="100%">
 	<thead></thead>
 	<tbody>
		<tr>
			<th><p>'.$owner_name.'</p></th>
			<th class="text-right"><p>ক্রেতার নামঃ '.$clientName.'</p></th>
		</tr>
		<tr>
			<th><p>'.$allproduct_name.'</p></th>
			<th class="text-right"><p>ঠিকানাঃ '.$address.'</p></th>
		</tr>
		<tr>
			<th><p>তারিখঃ '.$date.'</p></th>
			<th class="text-right"><p>মোবাইলঃ '.$clientContact.'</p></th>
		</tr>
		<tr>
			<th><p>আইডিঃ '.$orderId.'</p></th>
			<th class="text-right"><p>N.B.: '.$o_feature.'</p></th>
		</tr>
	</tbody>
</table>
<table  class="table-bordered" width="100%;">
	<thead></thead>
	<tbody>
		<tr>
			<th>Sl.no</th>
			<th>ব্র্যান্ড</th>
			<th>পণ্যের নাম</th>
			<th>দাম</th>
			<th>পরিমাণ</th>
			<th>মোট</th>
		</tr>';

		$x = 1;
		while($row = $orderItemResult->fetch_array()) {

			$brandSql = "SELECT  brands.brand_name FROM brands	INNER JOIN product ON brands.brand_id = product.brand_id  WHERE product.product_id = '$row[0]'";
		    $orderbrandResult = $connect->query($brandSql);
		    while($rows = $orderbrandResult->fetch_array()) {

			 $table .= '<tr>
				<th>'.$x.'</th>
				<th>'.$rows[0].'</th>
				<th>'.$row[4].'</th>
				<th>'.$row[1].'</th>
				<th>'.$row[2].'</th>
				<th>'.$row[3].'</th>
			</tr>
			';
		$x++;
		 	}} // /while

		$table .= '
		<tr>
			<th class="no-border" colspan="4"></th>
			<th>সমষ্টি</th>
			<th>'.$subTotal.'</th>
		</tr>

		<tr>
			<th class="no-border" colspan="4"></th>
			<th>ভ্যাট (0%)</th>
			<th>'.$vat.'</th>
		</tr>

		<tr>
			<th class="no-border" colspan="4"></th>
			<th>সর্বমোট</th>
			<th>'.$totalAmount.'</th>
		</tr>

		<tr>
			<th class="no-border" colspan="4"></th>
			<th>ছাড়</th>
			<th>'.$discount.'</th>
		</tr>

		<tr>
			<th class="no-border" colspan="4"></th>
			<th>পূর্ণাঙ্গ</th>
			<th>'.$grandTotal.'</th>
		</tr>

		<tr>
			<th class="no-border" colspan="2"><center>.........................</center></th>
			<th class="no-border" colspan="2"><center>.........................</center></th>
			<th>পরিশোধ</th>
			<th>'.$paid.'</th>
		</tr>

		<tr>
			<th class="no-border" colspan="2"><center>স্বাক্ষর(ক্রেতা)</center></th>
			<th class="no-border" colspan="2"><center>স্বাক্ষর(বিক্রেতা)</center></th>
			<th>বাঁকি</th>
			<th>'.$due.'</th>
		</tr>
        <tr>
            <th class="no-border" colspan="2"></th>
            <th class="no-border" colspan="2"><center>'.$seller.'<center></th>
            <th>পূর্বের জের</th>
            <th>'.$present_jer.'</th>
        </tr>
        <tr>
            <th class="no-border" colspan="4"></th>
            <th>মোট জের</th>
            <th>'.$total_due.'</th>
        </tr>
	</tbody>
</table>
<p class="f-p">'.$company_name.'</p>
<p class="f-pt">'.$contact_no.', '.$email_addr.'</p>
 ';


$connect->close();

echo $table;
