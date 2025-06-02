<?php require_once 'includes/header.php'; ?> 
<?php 
  if(!isset($_GET['dlt']) || $_GET['dlt'] == NULL ){
	  echo '<script>window.location=user.php</script>';
}else{
	$id = $_GET['dlt'];
}
 $query="SELECT * FROM users where user_id='$id' ";
$userQuery = $connect->query($query);
 while ($userResult = $userQuery->fetch_assoc()) 
	{ 

	$userid = $userResult['user_id'];
	}
						 
  $dlt="DELETE FROM users where user_id='$userid' ";
 $dlt = $connect->query($dlt);
  if($dlt){
  	 echo "<script>window.location='user.php';</script>";
  }else{
  	echo ' data not deleted';
  }
