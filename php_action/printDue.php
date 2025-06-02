<?php

require_once 'core.php';

// Get shop settings data
$settingsSql = "SELECT * FROM shop_settings WHERE id = 1";
$settingsResult = $connect->query($settingsSql);
$settings = $settingsResult->fetch_assoc();

$orderId = $_POST['2'];
$total_due='0';
$sql = "SELECT orders.order_id,orders.client_name,orders.client_contact,orders.address,orders.due FROM orders where orders.payment_status=3  order by  orders.order_id desc ";
$orderResult = $connect->query($sql);
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/store/';

$table = '
<style>
@font-face {
    font-family: \'SolaimanLipi\';
    src: url(\''.$baseUrl.'assests/fonts/SolaimanLipi.eot\');
    src: url(\''.$baseUrl.'assests/fonts/SolaimanLipi.eot\') format(\'embedded-opentype\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipi.woff2\') format(\'woff2\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipi.woff\') format(\'woff\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipi.ttf\') format(\'truetype\'),
         url(\''.$baseUrl.'assests/fonts/SolaimanLipi.svg#SolaimanLipi\') format(\'svg\');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}
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
.shop-header {
    text-align: center;
    margin-bottom: 20px;
}
.shop-header h2 {
    font-size: 20px;
    margin: 0;
    padding: 0;
}
.shop-header p {
    font-size: 12px;
    margin: 5px 0;
}
</style>
<div class="shop-header">
    <h2>'.$settings['shop_name'].'</h2>
    <p>'.$settings['shop_address'].'</p>
    <p>'.$settings['shop_mobile'].'</p>
    <h3>বাঁকির তালিকা</h3>
</div>
<table width="100%;">
    <tbody>
        <tr>
            <th>আইডি</th>
            <th>ক্রেতার নাম</th>
            <th>মোবাইল নাম্বার</th>
            <th>ঠিকানা</th>
            <th>বাঁকি</th>
        </tr>';

		$x = 1;

		    while($rows = $orderResult->fetch_array()) {
		    $total_due +=$rows['due'];
			 $table .= '<tr>
				<th>'.$rows['order_id'].'</th>
				<th>'.$rows['client_name'].'</th>
				<th>'.$rows['client_contact'].'</th>
				<th>'.$rows['address'].'</th>
				<th>'.$rows['due'].'</th>
			</tr>
			';
		$x++;


		 	} // /while
		 	 $table .='<tr>
     					<th colspan="4">সর্বমোট</th>
     					<th>'.$total_due.'</th>
   			  </tr>';

		$table .= '
	</tbody>
</table>
 ';


$connect->close();

echo $table;
