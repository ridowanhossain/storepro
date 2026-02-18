<?php 	
require_once 'core.php';

// Fetch shop settings
$shopSettingsQuery = "SELECT owner_name, allproduct_name, shop_address, shop_mobile, company_name FROM shop_settings LIMIT 1";
$shopSettingsResult = $connect->query($shopSettingsQuery);
$shopSettings = $shopSettingsResult->fetch_assoc();

$owner_name = $shopSettings['owner_name'] ?? '';
$allproduct_name = $shopSettings['allproduct_name'] ?? '';
$shop_address = $shopSettings['shop_address'] ?? '';
$shop_mobile = $shopSettings['shop_mobile'] ?? '';
$company_name = $shopSettings['company_name'] ?? '';

if($_POST) {	
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];

	// Convert dates from dd/mm/yyyy to MySQL format (yyyy-mm-dd)
	$startDateObj = DateTime::createFromFormat('d/m/Y', $startDate);
	$endDateObj = DateTime::createFromFormat('d/m/Y', $endDate);
	
	$startDateFormatted = $startDateObj ? $startDateObj->format('Y-m-d') : date('Y-m-d');
	$endDateFormatted = $endDateObj ? $endDateObj->format('Y-m-d') : date('Y-m-d');

	$sql = "SELECT * FROM spend WHERE spend_date BETWEEN '$startDateFormatted' AND '$endDateFormatted' ORDER BY spend_date ASC";
	$result = $connect->query($sql);

	$table = '
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap");
		@page { margin: 10mm; }
		table {
			border-collapse: collapse;
			width: 100%;
		}
		th, td {
			border: 1px solid #ddd;
			padding: 2px;
			text-align: center;
			font-family: "Noto Serif Bengali", serif !important;
			font-size: 12px; 
		}
		th {
			background-color: #f2f2f2;
		}
		.text-right {
			text-align: right;
		}
        /* Right align the last column (Amount) */
		td:last-child, th:last-child {
			text-align: right;
            padding-right: 5px;
		}
		.header-info {
			text-align: center;
			margin-bottom: 20px;
			font-family: "Noto Serif Bengali", serif !important;
		}
		.total-row {
			background-color: #f9f9f9;
			font-weight: bold;
		}
		* {
			font-family: "Noto Serif Bengali", serif !important;
		}
	</style>
	
	<div class="header-info">
		<h3>খরচের প্রতিবেদন</h3>
		<p>তারিখ: ' . $startDate . ' থেকে ' . $endDate . '</p>
	</div>
	
	<table>
		<thead>
			<tr>
				<th>ক্রমিক নং</th>
				<th>আইডি</th>
				<th>ক্যাটাগরি</th>
				<th>বিবরণ</th>
				<th>তারিখ</th>
				<th>টাকা</th>
			</tr>
		</thead>
		<tbody>';

	$serial = 1;
	$grandTotal = 0;
	$grandPaid = 0;
	$grandDue = 0;

	while($row = $result->fetch_array()) {
		$spend_id = $row['id'];
		$c_name = $row['c_name'];
		$date = $row['spend_date'];
		$total_amount = $row['total'];
		
		// Parse category and description
        $category = 'অন্যান্য';
        $description = $c_name;
        
        if(preg_match('/^\[(.+?)\]\s*(.*)$/', $c_name, $matches)) {
            $category = $matches[1];
            $description = $matches[2];
        }

		// Format date
		$date_formatted = date_create($date);
		$date_formatted = date_format($date_formatted, "d/m/Y");
		
		// Add to grand total
		$grandTotal += $total_amount;

		$table .= '<tr>
			<td>' . $serial . '</td>
			<td>' . $spend_id . '</td>
			<td>' . $category . '</td>
			<td>' . $description . '</td>
			<td>' . $date_formatted . '</td>
			<td>' . number_format($total_amount, 2) . ' ৳</td>
		</tr>';
		
		$serial++;
	}

	// Add grand total row
	$table .= '<tr class="total-row">
		<td colspan="5" class="text-right">সর্বমোট</td>
		<td>' . number_format($grandTotal, 2) . ' ৳</td>
	</tr>';

	$table .= '</tbody></table>';

	echo $table;
}

$connect->close();
?>
