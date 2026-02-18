<?php

require_once 'core.php';
$orderId = $_POST['3'];

$sql = "SELECT * from sr
    order by sr.sr_id desc
  ";
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
