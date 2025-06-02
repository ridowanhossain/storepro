<?php     

require_once 'core.php';

 $sql = "SELECT spend.spend_date,spend.c_name,spend.total,spend.paid,spend.due,spend.id FROM spend where status=1
				 order by  spend.id desc ";
$result = $connect->query($sql);


$output = array('data' => array());

if($result->num_rows > 0) { 
    $x = 1;
    
 while($row = $result->fetch_array()) {

 	$id = $row[0];

 	$spendId =$row[5];
 	$button = '	<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ব্যবস্থা <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">';
							if($_SESSION['Status']=='5'){
								$button .= '<li><a type="button" onclick="editspend('.$spendId.')" data-toggle="modal" data-target="#editspend"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>
								<li><a type="button" onclick="removeBrands('.$spendId.')" data-toggle="modal" data-target="#removespend"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>'; } 

								$button .= '<li><a type="button" href="spend-report.php?id='.$spendId.'" target="_blank"> <i class="fa fa-file-text"></i> প্রতিবেদন</a></li>   
							</ul>
						</div>';
	  $date = $row[0];
    $date=date_create("$date");
	  $date =date_format($date,"d/m/Y");
 	$output['data'][] = array(
 		//id
 		$x,	
 	   $row[1], 
 	   $date, 
 		$row[2], 
 		$row[3],
 		$row[4],
 		$button
 		);
 	$x++;
 } // /while 

}// if num_rows

$connect->close();

echo json_encode($output);