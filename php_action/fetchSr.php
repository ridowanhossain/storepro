<?php     

require_once 'core.php';

// Pre-fetch customer balances (Ledger based: Sales - Returns - Payments)
$customerBalances = array();
$balanceSql = "SELECT 
    sr.sr_id,
    (IFNULL(total_sales.amount, 0) - IFNULL(total_returns.amount, 0) - IFNULL(total_payments.amount, 0) + IFNULL(total_due.amount, 0)) as balance
FROM sr
LEFT JOIN (
    SELECT sr_id, SUM(grand_total) as amount FROM orders GROUP BY sr_id
) total_sales ON sr.sr_id = total_sales.sr_id
LEFT JOIN (
     SELECT o.sr_id, SUM(or_ret.return_amount) as amount 
     FROM order_returns or_ret
     JOIN orders o ON or_ret.order_id = o.order_id
     GROUP BY o.sr_id
) total_returns ON sr.sr_id = total_returns.sr_id
LEFT JOIN (
    SELECT sr_id, SUM(pement) as amount FROM pement_details GROUP BY sr_id
) total_payments ON sr.sr_id = total_payments.sr_id
LEFT JOIN (
    SELECT sr_id, SUM(due) as amount FROM due GROUP BY sr_id
) total_due ON sr.sr_id = total_due.sr_id
WHERE sr.b_status = 1";

$balanceResult = $connect->query($balanceSql);
if($balanceResult) {
	while ($bRow = $balanceResult->fetch_assoc()) {
		$customerBalances[$bRow['sr_id']] = $bRow['balance'];
	}
}

 $sql = "SELECT sr.name,sr.nmbr,sr.address,sr.c_date,sr.sr_id FROM sr where b_status =1
				 order by  sr.sr_id desc ";
$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 

 while($row = $result->fetch_array()) {

   $date = $row[3];
 	$date=date_create("$date");
 	$date =date_format($date,"d/m/Y");
 	$brandId =$row[4];
 	
 	// Calculate total due for this customer
 	$customerId = $row[4];
 	
 	// Use pre-calculated balance
 	$balance = isset($customerBalances[$customerId]) ? $customerBalances[$customerId] : 0;
    
    $dueAmount = 0;
    $excessAmount = 0;
    
    if ($balance > 0) {
        $dueAmount = $balance;
    } elseif ($balance < 0) {
        $excessAmount = abs($balance);
    }

 	$button = '	<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ব্যবস্থা <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">';
									if($_SESSION['Status'] == '1') {
										$button .='<li class="disabled"><a href="javascript:void(0)" style="cursor:not-allowed;color:#999;"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
									} else {
										$button .='<li><a type="button" onclick="editBrands('.$brandId.')" data-toggle="modal" data-target="#editSrProduct"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
									}
									$button .='<li><a type="button" onclick="prevDue('.$brandId.')" data-toggle="modal" data-target="#editprevdue"><i class="glyphicon glyphicon-edit"></i> পূর্বের বাঁকি</a></li>';
							if($_SESSION['Status']=='5'){
								$button .= '<li class="disabled"><a href="javascript:void(0);" style="cursor: not-allowed; opacity: 0.5;"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';}						   
								$button .= '<li><a type="button" href="customer-report='.$brandId.'" target="_blank"> <i class="fa fa-file-text"></i> প্রতিবেদন</a></li> 
							</ul>
						</div>';

 	$output['data'][] = array( 		
 	   $row[4],
 	   $row[0],
 	   $row[1],
 		$row[2],
 		$date,
 		number_format($dueAmount, 2) . ' ৳',
        number_format($excessAmount, 2) . ' ৳',
 		$button
 		); 	
 } // /while 

}// if num_rows

$connect->close();

echo json_encode($output);