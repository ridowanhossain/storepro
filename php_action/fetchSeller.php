<?php 	

require_once 'core.php';

 $sql = "SELECT brands.brand_name,product.product_name,users.full_name,seller.qty,seller.seller_id FROM seller  inner join brands
		  on seller.brand_id = brands.brand_id
		  inner join product 
		  on seller.product_id = product.product_id
		  inner join users
			on seller.user_id = users.user_id
				 order by  seller.seller_id desc ";
$result = $connect->query($sql);


$output = array('data' => array());

if($result->num_rows > 0) { 

 while($row = $result->fetch_array()) {
 	$brandId =$row[4];
 	$button = '	<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ব্যবস্থা <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a type="button" onclick="editBrands('.$brandId.')" data-toggle="modal" data-target="#editSrProduct"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>
								<li><a type="button" onclick="removeBrands('.$brandId.')" data-toggle="modal" data-target="#removeSrProduct"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>      
							</ul>
						</div>';

 	$output['data'][] = array( 		
 	   $row[0], 
 	   $row[1], 
 		$row[2], 
 		$row[3],
 		$button
 		); 	
 } // /while 

}// if num_rows

$connect->close();

echo json_encode($output);