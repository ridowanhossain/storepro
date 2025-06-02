<?php

require_once 'core.php';
$orderId = $_POST['3'];

$sql = "SELECT * from sr
    order by sr.sr_id desc
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
			<th>আইডি নাম্বার</th>
			<th>নাম</th>
			<th>মোবাইল নাম্বার</th>
			<th>ঠিকানা</th>
			<th>ক্রেতা যুক্ত করার তারিখ</th>
		</tr>';
		    while($rows = $orderResult->fetch_array()) {

			 $table .= '<tr>
				<th>'.$rows['sr_id'].'</th>
				<th>'.$rows['name'].'</th>
				<th>'.$rows['nmbr'].'</th>
				<th>'.$rows['address'].'</th>
				<th>'.$rows['c_date'].'</th>
			</tr>
			';
		 	} // /while

		$table .= '
	</tbody>
</table>
 ';


$connect->close();

echo $table;
