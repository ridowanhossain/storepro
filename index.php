<?php
// Force redirect index.php to / (only on GET, not POST)
$requestUri = $_SERVER['REQUEST_URI'];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($requestUri, '/index.php') !== false) {
    $newUri = str_replace('/index.php', '', $requestUri);
    // Remove multiple slashes if any
    $newUri = preg_replace('#/+#', '/', $newUri);
    // If empty after replacement (meaning it was just /index.php), set to /
    if ($newUri === '') $newUri = '/';
    
    header("Location: $newUri", true, 301);
    exit;
}

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

    $username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if(empty($username) || empty($password)) {
		if($username == "") {
			$errors[] = "Username is required";
		}

		if($password == "") {
			$errors[] = "Password is required";
		}
	} else {
		// Prepared statement to prevent SQL injection
		$stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();

		if($result->num_rows >= 1) {
			$password = md5($password);
			// Prepared statement for password check
			$mainStmt = $connect->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
			$mainStmt->bind_param("ss", $username, $password);
			$mainStmt->execute();
			$mainResult = $mainStmt->get_result();

			if($mainResult->num_rows == 1) {
				$value = $mainResult->fetch_assoc();
				$user_id = $value['user_id'];
				$user_status = $value['status'];
				$fullname = $value['full_name'];

				// Regenerate session ID to prevent session fixation
				session_regenerate_id(true);

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
			$mainStmt->close();
		} else {
			$errors[] = "Username doesnot exists";
		} // /else
		$stmt->close();
	} // /else not empty username // password

} // /if $_POST
?>

<html>

<head>
	<title><?php echo $shop_name; ?></title>
		<meta charset="utf-8">
		<link href="custom/css/login.css?v=<?php echo filemtime('custom/css/login.css'); ?>" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--webfonts-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:600italic,400,300,600,700' rel='stylesheet' type='text/css'>
		<!--//webfonts-->
</head>
<body>

				 <!-----start-main---->
				<div class="login-form">
						<h2><?php echo $shop_name; ?></h2>
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
				<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" id="loginForm">
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
						<p>Developed By <a href="http://www.techogram.com/service">Techogram</a></p>
					</div>
				<!-----//end-copyright---->

</body>
</html>
