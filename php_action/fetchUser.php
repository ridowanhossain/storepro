<?php 	

require_once 'core.php';

$sql = "SELECT * FROM users WHERE status != 5 ORDER BY user_id DESC";
$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 
	$i = 1;
	while($row = $result->fetch_array()) {
		$userid = $row['user_id'];
		$fullname = $row['full_name'];
		$username = $row['username'];
		$status = $row['status'];

		$label = "";
		if($status == 1) {
			$label = '<span class="label label-success">বিক্রেতা</span>';
		} else if($status == 2) {
			$label = '<span class="label label-danger">ম্যানেজার</span>';
		}

		$button = '
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				ব্যবস্থা <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" data-target="#editUserModal" onclick="editUser('.$userid.')"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>
				<li><a onclick="return confirm(\'Are you sure you delete this!!!\');" href="deletepost.php?dlt='.$userid.'"><i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>
			</ul>
		</div>';

		$output['data'][] = array( 		
			$i,
			$fullname,
			$username,
			$label,
			$button
		); 	
		$i++;
	} // /while 
} // if num_rows

$connect->close();

echo json_encode($output);
