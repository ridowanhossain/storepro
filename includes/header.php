<?php
require_once 'php_action/core.php';
?>

<?php
// Fetch shop name from database
$sql = "SELECT shop_name FROM shop_settings LIMIT 1";
$result = $connect->query($sql);
$shop_name = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $shop_name = $row['shop_name'];
}
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
    	<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title><?php echo $shop_name ?></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Disable caching -->
		<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="expires" content="0">
		<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="assests/plugins/datatables/jquery.dataTables.min.css">
		<link rel="stylesheet" href="assests/plugins/fileinput/css/fileinput.min.css">
		<link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
		<link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="assests/animate/animate.css">
		<link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">

		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- jQuery UI -->
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.min.css">
		<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js"></script>

		<!-- DataTables -->
		<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

        <!-- Add in the <head> section -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Chart.js for Dashboard Charts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>


	</head>
	<body>
		<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		<!-- Add your site or application content here -->
		<div id="wrapper">
			<div class="overlay"></div>

			<!-- Sidebar -->
			<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
                <ul class="nav sidebar-nav">
                    <li class="sidebar-brand">
                        <a href="dashboard"><?php echo $shop_name ?></a>
                        <p class="version" >Version : <?php echo $soft_version; ?></p>
                    </li>
                    <li class="active"><a href="dashboard"><i class="fa fa-fw fa-dashboard"></i> ড্যাসবোর্ড</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-shopping-cart"></i> অর্ডার <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">অর্ডার</li>
                            <li><a href="quick-order">দ্রুত অর্ডার</a></li>
                            <li><a href="regular-order">নিয়মিত অর্ডার</a></li>
                            <li><a href="quick-order-list">দ্রুত অর্ডার পরিচালনা</a></li>
                            <li><a href="regular-order-list">নিয়মিত অর্ডার পরিচালনা</a></li>
                        </ul>
                    </li>
                    <?php if($_SESSION['Status']=='5' || $_SESSION['Status']=='2') {  ?>
                    <li><a href="categories"><i class="fa fa-fw fa-file-o"></i> শ্রেণী</a></li>
                    <li><a href="brand"><i class="fa fa-fw fa-folder"></i> ব্র্যান্ড</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-bank"></i> পণ্য <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">পণ্য</li>
                            <li><a href="product">সক্রিয় পণ্য</a></li>
                            <li><a href="inactive-product">নিষ্ক্রিয় পণ্য</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['Status']=='5'||$_SESSION['Status']=='2'||$_SESSION['Status']=='1') { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-users"></i> ক্রেতা <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">ক্রেতা</li>
                            <li><a href="active-customer">সক্রিয় ক্রেতা</a></li>
                            <li><a href="inactive-customer">নিষ্ক্রিয় ক্রেতা</a></li>
                        </ul>
                    </li>
                    <?php } ?>

                    <li><a href="spend"><i class="fa fa-fw fa-exchange"></i> খরচ</a></li>
                    <?php if($_SESSION['Status']=='5') { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-line-chart"></i> প্রতিবেদন <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">রিপোর্ট</li>
                            <li><a href="stock-report">স্টক/ফেরতের প্রতিবেদন</a></li>
                            <li><a href="spend-reports">খরচের প্রতিবেদন</a></li>

                            <li><a href="profit-report">লাভের প্রতিবেদন</a></li>

                        </ul>
                    </li>
                    <li><a href="user"><i class="fa fa-fw fa-user"></i> ব্যবহারকারী</a></li>
                    <?php } ?>
                    
                    <?php if($_SESSION['Status']=='5') { ?>
                    <li><a href="settings"><i class="fa fa-fw fa-cog"></i>সেটিংস</a></li>
                    <?php } ?>
                </ul>
            </nav>
			<!-- Page Content -->
			<div id="page-content-wrapper">
				<div class="header-menu clearfix">
                    <button type="button" class="hamburger is-closed animated fadeInLeft" data-toggle="offcanvas" style="background: none !important;"><span class="hamb-top"></span><span class="hamb-middle"></span><span class="hamb-bottom"></span></button>
                    <div style="display: flex; align-items: center; gap: 20px; margin-left: auto;">
                        <p class="wel-user" style="margin: 0;">স্বাগতম <span><a href="user-setting"><?php echo $_SESSION['Fullname']; ?></a></span></p>
                        <a href="logout" class="logout"><i class="fa fa-lock"></i> Logout</a>
                    </div>
                </div>
