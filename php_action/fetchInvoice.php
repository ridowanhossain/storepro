<?php     

require_once 'core.php';

 $sql = "SELECT invoice.company_id,invoice.total,invoice.paid, invoice.due,invoice.invoice_id, invoice.c_date,company.company_id,company.name,invoice.p_name FROM invoice  
   inner join company on invoice.company_id=company.company_id
			where invoice.status=1	 order by  invoice.invoice_id desc ";
$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 

 while($row = $result->fetch_array()) {

  $date = $row[5];
 	$date=date_create("$date");
 	$date =date_format($date,"d/m/Y");
	$brandId =$row[4];
 	$button = '	<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ব্যবস্থা <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">';
									$button .='<li><a type="button" onclick="editBrands('.$brandId.')" data-toggle="modal" data-target="#editinvoice"><i class="glyphicon glyphicon-edit"></i> পরিশোধ/সম্পাদনা</a></li>';
							if($_SESSION['Status']=='5'){
								$button .= '<li><a type="button" onclick="removeBrands('.$brandId.')" data-toggle="modal" data-target="#removeSrProduct"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';}						   
								$button .= '<li><a type="button" href="invoice-report.php?id='.$brandId.'" target="_blank"> <i class="fa fa-file-text"></i> প্রতিবেদন</a></li> 
							</ul>
						</div>';

 	$output['data'][] = array( 		
 	   $row[4],
 	   $row[7],
 	   $row[8],
 	   $row[1],
 		$row[2],
 		$row[3],
 		$date,
 		$button,
 		); 	
 } // /while 

}// if num_rows

$connect->close();

echo json_encode($output);