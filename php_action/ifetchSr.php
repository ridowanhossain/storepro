<?php

require_once 'core.php';

 $sql = "SELECT sr.name,sr.nmbr,sr.address,sr.c_date,sr.sr_id FROM sr where b_status =2
				 order by  sr.sr_id desc ";
$result = $connect->query($sql);


$output = array('data' => array());

if($result->num_rows > 0) {

 while($row = $result->fetch_array()) {
 	 $date = $row[3];
 	$date=date_create("$date");
 	$date =date_format($date,"d/m/Y");
 	$brandId =$row[4];
 	$button = '	<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ব্যবস্থা <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">';
									$button .='<li><a type="button" onclick="editBrands('.$brandId.')" data-toggle="modal" data-target="#editSrProduct"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
										$button .='<li><a type="button" onclick="prevDue('.$brandId.')" data-toggle="modal" data-target="#editprevdue"><i class="glyphicon glyphicon-edit"></i> পূর্বের বাঁকি</a></li>';
							if($_SESSION['Status']=='5'){
								$button .= '<li><a type="button" onclick="removeBrands('.$brandId.')" data-toggle="modal" data-target="#removeSrProduct"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';}
								$button .= '<li><a type="button" href="customer-report.php?id='.$brandId.'" target="_blank"> <i class="fa fa-file-text"></i> প্রতিবেদন</a></li>
							</ul>
						</div>';

 	$output['data'][] = array(
 	   $row[4],
 	   $row[0],
 	   $row[1],
 		$row[2],
 		$date,
 		$button
 		);
 } // /while

}// if num_rows

$connect->close();

echo json_encode($output);
