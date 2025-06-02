<?php
require_once 'php_action/db_connect.php';

session_start();

if(isset($_SESSION['userId'])) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'dashboard';
	header("Location: https://$host$uri/$extra");
	exit;
}

$errors = array();

if($_POST) {

    $username = $_POST['username'];
	$password = $_POST['password'];

	if(empty($username) || empty($password)) {
		if($username == "") {
			$errors[] = "Username is required";
		}

		if($password == "") {
			$errors[] = "Password is required";
		}
	} else {
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $connect->query($sql);

		if($result->num_rows >= 1) {
			$password = md5($password);
			// exists
			$mainSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
			$mainResult = $connect->query($mainSql);

			if($mainResult->num_rows == 1) {
				$value = $mainResult->fetch_assoc();
				$user_id = $value['user_id'];
				$user_status = $value['status'];
				$fullname = $value['full_name'];

				// set session
				$_SESSION['userId'] = $user_id;
				$_SESSION['Status'] = $user_status;
				$_SESSION['Fullname'] = $fullname;

				$host  = $_SERVER['HTTP_HOST'];
				$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$extra = 'dashboard';
				header("Location: https://$host$uri/$extra");
				exit;
			} else{

				$errors[] = "Incorrect username/password combination";
			} // /else
		} else {
			$errors[] = "Username doesnot exists";
		} // /else
	} // /else not empty username // password

} // /if $_POST
?>

<html>

<head>
	<title><?php echo $shop_name; ?></title>
		<meta charset="utf-8">
		<link href="custom/css/login.css" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--webfonts-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:600italic,400,300,600,700' rel='stylesheet' type='text/css'>
		<!--//webfonts-->
</head>
<body>

				 <!-----start-main---->
				<div class="login-form">
						<h1>Sign In</h1>
						<div class="messages">
							<?php if($errors) {
								foreach ($errors as $key => $value) {
									echo '<div class="alert alert-warning" role="alert">
									<i class="glyphicon glyphicon-exclamation-sign"></i>
									'.$value.'</div>';
									}
								} ?>
						</div>
				<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
					<li>
						<input type="text" class="text" id="username" name="username" placeholder="Username" autocomplete="off" /><i href="#" class=" icon user"></i>
					</li>
					<li>
						<input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" /><i href="#" class=" icon lock"></i>
					</li>

					 <div class ="forgot">
						<input type="submit" value="Sign In" > <i href="#" class=" icon arrow"></i>
					</div>
					<li class="vdiv">
						<p>Version : <?php echo $soft_version; ?></p>
					</li>
				</form>
			</div>

		   </div>


		  <!-----start-copyright---->
   					<div class="copy-right">
						<p>Developed By <a href="http://www.codeoner.com">CODEONER</a></p>
					</div>
				<!-----//end-copyright---->

</body>
</html>
