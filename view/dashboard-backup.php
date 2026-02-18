<?php require_once 'includes/header.php'; ?>
<!-- Page Content -->

<?php 

$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

//test 
$orderSql = "SELECT quantity FROM order_item ";
$orderQuery = $connect->query($orderSql);
$totalsell = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
	$totalsell += (int)$orderResult['quantity'];
}

$orderSqlbrand = "SELECT * FROM brands WHERE brand_active =1";
$brandQuery = $connect->query($orderSqlbrand);
$countbrand = $brandQuery->num_rows;

$orderSqlcategory = "SELECT * FROM categories";
$categoryQuery = $connect->query($orderSqlcategory);
$countcategory = $categoryQuery->num_rows;

$totalRevenue = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
	$totalRevenue += $orderResult['paid'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

// Out of Stock Count
$outOfStockSql = "SELECT * FROM product WHERE quantity = 0 AND status = 1";
$outOfStockQuery = $connect->query($outOfStockSql);
$countOutOfStock = $outOfStockQuery->num_rows;

// Total Customers Count
$customerSql = "SELECT COUNT(DISTINCT client_name) as customer_count FROM orders WHERE order_status = 1";
$customerQuery = $connect->query($customerSql);
$customerResult = $customerQuery->fetch_assoc();
$countCustomers = $customerResult['customer_count'] ?? 0;

// Total Due Amount
$dueSql = "SELECT SUM(due) as total_due FROM orders WHERE payment_status = 3";
$dueQuery = $connect->query($dueSql);
$dueResult = $dueQuery->fetch_assoc();
$totalDue = $dueResult['total_due'] ?? 0;

// Total Expenses
$expenseSql = "SELECT SUM(total) as total_expense FROM spend WHERE status = 1";
$expenseQuery = $connect->query($expenseSql);
$expenseResult = $expenseQuery->fetch_assoc();
$totalExpense = $expenseResult['total_expense'] ?? 0;

// Today's Revenue
$today = date('Y-m-d');
$todayRevenueSql = "SELECT SUM(paid) as today_revenue FROM orders WHERE order_date = '$today'";
$todayRevenueQuery = $connect->query($todayRevenueSql);
$todayRevenueResult = $todayRevenueQuery->fetch_assoc();
$todayRevenue = $todayRevenueResult['today_revenue'] ?? 0;

// Net Profit Calculation (Total Revenue - Total Expenses)
$totalRevenueSql = "SELECT SUM(paid) as total_revenue FROM orders WHERE order_status = 1";
$totalRevenueQuery = $connect->query($totalRevenueSql);
$totalRevenueResult = $totalRevenueQuery->fetch_assoc();
$totalRevenue = $totalRevenueResult['total_revenue'] ?? 0;
$netProfit = $totalRevenue - $totalExpense;

$connect->close();

?>

<!-- Quick Actions Buttons -->
<div class="clearfix" style="margin-bottom: 20px;">
	<div class="pull-right">
		<a href="quick-order" class="btn btn-primary" style="margin-right: 10px;">
			<i class="fa fa-shopping-cart"></i> Quick Order
		</a>
		<a href="product" class="btn btn-success" style="margin-right: 10px;">
			<i class="fa fa-plus-circle"></i> Add Product
		</a>
		<a href="active-customer" class="btn btn-info">
			<i class="fa fa-user-plus"></i> Add Customer
		</a>
	</div>
</div>

<!-- Alerts Section -->
<?php if($countLowStock > 0 || $countOutOfStock > 0) { ?>
<div class="clearfix" style="margin-bottom: 20px;">
	<?php if($countOutOfStock > 0) { ?>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong><i class="fa fa-exclamation-circle"></i> Out of Stock Alert!</strong> 
		<?php echo $countOutOfStock; ?> পণ্য স্টক শেষ হয়ে গেছে। অনুগ্রহ করে stock যোগ করুন।
		<a href="product" class="alert-link">View Products</a>
	</div>
	<?php } ?>
	
	<?php if($countLowStock > 0) { ?>
	<div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong><i class="fa fa-warning"></i> Low Stock Warning!</strong> 
		<?php echo $countLowStock; ?> পণ্যের stock কম আছে (৩টি বা তার কম)।
		<a href="product" class="alert-link">View Products</a>
	</div>
	<?php } ?>
</div>
<?php } ?>

<!-- Statistics Cards Row 1 -->
<div class="cn-body clearfix">
	<div class="col-md-3 clearfix">
		<div class="upper-cn upper-cn1 clearfix">
			<h4 class="">বিক্রয়</h4>
			<h2 class=""><?php echo $countOrder; ?></h2>
			<p class="ttn">সর্বমোট বিক্রয়</p>
			<i class="fa fa-line-chart cni"></i>
		</div>
	</div>
	<div class="col-md-3 clearfix">
		<div class="upper-cn upper-cn2 clearfix">
			<h4 class="">পণ্য</h4>
			<h2 class=""><?php echo $countProduct; ?></h2>
			<p class="ttn">সর্বমোট পণ্য</p>
			<i class="fa fa-bank cni"></i>
		</div>
	</div>
	<div class="col-md-3 clearfix">
		<div class="upper-cn upper-cn3 clearfix">
			<h4 class="">পণ্যের শ্রেণী</h4>
			<h2 class=""><?php echo $countcategory; ?></h2>
			<p class="ttn">সর্বমোট পণ্যের শ্রেণী</p>
			<i class="fa fa-money cni"></i>
		</div>
	</div>
	<div class="col-md-3 clearfix">
		<div class="upper-cn upper-cn4 clearfix">
			<h4 class="">ব্র্যান্ড</h4>
			<h2 class=""><?php echo $countbrand; ?></h2>
			<p class="ttn">সর্বমোট ব্র্যান্ড</p>
			<i class="fa fa-money cni"></i>
		</div>
	</div>
</div>
<!-- New Statistics Cards Row 2 -->
<div class="cn-body clearfix" style="margin-top: 20px;">
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn5 clearfix" style="background: linear-gradient(135deg, #c44569 0%, #d63447 100%);">
			<h4 class="">Low Stock</h4>
			<h2 class=""><?php echo $countLowStock; ?></h2>
			<p class="ttn">কম স্টক সতর্কতা</p>
			<i class="fa fa-exclamation-triangle cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn6 clearfix" style="background: linear-gradient(135deg, #1e88e5 0%, #0d7377 100%);">
			<h4 class="">ক্রেতা</h4>
			<h2 class=""><?php echo $countCustomers; ?></h2>
			<p class="ttn">সর্বমোট ক্রেতা</p>
			<i class="fa fa-users cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn7 clearfix" style="background: linear-gradient(135deg, #d35400 0%, #e67e22 100%);">
			<h4 class="">বাঁকি</h4>
			<h2 class=""><?php echo number_format($totalDue); ?> ৳</h2>
			<p class="ttn">সর্বমোট বাঁকি</p>
			<i class="fa fa-credit-card cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn8 clearfix" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);">
			<h4 class="">খরচ</h4>
			<h2 class=""><?php echo number_format($totalExpense); ?> ৳</h2>
			<p class="ttn">সর্বমোট খরচ</p>
			<i class="fa fa-shopping-cart cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn9 clearfix" style="background: linear-gradient(135deg, #16a085 0%, #c44569 100%);">
			<h4 class="">আজকের আয়</h4>
			<h2 class=""><?php echo number_format($todayRevenue); ?> ৳</h2>
			<p class="ttn">আজকের মোট আয়</p>
			<i class="fa fa-calendar-check-o cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn10 clearfix" style="background: linear-gradient(135deg, #27ae60 0%, #16a085 100%);">
			<h4 class="">নিট লাভ</h4>
			<h2 class=""><?php echo number_format($netProfit); ?> ৳</h2>
			<p class="ttn">মোট লাভ</p>
			<i class="fa fa-line-chart cni"></i>
		</div>
	</div>
</div>
<!-- Recent Activities Section - Modern Design -->
<div class="clearfix" style="margin-top: 40px; margin-bottom: 30px;">
	<h2 style="text-align: center; margin-bottom: 30px; font-size: 28px; color: #333;">
		<i class="fa fa-clock-o"></i> সাম্প্রতিক কার্যক্রম
	</h2>
	
	<div class="row">
		<!-- Recent Stock Section -->
		<div class="col-md-4">
			<div class="panel panel-default" style="border-top: 3px solid #5548c8; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
				<div class="panel-heading" style="background: linear-gradient(135deg, #5548c8 0%, #5e3a87 100%); color: white; border: none;">
					<h4 style="margin: 0; font-weight: bold;">
						<i class="fa fa-cubes"></i> সর্বশেষ স্টক
						<span class="badge" style="background: rgba(255,255,255,0.3); float: right;">10</span>
					</h4>
				</div>
				<div class="panel-body" style="padding: 0;">
					<?php  
						$stockSql = "SELECT p.pro_name, p.qty, p.clor, p.pdate, b.brand_name 
									 FROM pro p 
									 LEFT JOIN brands b ON p.brand_name = b.brand_id 
									 ORDER BY p.pro_id DESC 
									 LIMIT 10";
						$stockQuery = mysqli_query($connection, $stockSql);
					?>
					<?php while($stock = mysqli_fetch_array($stockQuery)){ 
						$stockDate = date_create($stock['pdate']);
						$formattedDate = date_format($stockDate, "d/m/Y");
					?>
					<div class="list-group-item" style="border-left: 4px solid #5548c8; margin-bottom: 0; transition: all 0.3s ease;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-9">
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 13px;">
									<?php echo $stock['brand_name'] ?? 'N/A'; ?> - <?php echo $stock['pro_name']; ?>
								</p>
								<p style="margin: 0; color: #555; font-size: 11px;">
									<i class="fa fa-calendar"></i> <?php echo $formattedDate; ?>
								</p>
							</div>
							<div class="col-xs-3 text-right">
								<span class="label" style="background: #43e97b; font-size: 12px; padding: 5px 10px;">
									<?php echo $stock['qty']; ?> <?php echo $stock['clor']; ?>
								</span>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<!-- Recent Orders Section -->
		<div class="col-md-4">
			<div class="panel panel-default" style="border-top: 3px solid #d63447; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
				<div class="panel-heading" style="background: linear-gradient(135deg, #c44569 0%, #d63447 100%); color: white; border: none;">
					<h4 style="margin: 0; font-weight: bold;">
						<i class="fa fa-shopping-cart"></i> সর্বশেষ অর্ডার
						<span class="badge" style="background: rgba(255,255,255,0.3); float: right;">10</span>
					</h4>
				</div>
				<div class="panel-body" style="padding: 0;">
					<?php  
						$orderSql = "SELECT oi.order_date, oi.order_id, oi.quantity, 
									 p.product_name, b.brand_name 
									 FROM order_item oi 
									 LEFT JOIN product p ON oi.product_id = p.product_id 
									 LEFT JOIN brands b ON p.brand_id = b.brand_id 
									 ORDER BY oi.order_item_id DESC 
									 LIMIT 10";
						$orderQuery = mysqli_query($connection, $orderSql);
					?>
					<?php while($order = mysqli_fetch_array($orderQuery)){ 
						$orderDate = date_create($order['order_date']);
						$formattedDate = date_format($orderDate, "d/m/Y");
					?>
					<div class="list-group-item" style="border-left: 4px solid #d63447; margin-bottom: 0; transition: all 0.3s ease;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-9">
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 13px;">
									<?php echo $order['brand_name'] ?? 'N/A'; ?> - <?php echo $order['product_name']; ?>
								</p>
								<p style="margin: 0; color: #555; font-size: 11px;">
									<i class="fa fa-calendar"></i> <?php echo $formattedDate; ?> 
									<span style="margin-left: 10px;">
										<i class="fa fa-hashtag"></i> <?php echo $order['order_id']; ?>
									</span>
								</p>
							</div>
							<div class="col-xs-3 text-right">
								<span class="label" style="background: #4facfe; font-size: 12px; padding: 5px 10px;">
									<?php echo $order['quantity']; ?> টি
								</span>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<!-- Recent Due Section -->
		<div class="col-md-4">
			<div class="panel panel-default" style="border-top: 3px solid #e67e22; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
				<div class="panel-heading" style="background: linear-gradient(135deg, #d35400 0%, #e67e22 100%); color: white; border: none;">
					<h4 style="margin: 0; font-weight: bold;">
						<i class="fa fa-exclamation-circle"></i> সর্বশেষ বাঁকি
						<span class="badge" style="background: rgba(255,255,255,0.3); float: right;">10</span>
					</h4>
				</div>
				<div class="panel-body" style="padding: 0;">
					<?php  
						$dueSql = "SELECT oi.order_date, oi.quantity, o.client_name, 
								   p.product_name, b.brand_name 
								   FROM order_item oi 
								   LEFT JOIN orders o ON oi.order_id = o.order_id 
								   LEFT JOIN product p ON oi.product_id = p.product_id 
								   LEFT JOIN brands b ON p.brand_id = b.brand_id 
								   WHERE oi.payment_status = 3 
								   ORDER BY oi.order_id DESC 
								   LIMIT 10";
						$dueQuery = mysqli_query($connection, $dueSql);
					?>
					<?php while($due = mysqli_fetch_array($dueQuery)){ 
						$dueDate = date_create($due['order_date']);
						$formattedDate = date_format($dueDate, "d/m/Y");
					?>
					<div class="list-group-item" style="border-left: 4px solid #e67e22; margin-bottom: 0; transition: all 0.3s ease;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-9">
				<p style="margin: 0; font-weight: bold; color: #333; font-size: 13px;">
					<?php echo $due['client_name']; ?>
				</p>
				<p style="margin: 0; color: #555; font-size: 11px;">
					<i class="fa fa-calendar"></i> <?php echo $formattedDate; ?>
					<span style="margin-left: 10px; color: #444;">
						<?php echo $due['brand_name'] ?? 'N/A'; ?> - <?php echo $due['product_name']; ?>
					</span>
				</p>
			</div>
							<div class="col-xs-3 text-right">
								<span class="label label-warning" style="font-size: 12px; padding: 5px 10px;">
									<?php echo $due['quantity']; ?> টি
								</span>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Top Performers Section -->
<div class="last-ten clearfix" style="margin-top: 30px;">
	<div class="col-md-4">
		<div class="last-report">
			<h2 class="ten-header" style="background: linear-gradient(135deg, #5548c8 0%, #5e3a87 100%); color: white;">🏆 Top 5 Selling Products</h2>
			<div class="ten-report">
				<?php  
					$topProductsSql = "SELECT p.product_name, b.brand_name, SUM(oi.quantity) as total_sold 
									   FROM order_item oi 
									   INNER JOIN product p ON oi.product_id = p.product_id 
									   INNER JOIN brands b ON p.brand_id = b.brand_id 
									   GROUP BY oi.product_id 
									   ORDER BY total_sold DESC 
									   LIMIT 5";
					$topProductsQuery = mysqli_query($connection, $topProductsSql);
					$rank = 1;
				?>
				<?php while($row = mysqli_fetch_array($topProductsQuery)){ ?>
					<div class="s-ten-report" style="border-left: 4px solid #5548c8;">
						<p class="r-left" style="font-weight: bold; color: #5548c8;">#<?php echo $rank; ?></p>
						<p class="r-model"><?php echo $row['brand_name']; ?> - <?php echo $row['product_name']; ?></p>
						<p class="r-right" style="background: #5548c8; color: white; padding: 2px 8px; border-radius: 10px;"><?php echo $row['total_sold']; ?> টি</p>
						<br>
					</div>
				<?php $rank++; } ?>
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="last-report">
			<h2 class="ten-header" style="background: linear-gradient(135deg, #c44569 0%, #d63447 100%); color: white;">👥 Top 5 Customers</h2>
			<div class="ten-report">
				<?php  
					$topCustomersSql = "SELECT c.client_name, c.client_contact, SUM(c.grand_total) as total_purchase 
										FROM orders c 
										WHERE c.order_status = 1 
										GROUP BY c.client_name 
										ORDER BY total_purchase DESC 
										LIMIT 5";
					$topCustomersQuery = mysqli_query($connection, $topCustomersSql);
					$rank = 1;
				?>
				<?php while($row = mysqli_fetch_array($topCustomersQuery)){ ?>
					<div class="s-ten-report" style="border-left: 4px solid #d63447;">
						<p class="r-left" style="font-weight: bold; color: #d63447;">#<?php echo $rank; ?></p>
						<p class="r-model"><?php echo $row['client_name']; ?></p>
						<p class="r-right" style="background: #d63447; color: white; padding: 2px 8px; border-radius: 10px;"><?php echo number_format($row['total_purchase']); ?> ৳</p>
						<br>
					</div>
				<?php $rank++; } ?>
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="last-report">
			<h2 class="ten-header" style="background: linear-gradient(135deg, #1e88e5 0%, #0d7377 100%); color: white;">🔥 Top 5 Brands</h2>
			<div class="ten-report">
				<?php  
					$topBrandsSql = "SELECT b.brand_name, SUM(oi.quantity) as total_sold 
									 FROM order_item oi 
									 INNER JOIN product p ON oi.product_id = p.product_id 
									 INNER JOIN brands b ON p.brand_id = b.brand_id 
									 GROUP BY b.brand_id 
									 ORDER BY total_sold DESC 
									 LIMIT 5";
					$topBrandsQuery = mysqli_query($connection, $topBrandsSql);
					$rank = 1;
				?>
				<?php while($row = mysqli_fetch_array($topBrandsQuery)){ ?>
					<div class="s-ten-report" style="border-left: 4px solid #1e88e5;">
						<p class="r-left" style="font-weight: bold; color: #1e88e5;">#<?php echo $rank; ?></p>
						<p class="r-model"><?php echo $row['brand_name']; ?></p>
						<p class="r-right" style="background: #1e88e5; color: white; padding: 2px 8px; border-radius: 10px;"><?php echo $row['total_sold']; ?> টি</p>
						<br>
					</div>
				<?php $rank++; } ?>
			</div>
		</div>
	</div>
</div>

<!-- Sales Reports Section - Modern Design -->
<div class="clearfix" style="margin-top: 40px; margin-bottom: 40px;">
	<h2 style="text-align: center; margin-bottom: 30px; font-size: 28px; color: #333;">
		<i class="fa fa-bar-chart"></i> বিক্রয় রিপোর্ট
	</h2>
	
	<div class="row">
		<?php
		// Define all reports with their configurations
		$reports = [
			[
				'title' => 'আজ',
				'icon' => 'fa-calendar-check-o',
				'color' => '#5548c8',
				'gradient' => 'linear-gradient(135deg, #5548c8 0%, #5e3a87 100%)',
				'date_condition' => "o.order_date = '" . date('Y-m-d') . "'",
				'date_condition_oi' => "oi.order_date = '" . date('Y-m-d') . "'"
			],
			[
				'title' => 'গতকাল',
				'icon' => 'fa-calendar-minus-o',
				'color' => '#d63447',
				'gradient' => 'linear-gradient(135deg, #c44569 0%, #d63447 100%)',
				'date_condition' => "o.order_date = '" . date('Y-m-d', strtotime('-1 days')) . "'",
				'date_condition_oi' => "oi.order_date = '" . date('Y-m-d', strtotime('-1 days')) . "'"
			],
			[
				'title' => 'এই সপ্তাহ',
				'icon' => 'fa-calendar',
				'color' => '#1e88e5',
				'gradient' => 'linear-gradient(135deg, #1e88e5 0%, #0d7377 100%)',
				'date_condition' => "o.order_date BETWEEN '" . date('Y-m-d', strtotime('monday this week')) . "' AND '" . date('Y-m-d', strtotime('sunday this week')) . "'",
				'date_condition_oi' => "oi.order_date BETWEEN '" . date('Y-m-d', strtotime('monday this week')) . "' AND '" . date('Y-m-d', strtotime('sunday this week')) . "'"
			],
			[
				'title' => 'এই মাস',
				'icon' => 'fa-calendar-o',
				'color' => '#27ae60',
				'gradient' => 'linear-gradient(135deg, #27ae60 0%, #16a085 100%)',
				'date_condition' => "o.order_date BETWEEN '" . date('Y-m-01') . "' AND '" . date('Y-m-t') . "'",
				'date_condition_oi' => "oi.order_date BETWEEN '" . date('Y-m-01') . "' AND '" . date('Y-m-t') . "'"
			],
			[
				'title' => 'সর্বশেষ ৭ দিন',
				'icon' => 'fa-calendar-plus-o',
				'color' => '#e67e22',
				'gradient' => 'linear-gradient(135deg, #d35400 0%, #e67e22 100%)',
				'date_condition' => "o.order_date > (CURDATE()) - INTERVAL 7 DAY",
				'date_condition_oi' => "oi.order_date > (CURDATE()) - INTERVAL 7 DAY"
			],
			[
				'title' => 'সর্বশেষ ৩০ দিন',
				'icon' => 'fa-calendar-times-o',
				'color' => '#c0392b',
				'gradient' => 'linear-gradient(135deg, #c0392b 0%, #d35400 100%)',
				'date_condition' => "o.order_date > (CURDATE()) - INTERVAL 30 DAY",
				'date_condition_oi' => "oi.order_date > (CURDATE()) - INTERVAL 30 DAY"
			],
			[
				'title' => 'গত মাস',
				'icon' => 'fa-history',
				'color' => '#6c5ce7',
				'gradient' => 'linear-gradient(135deg, #6c5ce7 0%, #5f3dc4 100%)',
				'date_condition' => "YEAR(o.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(o.order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)",
				'date_condition_oi' => "YEAR(oi.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(oi.order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)"
			],
			[
				'title' => 'গত বছর',
				'icon' => 'fa-clock-o',
				'color' => '#e84393',
				'gradient' => 'linear-gradient(135deg, #e84393 0%, #c0392b 100%)',
				'date_condition' => "YEAR(o.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)",
				'date_condition_oi' => "YEAR(oi.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)"
			]
		];
		
		foreach($reports as $report) {
			// Get sales data
			$salesSql = "SELECT 
						 COUNT(*) as total_orders,
						 SUM(oi.quantity) as total_qty,
						 SUM(o.paid) as total_paid,
						 SUM(o.grand_total) as grand_total,
						 SUM(o.due) as total_due
						 FROM orders o
						 LEFT JOIN order_item oi ON o.order_id = oi.order_id
						 WHERE " . $report['date_condition'];
			$salesResult = mysqli_query($connection, $salesSql);
			$salesData = mysqli_fetch_assoc($salesResult);
			
			// Get profit if user has permission
			$profit = 0;
			if($_SESSION['Status'] == '5') {
				$profitSql = "SELECT SUM(brate) as total_cost FROM order_item oi WHERE " . $report['date_condition_oi'];
				$profitResult = mysqli_query($connection, $profitSql);
				$profitData = mysqli_fetch_assoc($profitResult);
				$profit = ($salesData['grand_total'] ?? 0) - ($profitData['total_cost'] ?? 0);
			}
		?>
		
		<div class="col-md-3" style="margin-bottom: 20px;">
			<div class="panel panel-default" style="border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
				<div class="panel-heading" style="background: <?php echo $report['gradient']; ?>; color: white; border: none; padding: 15px;">
					<h4 style="margin: 0; font-weight: bold; font-size: 16px;">
						<i class="fa <?php echo $report['icon']; ?>"></i> <?php echo $report['title']; ?>
					</h4>
				</div>
				<div class="panel-body" style="padding: 20px;">
					<!-- Main Sales Number -->
					<div style="text-align: center; margin-bottom: 20px;">
						<h1 style="margin: 0; font-size: 42px; font-weight: bold; color: <?php echo $report['color']; ?>;">
							<?php echo number_format($salesData['total_qty'] ?? 0); ?>
						</h1>
						<p style="margin: 5px 0 0 0; color: #555; font-size: 12px;">মোট বিক্রয় (টি)</p>
					</div>
					
					<!-- Stats Grid -->
					<div style="border-top: 1px solid #eee; padding-top: 15px;">
						<div class="row" style="margin-bottom: 10px;">
							<div class="col-xs-6">
								<p style="margin: 0; color: #444; font-size: 11px;">
									<i class="fa fa-shopping-cart"></i> অর্ডার
								</p>
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 14px;">
									<?php echo number_format($salesData['total_orders'] ?? 0); ?> টি
								</p>
							</div>
							<div class="col-xs-6">
								<p style="margin: 0; color: #666; font-size: 11px;">
									<i class="fa fa-money"></i> পরিশোধ
								</p>
								<p style="margin: 0; font-weight: bold; color: #43e97b; font-size: 14px;">
									<?php echo number_format($salesData['total_paid'] ?? 0); ?> ৳
								</p>
							</div>
						</div>
						
						<div class="row" style="margin-bottom: 10px;">
							<div class="col-xs-6">
								<p style="margin: 0; color: #666; font-size: 11px;">
									<i class="fa fa-credit-card"></i> বাঁকি
								</p>
								<p style="margin: 0; font-weight: bold; color: #f5576c; font-size: 14px;">
									<?php echo number_format($salesData['total_due'] ?? 0); ?> ৳
								</p>
							</div>
							<div class="col-xs-6">
								<p style="margin: 0; color: #666; font-size: 11px;">
									<i class="fa fa-calculator"></i> সর্বমোট
								</p>
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 14px;">
									<?php echo number_format($salesData['grand_total'] ?? 0); ?> ৳
								</p>
							</div>
						</div>
						
						<?php if($_SESSION['Status'] == '5') { ?>
						<div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; text-align: center;">
							<p style="margin: 0; color: #666; font-size: 11px;">
								<i class="fa fa-line-chart"></i> লাভ
							</p>
							<p style="margin: 5px 0 0 0; font-weight: bold; color: <?php echo $report['color']; ?>; font-size: 18px;">
								<?php echo number_format($profit); ?> ৳
							</p>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		
		<?php } ?>
	</div>
</div>


<!-- Goals & Targets Section -->
<div class="clearfix" style="margin-bottom: 40px;">
	<h2 style="text-align: center; margin-bottom: 30px; font-size: 28px; color: #333;">🎯 Monthly Sales Goal</h2>
	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-body">
					<?php 
						// Set monthly target (you can make this dynamic from settings)
						$monthlyTarget = 100000; // Example: 1 lakh taka
						$currentMonthSales = $gtotalMonth ?? 0;
						$achievementPercent = $monthlyTarget > 0 ? ($currentMonthSales / $monthlyTarget) * 100 : 0;
						
						// Determine progress bar color
						if($achievementPercent >= 100) {
							$progressColor = '#43e97b'; // Green
						} elseif($achievementPercent >= 75) {
							$progressColor = '#feca57'; // Yellow
						} else {
							$progressColor = '#f5576c'; // Red
						}
					?>
					<h4 style="margin-bottom: 20px;">
						<span class="pull-left">এই মাসের লক্ষ্য: <?php echo number_format($monthlyTarget); ?> ৳</span>
						<span class="pull-right">অর্জিত: <?php echo number_format($currentMonthSales); ?> ৳ (<?php echo number_format($achievementPercent, 1); ?>%)</span>
						<div class="clearfix"></div>
					</h4>
					<div class="progress" style="height: 30px; margin-bottom: 10px;">
						<div class="progress-bar" role="progressbar" 
							 style="width: <?php echo min($achievementPercent, 100); ?>%; background-color: <?php echo $progressColor; ?>; font-size: 16px; line-height: 30px;">
							<?php echo number_format($achievementPercent, 1); ?>%
						</div>
					</div>
					<p class="text-center" style="color: #444;">
						<?php if($achievementPercent >= 100) { ?>
							<i class="fa fa-check-circle" style="color: #43e97b;"></i> Congratulations! লক্ষ্য অর্জিত হয়েছে! 🎉
						<?php } elseif($achievementPercent >= 75) { ?>
							<i class="fa fa-thumbs-up" style="color: #feca57;"></i> ভালো অগ্রগতি! লক্ষ্যের কাছাকাছি পৌঁছেছেন।
						<?php } else { ?>
							<i class="fa fa-info-circle" style="color: #f5576c;"></i> আরও <?php echo number_format($monthlyTarget - $currentMonthSales); ?> ৳ প্রয়োজন লক্ষ্য অর্জনের জন্য।
						<?php } ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Charts Section -->
<div class="clearfix" style="margin-top: 40px;">
	<h2 style="text-align: center; margin-bottom: 30px; font-size: 28px; color: #333;">📊 Analytics & Charts</h2>
	
	<!-- Row 1: Sales Trend and Profit/Loss -->
	<div class="row" style="margin-bottom: 30px;">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #5548c8 0%, #5e3a87 100%); color: white;">
					<h4><i class="fa fa-line-chart"></i> বিক্রয় Trend (গত 30 দিন)</h4>
				</div>
				<div class="panel-body">
					<canvas id="salesTrendChart" height="100"></canvas>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #27ae60 0%, #16a085 100%); color: white;">
					<h4><i class="fa fa-bar-chart"></i> শ্রেণী অনুযায়ী বিক্রয়</h4>
				</div>
				<div class="panel-body">
					<canvas id="categorySalesChart" height="100"></canvas>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Row 2: Payment Status and Category Sales -->
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #1e88e5 0%, #0d7377 100%); color: white;">
					<h4><i class="fa fa-credit-card"></i> পেমেন্ট স্ট্যাটাস</h4>
				</div>
				<div class="panel-body">
					<canvas id="paymentStatusChart" height="100"></canvas>
				</div>
			</div>
		</div>
			
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #c44569 0%, #d63447 100%); color: white;">
					<h4><i class="fa fa-pie-chart"></i> লাভ-ক্ষতি বিশ্লেষণ</h4>
				</div>
				<div class="panel-body">
					<canvas id="profitLossChart" height="100"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Chart Data Preparation -->
<?php
// Sales Trend Data (Last 30 days)
$salesTrendData = [];
$salesTrendLabels = [];
for ($i = 29; $i >= 0; $i--) {
	$date = date('Y-m-d', strtotime("-$i days"));
	$dateLabel = date('d/m', strtotime("-$i days"));
	$sql = "SELECT SUM(grand_total) as daily_sales FROM orders WHERE order_date = '$date'";
	$result = mysqli_query($connection, $sql);
	$row = mysqli_fetch_assoc($result);
	$salesTrendData[] = $row['daily_sales'] ?? 0;
	$salesTrendLabels[] = $dateLabel;
}

// Profit/Loss Data
$totalRevenueSql = "SELECT SUM(paid) as revenue FROM orders WHERE order_status = 1";
$revenueResult = mysqli_query($connection, $totalRevenueSql);
$revenueRow = mysqli_fetch_assoc($revenueResult);
$chartRevenue = $revenueRow['revenue'] ?? 0;

$totalExpenseSql = "SELECT SUM(total) as expense FROM spend WHERE status = 1";
$expenseResult = mysqli_query($connection, $totalExpenseSql);
$expenseRow = mysqli_fetch_assoc($expenseResult);
$chartExpense = $expenseRow['expense'] ?? 0;

$chartProfit = $chartRevenue - $chartExpense;

// Payment Status Data
$paidSql = "SELECT SUM(paid) as total_paid FROM orders WHERE payment_status = 1";
$paidResult = mysqli_query($connection, $paidSql);
$paidRow = mysqli_fetch_assoc($paidResult);
$totalPaid = $paidRow['total_paid'] ?? 0;

$dueSql = "SELECT SUM(due) as total_due FROM orders WHERE payment_status = 3";
$dueResult = mysqli_query($connection, $dueSql);
$dueRow = mysqli_fetch_assoc($dueResult);
$totalDueChart = $dueRow['total_due'] ?? 0;

// Category Sales Data
$categorySql = "SELECT c.categories_name, SUM(oi.quantity) as total_qty 
				FROM order_item oi 
				INNER JOIN product p ON oi.product_id = p.product_id 
				INNER JOIN categories c ON p.categories_id = c.categories_id 
				GROUP BY c.categories_id 
				ORDER BY total_qty DESC 
				LIMIT 10";
$categoryResult = mysqli_query($connection, $categorySql);
$categoryLabels = [];
$categoryData = [];
while ($row = mysqli_fetch_assoc($categoryResult)) {
	$categoryLabels[] = $row['categories_name'];
	$categoryData[] = $row['total_qty'];
}
?>

<!-- Chart.js Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	
	// Sales Trend Chart
	const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
	new Chart(salesTrendCtx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($salesTrendLabels); ?>,
			datasets: [{
				label: 'দৈনিক বিক্রয় (৳)',
				data: <?php echo json_encode($salesTrendData); ?>,
				borderColor: '#667eea',
				backgroundColor: 'rgba(102, 126, 234, 0.1)',
				borderWidth: 3,
				fill: true,
				tension: 0.4
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { display: true, position: 'top' },
				title: { display: false }
			},
			scales: {
				y: { beginAtZero: true }
			}
		}
	});
	
	// Profit/Loss Chart
	const profitLossCtx = document.getElementById('profitLossChart').getContext('2d');
	new Chart(profitLossCtx, {
		type: 'pie',
		data: {
			labels: ['আয়', 'খরচ', 'লাভ'],
			datasets: [{
				data: [<?php echo $chartRevenue; ?>, <?php echo $chartExpense; ?>, <?php echo $chartProfit; ?>],
				backgroundColor: ['#43e97b', '#f5576c', '#667eea'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { display: true, position: 'bottom' }
			}
		}
	});
	
	// Payment Status Chart
	const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
	new Chart(paymentStatusCtx, {
		type: 'doughnut',
		data: {
			labels: ['পরিশোধিত', 'বাঁকি'],
			datasets: [{
				data: [<?php echo $totalPaid; ?>, <?php echo $totalDueChart; ?>],
				backgroundColor: ['#43e97b', '#f5576c'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { display: true, position: 'bottom' }
			}
		}
	});
	
	// Category Sales Chart
	const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
	new Chart(categorySalesCtx, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($categoryLabels); ?>,
			datasets: [{
				label: 'বিক্রিত পরিমাণ',
				data: <?php echo json_encode($categoryData); ?>,
				backgroundColor: '#4facfe',
				borderColor: '#00f2fe',
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { display: false }
			},
			scales: {
				y: { beginAtZero: true }
			}
		}
	});
});
</script>

<!-- Last Updated Time & Actions -->
<div class="clearfix" style="margin-top: 40px; margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-radius: 5px;">
	<div class="row">
		<div class="col-md-6">
			<p style="margin: 0; color: #444;">
				<i class="fa fa-clock-o"></i> Last Updated: 
				<strong><?php echo date('d/m/Y h:i A'); ?></strong>
			</p>
		</div>
		<div class="col-md-6 text-right">
			<button onclick="window.print()" class="btn btn-primary">
				<i class="fa fa-print"></i> Print Dashboard
			</button>
			<button onclick="location.reload()" class="btn btn-success" style="margin-left: 10px;">
				<i class="fa fa-refresh"></i> Refresh
			</button>
		</div>
	</div>
</div>

<!-- Animated Counter Script -->
<script>
// Animate counters on page load
function animateCounter(element) {
	const target = parseInt(element.innerText.replace(/,/g, ''));
	if(isNaN(target)) return;
	
	const duration = 1500; // 1.5 seconds
	const steps = 60;
	const increment = target / steps;
	let current = 0;
	
	const timer = setInterval(() => {
		current += increment;
		if(current >= target) {
			element.innerText = target.toLocaleString();
			clearInterval(timer);
		} else {
			element.innerText = Math.floor(current).toLocaleString();
		}
	}, duration / steps);
}

// Run animation on load
window.addEventListener('load', function() {
	// Animate all h2 elements in upper-cn cards
	document.querySelectorAll('.upper-cn h2').forEach(function(el) {
		animateCounter(el);
	});
});
</script>

<!-- Enhanced CSS for Better UI -->
<style>
/* Card Hover Effects */
.upper-cn {
	transition: all 0.3s ease;
	cursor: pointer;
}

.upper-cn:hover {
	transform: translateY(-5px);
	box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

/* Panel Hover Effects */
.panel {
	transition: all 0.3s ease;
}

.panel:hover {
	box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Button Hover Effects */
.btn {
	transition: all 0.3s ease;
}

.btn:hover {
	transform: translateY(-2px);
	box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

/* Progress Bar Animation */
.progress-bar {
	transition: width 1.5s ease-in-out;
}

/* Alert Animation */
.alert {
	animation: slideDown 0.5s ease;
}

@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* Print Styles */
@media print {
	.btn, .alert, .pull-right, button {
		display: none !important;
	}
	.upper-cn, .panel {
		box-shadow: none !important;
		page-break-inside: avoid;
	}
}

/* Smooth Scrolling */
html {
	scroll-behavior: smooth;
}

/* Card Number Styling */
.upper-cn h2 {
	font-weight: bold;
	text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

/* Top Performers Styling */
.s-ten-report {
	transition: all 0.3s ease;
	padding: 10px;
	border-radius: 5px;
}

.s-ten-report:hover {
	background-color: #f8f9fa;
	transform: translateX(5px);
}
</style>

<?php require_once 'includes/footer.php'; ?>



        
