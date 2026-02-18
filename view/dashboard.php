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

// Total Due Amount Calculation (Order Wise Sum)
// Logic: Sum( Max(0, OrderDue - OrderReturn) ) based on regular-order-view logic
$totalDueSql = "SELECT SUM(GREATEST(0, o.due - IFNULL(r.ret_amt, 0))) as total_due
                FROM orders o
                LEFT JOIN (
                    SELECT order_id, SUM(return_amount) as ret_amt 
                    FROM order_returns 
                    GROUP BY order_id
                ) r ON o.order_id = r.order_id
                WHERE o.order_status = 1";
$totalDueQuery = $connect->query($totalDueSql);
$totalDueResult = $totalDueQuery->fetch_assoc();
$totalDue = $totalDueResult['total_due'] ?? 0;

// Total Expenses
$expenseSql = "SELECT SUM(total) as total_expense FROM spend WHERE status = 1";
$expenseQuery = $connect->query($expenseSql);
$expenseResult = $expenseQuery->fetch_assoc();
$totalExpense = $expenseResult['total_expense'] ?? 0;

// Today's Revenue
$today = date('Y-m-d');
$todayRevenueSql = "SELECT SUM(pement) as today_revenue FROM pement_details WHERE DATE(date) = '$today'";
$todayRevenueQuery = $connect->query($todayRevenueSql);
$todayRevenueResult = $todayRevenueQuery->fetch_assoc();
$todayRevenue = $todayRevenueResult['today_revenue'] ?? 0;

// Today's Orders Count, Total Amount & Discount
$todayOrdersSql = "SELECT COUNT(*) as order_count, SUM(total_amount) as today_total_amt, SUM(discount) as today_discount, SUM(grand_total) as today_grand FROM orders WHERE DATE(order_date) = '$today' AND order_status = 1";
$todayOrdersQuery = $connect->query($todayOrdersSql);
$todayOrdersResult = $todayOrdersQuery->fetch_assoc();
$countTodayOrder = $todayOrdersResult['order_count'] ?? 0;
$todayTotalAmt = $todayOrdersResult['today_total_amt'] ?? 0; // total_amount column (before discount)
$todayTotalDiscount = $todayOrdersResult['today_discount'] ?? 0; // discount column
$todayTotalSales = $todayOrdersResult['today_grand'] ?? 0; // grand_total for display card

// Today's Return Amount
$todayReturnSql = "SELECT SUM(return_amount) as today_return FROM order_returns WHERE DATE(return_date) = '$today'";
$todayReturnQuery = $connect->query($todayReturnSql);
$todayReturnResult = $todayReturnQuery->fetch_assoc();
$todayReturnAmount = $todayReturnResult['today_return'] ?? 0;

// Today's Due Amount
$todayDueSql = "SELECT SUM(due) as today_due FROM orders WHERE DATE(order_date) = '$today' AND order_status = 1";
$todayDueQuery = $connect->query($todayDueSql);
$todayDueResult = $todayDueQuery->fetch_assoc();
$todayDue = ($todayDueResult['today_due'] ?? 0);

// Today's Profit Calculation
// Formula: (Today's Sales Grand Total - Today's Sales Cost) - Today's Return Margin

// 1. Total Cost of items sold today
$todaySalesCostSql = "SELECT SUM(brate) as total_cost FROM order_item WHERE DATE(order_date) = '$today' AND order_item_status = 1";
$todaySalesCostQuery = $connect->query($todaySalesCostSql);
$todaySalesCostResult = $todaySalesCostQuery->fetch_assoc();
$todaySalesCost = $todaySalesCostResult['total_cost'] ?? 0;

// 2. Today's Return Margin (Profit lost from returns today)
// Return Margin = return_amount - (original_cost_per_unit * return_quantity)
$todayReturnMarginSql = "SELECT SUM(or_ret.return_amount - (oi.brate / oi.quantity) * or_ret.return_quantity) as return_margin 
                        FROM order_returns or_ret 
                        JOIN order_item oi ON or_ret.order_item_id = oi.order_item_id 
                        JOIN orders o ON or_ret.order_id = o.order_id
                        WHERE DATE(o.order_date) = '$today' AND o.order_status = 1";
$todayReturnMarginQuery = $connect->query($todayReturnMarginSql);
$todayReturnMarginResult = $todayReturnMarginQuery->fetch_assoc();
$todayReturnMargin = $todayReturnMarginResult['return_margin'] ?? 0;

// Calculate Profit: {(total_amount - todaySalesCost) - todayTotalDiscount} - todayReturnMargin
$todayProfit = (($todayTotalAmt - $todaySalesCost) - $todayTotalDiscount) - $todayReturnMargin;

// Today's Refund Calculation
// Logic: Calculate refund for orders that had a RETURN today.
// Formula: Paid - (GrandTotal - TotalReturns) for orders with returns today.
// Today's Refund Calculation
// Logic: Calculate refund for orders that had a RETURN today (using DB Server Date).
// Formula used in single-report.php: Refund = TotalPayment (from pement_details) - (GrandTotal - TotalReturns)
$todayRefundSql = "SELECT SUM(
                        GREATEST(0, 
                            IFNULL(pd.real_paid, 0) - (orders.grand_total - IFNULL(r.ret_amt, 0))
                        )
                   ) as today_refund
                   FROM orders
                   INNER JOIN (
                       SELECT DISTINCT order_id 
                       FROM order_returns 
                       WHERE DATE(return_date) = CURRENT_DATE()
                   ) today_ret ON orders.order_id = today_ret.order_id
                   LEFT JOIN (
                       SELECT order_id, SUM(return_amount) as ret_amt 
                       FROM order_returns 
                       GROUP BY order_id
                   ) r ON orders.order_id = r.order_id
                   LEFT JOIN (
                       SELECT order_id, SUM(pement) as real_paid 
                       FROM pement_details 
                       GROUP BY order_id
                   ) pd ON orders.order_id = pd.order_id
                   WHERE orders.order_status = 1";
$todayRefundQuery = $connect->query($todayRefundSql);
if(!$todayRefundQuery) {
    // echo "SQL Error: " . $connect->error;
    $todayRefund = 0;
} else {
    $todayRefundResult = $todayRefundQuery->fetch_assoc();
    $todayRefund = $todayRefundResult['today_refund'] ?? 0;
}

// Today's Collection (Balance) - Total payments received today
$todayCollectionSql = "SELECT SUM(pement) as today_collection FROM pement_details WHERE DATE(date) = '$today'";
$todayCollectionQuery = $connect->query($todayCollectionSql);
$todayCollectionResult = $todayCollectionQuery->fetch_assoc();
$todayCollection = $todayCollectionResult['today_collection'] ?? 0;
// Today's Expense
$todayExpenseSql = "SELECT SUM(total) as today_expense FROM spend WHERE spend_date = '$today' AND status = 1";
$todayExpenseQuery = $connect->query($todayExpenseSql);
$todayExpenseResult = $todayExpenseQuery->fetch_assoc();
$todayExpense = $todayExpenseResult['today_expense'] ?? 0;

// Store important values before closing connection
$safeTotalDue = $totalDue;
$safeTotalPaid = 0; // Will be calculated later with $connection

$connect->close();
?>

<!-- Quick Actions Buttons -->
<div class="clearfix" style="margin-top: 20px; margin-bottom: 20px; padding-left: 10px; padding-right: 10px;">
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
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn1 clearfix" style="background: linear-gradient(135deg, #FF512F 0%, #DD2476 100%);">
			<h4 class="">বিক্রয়</h4>
			<h2 class=""><?php echo $countOrder; ?></h2>
			<p class="ttn">সর্বমোট বিক্রয়</p>
			<i class="fa fa-line-chart cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn2 clearfix" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
			<h4 class="">পণ্য</h4>
			<h2 class=""><?php echo $countProduct; ?></h2>
			<p class="ttn">সর্বমোট পণ্য</p>
			<i class="fa fa-bank cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn3 clearfix" style="background: linear-gradient(135deg, #8E2DE2 0%, #4A00E0 100%);">
			<h4 class="">পণ্যের শ্রেণী</h4>
			<h2 class=""><?php echo $countcategory; ?></h2>
			<p class="ttn">সর্বমোট পণ্যের শ্রেণী</p>
			<i class="fa fa-money cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn4 clearfix" style="background: linear-gradient(135deg, #2C3E50 0%, #4CA1AF 100%);">
			<h4 class="">ব্র্যান্ড</h4>
			<h2 class=""><?php echo $countbrand; ?></h2>
			<p class="ttn">সর্বমোট ব্র্যান্ড</p>
			<i class="fa fa-money cni"></i>
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
		<div class="upper-cn upper-cn5 clearfix" style="background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);">
			<h4 class="">আজকের ব্যালেন্স</h4>
			<h2 class=""><?php echo number_format($todayCollection); ?> ৳</h2>
			<p class="ttn">আজকের কালেকশন এমাউন্ট</p>
			<i class="fa fa-money cni"></i>
		</div>
	</div>
</div>

<!-- New Statistics Cards Row 2 -->
<div class="cn-body clearfix" style="margin-top: 20px;">
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn11 clearfix" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);">
			<h4 class="">আজকের বিক্রয়</h4>
			<h2 class=""><?php echo number_format($todayTotalSales); ?> ৳</h2>
			<p class="ttn">আজকের মোট বিক্রয় মূল্য</p>
			<i class="fa fa-shopping-basket cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn12 clearfix" style="background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);">
			<h4 class="">আজকের ফেরত</h4>
			<h2 class=""><?php echo number_format($todayReturnAmount); ?> ৳</h2>
			<p class="ttn">আজকের ফেরতকৃত পণ্যের মূল্য</p>
			<i class="fa fa-undo cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn7 clearfix" style="background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%);">
			<h4 class="">আজকের বাঁকি</h4>
			<h2 class=""><?php echo number_format($todayDue); ?> ৳</h2>
			<p class="ttn">আজকের মোট বাঁকি</p>
			<i class="fa fa-credit-card cni"></i>
		</div>
	</div>

	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn9 clearfix" style="background: linear-gradient(135deg, #16a085 0%, #c44569 100%);">
			<h4 class="">আজকের পরিশোধ</h4>
			<h2 class=""><?php echo number_format($todayRevenue); ?> ৳</h2>
			<p class="ttn">আজকের মোট পরিশোধ</p>
			<i class="fa fa-calendar-check-o cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn10 clearfix" style="background: linear-gradient(135deg, #27ae60 0%, #16a085 100%);">
			<h4 class="">আজকের লাভ</h4>
			<h2 class=""><?php echo number_format($todayProfit); ?> ৳</h2>
			<p class="ttn">আজকের মোট লাভ</p>
			<i class="fa fa-line-chart cni"></i>
		</div>
	</div>
	<div class="col-md-2 clearfix">
		<div class="upper-cn upper-cn8 clearfix" style="background: linear-gradient(135deg, #4c2089 0%, #6e48aa 100%);">
			<h4 class="">আজকের খরচ</h4>
			<h2 class=""><?php echo number_format($todayExpense); ?> ৳</h2>
			<p class="ttn">আজকের মোট খরচ</p>
			<i class="fa fa-minus-square cni"></i>
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
			<div class="panel panel-default" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
				<div class="panel-heading" style="background: linear-gradient(135deg, #5548c8 0%, #5e3a87 100%); color: white; border: none;">
					<h4 style="margin: 0; font-weight: bold;">
						<i class="fa fa-cubes"></i> সর্বশেষ স্টক
					</h4>
				</div>
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
					<div class="list-group-item" style="border-left: 4px solid #5548c8; margin-bottom: 0; transition: all 0.3s ease; padding: 12px 15px;">
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
		
		<!-- Recent Orders Section -->
		<div class="col-md-4">
			<div class="panel panel-default" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
				<div class="panel-heading" style="background: linear-gradient(135deg, #c44569 0%, #d63447 100%); color: white; border: none;">
					<h4 style="margin: 0; font-weight: bold;">
						<i class="fa fa-shopping-cart"></i> সর্বশেষ অর্ডার
					</h4>
				</div>
					<?php  
						$orderSql = "SELECT order_id, order_date, client_name, grand_total, sr_id 
									 FROM orders 
									 WHERE order_status = 1 
									 ORDER BY order_id DESC 
									 LIMIT 10";
						$orderQuery = mysqli_query($connection, $orderSql);
					?>
					<?php while($order = mysqli_fetch_array($orderQuery)){ 
						$orderDate = date_create($order['order_date']);
						$formattedDate = date_format($orderDate, "d/m/Y");
						
						// Differentiate between Quick and Regular Order view links
						$viewLink = ($order['sr_id'] == 0) ? "quick-order-view=" : "regular-order-view=";
						$orderTypeText = ($order['sr_id'] == 0) ? "দ্রুত অর্ডার" : "অর্ডার";
					?>
					<div class="list-group-item" style="border-left: 4px solid #d63447; margin-bottom: 0; transition: all 0.3s ease; padding: 12px 15px;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-8">
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 14px;">
									<?php echo $order['client_name']; ?>
								</p>
								<p style="margin: 2px 0 0 0; color: #555; font-size: 11px;">
									<i class="fa fa-calendar"></i> <?php echo $formattedDate; ?> 
									<span style="margin-left: 10px;">
										<i class="fa fa-hashtag"></i> ID: <?php echo $order['order_id']; ?>
									</span>
								</p>
							</div>
							<div class="col-xs-4 text-right">
								<span class="label" style="background: #d63447; font-size: 12px; padding: 5px 10px; display: inline-block;">
									<?php echo number_format($order['grand_total']); ?> ৳
								</span>
								<a href="<?php echo $viewLink . $order['order_id']; ?>" target="_blank" style="color: #d63447; margin-left: 8px; font-size: 16px;" title="<?php echo $orderTypeText; ?> দেখুন">
									<i class="fa fa-eye"></i>
								</a>
							</div>
						</div>
					</div>
					<?php } ?>
			</div>
		</div>
		
		<!-- Recent Due Section -->
		<div class="col-md-4">
			<div class="panel panel-default" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
				<div class="panel-heading" style="background: linear-gradient(135deg, #d35400 0%, #e67e22 100%); color: white; border: none;">
					<h4 style="margin: 0; font-weight: bold;">
						<i class="fa fa-exclamation-circle"></i> সর্বশেষ বাঁকি
					</h4>
				</div>
					<?php  
						$dueSql = "SELECT order_id, order_date, client_name, due, sr_id 
								   FROM orders 
								   WHERE due > 0 AND order_status = 1 
								   ORDER BY order_id DESC 
								   LIMIT 10";
						$dueQuery = mysqli_query($connection, $dueSql);
					?>
					<?php while($due = mysqli_fetch_array($dueQuery)){ 
						$dueDate = date_create($due['order_date']);
						$formattedDate = date_format($dueDate, "d/m/Y");
						
						// Differentiate between Quick and Regular Order view links
						$viewLink = ($due['sr_id'] == 0) ? "quick-order-view=" : "regular-order-view=";
						$orderTypeText = ($due['sr_id'] == 0) ? "দ্রুত অর্ডার" : "অর্ডার";
					?>
					<div class="list-group-item" style="border-left: 4px solid #e67e22; margin-bottom: 0; transition: all 0.3s ease; padding: 12px 15px;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-8">
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 14px;">
									<?php echo $due['client_name']; ?>
								</p>
								<p style="margin: 2px 0 0 0; color: #555; font-size: 11px;">
									<i class="fa fa-calendar"></i> <?php echo $formattedDate; ?> 
									<span style="margin-left: 10px;">
										<i class="fa fa-hashtag"></i> ID: <?php echo $due['order_id']; ?>
									</span>
								</p>
							</div>
							<div class="col-xs-4 text-right">
								<span class="label" style="background: #e67e22; font-size: 12px; padding: 5px 10px; display: inline-block;">
									<?php echo number_format($due['due']); ?> ৳
								</span>
								<a href="<?php echo $viewLink . $due['order_id']; ?>" target="_blank" style="color: #e67e22; margin-left: 8px; font-size: 16px;" title="<?php echo $orderTypeText; ?> দেখুন">
									<i class="fa fa-eye"></i>
								</a>
							</div>
						</div>
					</div>
					<?php } ?>
			</div>
		</div>
	</div>
</div>


<!-- Top Performers Section -->
<div class="last-ten clearfix" style="margin-top: 30px;">
	<!-- Top Products -->
	<div class="col-md-4">
		<div class="panel panel-default" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
			<div class="panel-heading" style="background: linear-gradient(135deg, #5548c8 0%, #5e3a87 100%); color: white; border: none; padding: 12px 15px;">
				<h4 style="margin: 0; font-weight: bold;">
					🏆 সেরা ১০ টি পণ্য
				</h4>
			</div>
				<?php  
					$topProductsSql = "SELECT p.product_name, b.brand_name, SUM(oi.quantity) as total_sold 
									   FROM order_item oi 
									   INNER JOIN product p ON oi.product_id = p.product_id 
									   INNER JOIN brands b ON p.brand_id = b.brand_id 
									   GROUP BY oi.product_id 
									   ORDER BY total_sold DESC 
									   LIMIT 10";
					$topProductsQuery = mysqli_query($connection, $topProductsSql);
					$rank = 1;
				?>
				<?php while($row = mysqli_fetch_array($topProductsQuery)){ ?>
					<div class="list-group-item" style="border-left: 4px solid #5548c8; margin-bottom: 0; transition: all 0.3s ease; padding: 12px 15px;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-9">
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 13px;">
									<?php echo $row['brand_name']; ?>
								</p>
								<p style="margin: 0; color: #555; font-size: 11px;">
									<?php echo $row['product_name']; ?>
								</p>
							</div>
							<div class="col-xs-3 text-right">
								<span class="label" style="background: #5548c8; font-size: 12px; padding: 5px 10px;">
									<?php echo $row['total_sold']; ?> টি
								</span>
							</div>
						</div>
					</div>
				<?php $rank++; } ?>
		</div>
	</div>
	
	<!-- Top Customers -->
	<div class="col-md-4">
		<div class="panel panel-default" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
			<div class="panel-heading" style="background: linear-gradient(135deg, #c44569 0%, #d63447 100%); color: white; border: none; padding: 12px 15px;">
				<h4 style="margin: 0; font-weight: bold;">
					👥 সেরা ১০ জন কাস্টমার
				</h4>
			</div>
				<?php  
					$topCustomersSql = "SELECT c.client_name, c.client_contact, c.sr_id, SUM(c.grand_total) as total_purchase 
										FROM orders c 
										WHERE c.order_status = 1 AND c.sr_id != 0 
										GROUP BY c.sr_id 
										ORDER BY total_purchase DESC 
										LIMIT 10";
					$topCustomersQuery = mysqli_query($connection, $topCustomersSql);
					$rank = 1;
				?>
				<?php while($row = mysqli_fetch_array($topCustomersQuery)){ ?>
					<div class="list-group-item" style="border-left: 4px solid #d63447; margin-bottom: 0; transition: all 0.3s ease; padding: 12px 15px;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-8">
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 13px;">
									<?php echo $row['client_name']; ?>
								</p>
								<p style="margin: 0; color: #555; font-size: 11px;">
									<?php echo $row['client_contact']; ?>
								</p>
							</div>
							<div class="col-xs-4 text-right">
								<span class="label" style="background: #d63447; font-size: 12px; padding: 5px 10px; display: inline-block;">
									<?php echo number_format($row['total_purchase']); ?> ৳
								</span>
								<a href="customer-report=<?php echo $row['sr_id']; ?>" target="_blank" style="color: #d63447; margin-left: 8px; font-size: 16px;" title="ক্রেতা রিপোর্ট দেখুন">
									<i class="fa fa-eye"></i>
								</a>
							</div>
						</div>
					</div>
				<?php $rank++; } ?>
		</div>
	</div>
	
	<!-- Top Brands -->
	<div class="col-md-4">
		<div class="panel panel-default" style="box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
			<div class="panel-heading" style="background: linear-gradient(135deg, #1e88e5 0%, #0d7377 100%); color: white; border: none; padding: 12px 15px;">
				<h4 style="margin: 0; font-weight: bold;">
					🔥 সেরা ১০টি ব্র্যান্ড
				</h4>
			</div>
				<?php  
					$topBrandsSql = "SELECT b.brand_name, SUM(oi.quantity) as total_sold 
									 FROM order_item oi 
									 INNER JOIN product p ON oi.product_id = p.product_id 
									 INNER JOIN brands b ON p.brand_id = b.brand_id 
									 GROUP BY b.brand_id 
									 ORDER BY total_sold DESC 
									 LIMIT 10";
					$topBrandsQuery = mysqli_query($connection, $topBrandsSql);
					$rank = 1;
				?>
				<?php while($row = mysqli_fetch_array($topBrandsQuery)){ ?>
					<div class="list-group-item" style="border-left: 4px solid #1e88e5; margin-bottom: 0; transition: all 0.3s ease; padding: 12px 15px;">
						<div class="row" style="align-items: center;">
							<div class="col-xs-9">
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 14px;">
									<?php echo $row['brand_name']; ?>
								</p>
							</div>
							<div class="col-xs-3 text-right">
								<span class="label" style="background: #1e88e5; font-size: 12px; padding: 5px 10px;">
									<?php echo $row['total_sold']; ?> টি
								</span>
							</div>
						</div>
					</div>
				<?php $rank++; } ?>
		</div>
	</div>
</div>

<!-- Sales Reports Section - Modern Design -->
<div class="clearfix" style="margin-top: 40px; margin-bottom: 40px;">
	<h2 style="text-align: center; margin-bottom: 30px; font-size: 28px; color: #333;">
		<i class="fa fa-bar-chart"></i> রিপোর্ট
	</h2>
	
	<div class="row">
		<?php
		$bn_months = array(1=>'জানুয়ারি', 2=>'ফেব্রুয়ারি', 3=>'মার্চ', 4=>'এপ্রিল', 5=>'মে', 6=>'জুন', 7=>'জুলাই', 8=>'আগস্ট', 9=>'সেপ্টেম্বর', 10=>'অক্টোবর', 11=>'নভেম্বর', 12=>'ডিসেম্বর');
		$current_month_bn = $bn_months[date('n')];
		
		// Calculate Last Month Name
		$prev_month_index = date('n') - 1;
		if ($prev_month_index < 1) $prev_month_index = 12;
		$last_month_bn = $bn_months[$prev_month_index];
		
		// Year Logic
		$search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
		$replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
		
		$current_year_bn = str_replace($search_array, $replace_array, date('Y')) . ' সাল';
		$last_year_bn = str_replace($search_array, $replace_array, (date('Y') - 1)) . ' সাল';
		
		// Day Names Logic
		$bn_days = array('Saturday'=>'শনিবার', 'Sunday'=>'রবিবার', 'Monday'=>'সোমবার', 'Tuesday'=>'মঙ্গলবার', 'Wednesday'=>'বুধবার', 'Thursday'=>'বৃহস্পতিবার', 'Friday'=>'শুক্রবার');
		$today_bn = $bn_days[date('l')];
		$yesterday_bn = $bn_days[date('l', strtotime('-1 days'))];
		// Define all reports with their configurations
		$reports = [
			[
				'title' => $today_bn,
				'icon' => 'fa-calendar-check-o',
				'color' => '#5548c8',
				'gradient' => 'linear-gradient(135deg, #5548c8 0%, #5e3a87 100%)',
				'date_condition' => "DATE(o.order_date) = '" . date('Y-m-d') . "'",
				'date_condition_oi' => "DATE(oi.order_date) = '" . date('Y-m-d') . "'"
			],
			[
				'title' => $yesterday_bn,
				'icon' => 'fa-calendar-check-o',
				'color' => '#d63447',
				'gradient' => 'linear-gradient(135deg, #c44569 0%, #d63447 100%)',
				'date_condition' => "DATE(o.order_date) = '" . date('Y-m-d', strtotime('-1 days')) . "'",
				'date_condition_oi' => "DATE(oi.order_date) = '" . date('Y-m-d', strtotime('-1 days')) . "'"
			],
			[
				'title' => 'সর্বশেষ ৭ দিন',
				'icon' => 'fa-calendar-check-o',
				'color' => '#e67e22',
				'gradient' => 'linear-gradient(135deg, #d35400 0%, #e67e22 100%)',
				'date_condition' => "o.order_date > (CURDATE()) - INTERVAL 7 DAY",
				'date_condition_oi' => "oi.order_date > (CURDATE()) - INTERVAL 7 DAY"
			],
			[
				'title' => 'সর্বশেষ ৩০ দিন',
				'icon' => 'fa-calendar-check-o',
				'color' => '#c0392b',
				'gradient' => 'linear-gradient(135deg, #c0392b 0%, #d35400 100%)',
				'date_condition' => "o.order_date > (CURDATE()) - INTERVAL 30 DAY",
				'date_condition_oi' => "oi.order_date > (CURDATE()) - INTERVAL 30 DAY"
			],
			[
				'title' => $current_month_bn,
				'icon' => 'fa-calendar-check-o',
				'color' => '#27ae60',
				'gradient' => 'linear-gradient(135deg, #27ae60 0%, #16a085 100%)',
				'date_condition' => "o.order_date BETWEEN '" . date('Y-m-01') . "' AND '" . date('Y-m-t') . "'",
				'date_condition_oi' => "oi.order_date BETWEEN '" . date('Y-m-01') . "' AND '" . date('Y-m-t') . "'"
			],
			[
				'title' => $last_month_bn,
				'icon' => 'fa-calendar-check-o',
				'color' => '#6c5ce7',
				'gradient' => 'linear-gradient(135deg, #6c5ce7 0%, #5f3dc4 100%)',
				'date_condition' => "YEAR(o.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(o.order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)",
				'date_condition_oi' => "YEAR(oi.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(oi.order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)"
			],
			[
				'title' => $current_year_bn,
				'icon' => 'fa-calendar-check-o',
				'color' => '#2980b9',
				'gradient' => 'linear-gradient(135deg, #2980b9 0%, #3498db 100%)',
				'date_condition' => "YEAR(o.order_date) = YEAR(CURDATE())",
				'date_condition_oi' => "YEAR(oi.order_date) = YEAR(CURDATE())"
			],
			[
				'title' => $last_year_bn,
				'icon' => 'fa-calendar-check-o',
				'color' => '#e84393',
				'gradient' => 'linear-gradient(135deg, #e84393 0%, #c0392b 100%)',
				'date_condition' => "YEAR(o.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)",
				'date_condition_oi' => "YEAR(oi.order_date) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)"
			]
		];
		
		foreach($reports as $report) {
			// Get sales aggregates safely (without duplicates from items join)
			$ordersAggSql = "SELECT 
						 COUNT(order_id) as total_orders,
						 SUM(paid) as total_paid,
						 SUM(total_amount) as total_amount,
						 SUM(discount) as total_discount,
						 SUM(grand_total) as grand_total,
						 SUM(due) as total_due
						 FROM orders o
						 WHERE " . $report['date_condition'] . " AND order_status = 1";
			$ordersAggResult = mysqli_query($connection, $ordersAggSql);
			$salesData = mysqli_fetch_assoc($ordersAggResult);

			// Get Actual Collections (Cash In) for the period
			// Replace o.order_date with date for pement_details table
			$payCond = str_replace(['o.order_date', 'o.', 'orders.'], ['date', 'pd.', 'pd.'], $report['date_condition']);
			// Remove table aliases if they don't match or aren't needed, but pement_details usually has `date` column.
			// Simple replacement: o.order_date -> date
			$payCond = str_replace('o.order_date', 'date', $report['date_condition']);
			
			$paySql = "SELECT SUM(pement) as total_collection FROM pement_details WHERE $payCond";
			$payResult = mysqli_query($connection, $paySql);
			$payData = mysqli_fetch_assoc($payResult);
			$salesData['total_paid'] = $payData['total_collection'] ?? 0;
			
			// Get item quantities and cost
			$itemsSql = "SELECT SUM(quantity) as total_qty, SUM(brate) as total_cost 
						 FROM order_item oi 
						 WHERE " . $report['date_condition_oi'] . " AND order_item_status = 1";
			$itemsResult = mysqli_query($connection, $itemsSql);
			$itemsData = mysqli_fetch_assoc($itemsResult);
			$total_qty = $itemsData['total_qty'] ?? 0;
			$total_cost = $itemsData['total_cost'] ?? 0;

			// Get returns and return margin based on RETURN DATE (Activity based)
			$returnCond = str_replace('o.order_date', 'or_ret.return_date', $report['date_condition']);
			$reportReturnSql = "SELECT SUM(or_ret.return_amount) as total_return,
							   SUM(or_ret.return_amount - (oi.brate / oi.quantity) * or_ret.return_quantity) as return_margin 
							   FROM order_returns or_ret
                               JOIN order_item oi ON or_ret.order_item_id = oi.order_item_id
							   WHERE $returnCond";
			$reportReturnResult = mysqli_query($connection, $reportReturnSql);
			$reportReturnData = mysqli_fetch_assoc($reportReturnResult);
			$reportReturnAmt = $reportReturnData['total_return'] ?? 0;
			$reportReturnMargin = $reportReturnData['return_margin'] ?? 0;
			


			// Calculate Due for this Period (Order Wise Sum)
			// Uses: GREATEST(0, grand_total - returns - paid)
			// Note: orders.paid has the payment at order time. Custom payments (order_id=0 in pement_details) are not order-specific.
			$dueCond = str_replace(['o.', 'orders.'], ['o.', 'o.'], $report['date_condition']); // Normalize suffix
			
			$periodDueSql = "SELECT SUM(GREATEST(0, o.grand_total - IFNULL(r.ret_amt, 0) - o.paid)) as period_due
							 FROM orders o
							 LEFT JOIN (
								 SELECT order_id, SUM(return_amount) as ret_amt 
								 FROM order_returns 
								 GROUP BY order_id
							 ) r ON o.order_id = r.order_id
							 WHERE $dueCond AND o.order_status = 1";
			
			$periodDueQuery = mysqli_query($connection, $periodDueSql);
			if($periodDueQuery) {
				$periodDueResult = mysqli_fetch_assoc($periodDueQuery);
				$salesData['total_due'] = $periodDueResult['period_due'] ?? 0;
			} else {
				// Fallback: use orders.due directly
				$salesData['total_due'] = $salesData['total_due'] ?? 0;
			}

			// Get expenses for the period
			$expCond = str_replace(['o.order_date', 'o.'], ['spend_date', ''], $report['date_condition']);
			$expSql = "SELECT SUM(total) as period_expense FROM spend WHERE $expCond AND status = 1";
			$expResult = mysqli_query($connection, $expSql);
			$expData = mysqli_fetch_assoc($expResult);
			$periodExpense = $expData['period_expense'] ?? 0;
			
			// Get PROFIT Return Margin based on ORDER DATE (Financial Attribution)
			// This ensures profit is deducted from the day the order was made, not when the return happened
			$profitReturnSql = "SELECT SUM(or_ret.return_amount - (oi.brate / oi.quantity) * or_ret.return_quantity) as return_margin 
							   FROM order_returns or_ret
                               JOIN order_item oi ON or_ret.order_item_id = oi.order_item_id
                               JOIN orders o ON or_ret.order_id = o.order_id
							   WHERE " . $report['date_condition'] . " AND o.order_status = 1";
			$profitReturnResult = mysqli_query($connection, $profitReturnSql);
			$profitReturnData = mysqli_fetch_assoc($profitReturnResult);
			$profitReturnMargin = $profitReturnData['return_margin'] ?? 0;

			// Get profit using the specific formula
			$profit = 0;
			if($_SESSION['Status'] == '5') {
				// Formula: {(total_amount - cost) - discount} - OrderBasedReturnMargin
				$profit = (($salesData['total_amount'] ?? 0) - $total_cost - ($salesData['total_discount'] ?? 0)) - $profitReturnMargin;
			}
		?>
		
		<div class="col-md-3" style="margin-bottom: 20px; padding: 0 10px;">
			<div class="panel panel-default" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 8px; overflow: hidden; margin-bottom: 0;">
				<div class="panel-heading" style="background: <?php echo $report['gradient']; ?>; color: white; border: none; padding: 10px 15px;">
					<h5 style="margin: 0; font-weight: bold; font-size: 16px;">
						<i class="fa <?php echo $report['icon']; ?>"></i> <?php echo $report['title']; ?>
					</h5>
				</div>
				<div class="panel-body" style="padding: 15px;">
					<!-- Main Orders -->
					<div style="text-align: center; margin-bottom: 12px;">
						<h3 style="margin: 0; font-size: 36px; font-weight: bold; color: <?php echo $report['color']; ?>;">
							<?php echo number_format($salesData['total_orders'] ?? 0); ?>
						</h3>
						<p style="margin: 0; color: #777; font-size: 13px; font-weight: 500;">অর্ডার</p>
					</div>
					
					<!-- Stats Grid -->
					<div style="border-top: 1px solid #f0f0f0; padding-top: 10px;">
						<div class="row" style="margin-bottom: 8px;">
							<div class="col-xs-6">
								<p style="margin: 0; color: #888; font-size: 12px;">পরিশোধ</p>
								<p style="margin: 0; font-weight: bold; color: #27ae60; font-size: 15px;">
									<?php echo number_format($salesData['total_paid'] ?? 0); ?> ৳
								</p>
							</div>
							<div class="col-xs-6 text-right">
								<p style="margin: 0; color: #888; font-size: 12px;">বাঁকি</p>
								<p style="margin: 0; font-weight: bold; color: #d63447; font-size: 15px;">
									<?php echo number_format($salesData['total_due'] ?? 0); ?> ৳
								</p>
							</div>
						</div>
						
						<div class="row" style="margin-bottom: 8px;">
							<div class="col-xs-6">
								<p style="margin: 0; color: #888; font-size: 12px;"> মোট বিক্রয়</p>
								<p style="margin: 0; font-weight: bold; color: #333; font-size: 15px;">
									<?php echo number_format($salesData['grand_total'] ?? 0); ?> ৳
								</p>
							</div>
							<div class="col-xs-6 text-right">
								<p style="margin: 0; color: #888; font-size: 12px;">ফেরতকৃত পন্যের মূল্য</p>
								<p style="margin: 0; font-weight: bold; color: #777; font-size: 15px;">
									<?php echo number_format($reportReturnAmt); ?> ৳
								</p>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<p style="margin: 0; color: #888; font-size: 12px;">খরচ</p>
								<p style="margin: 0; font-weight: bold; color: #e67e22; font-size: 15px;">
									<?php echo number_format($periodExpense); ?> ৳
								</p>
							</div>
							
							<?php if($_SESSION['Status'] == '5') { ?>
							<div class="col-xs-6 text-right">
								<p style="margin: 0; color: #888; font-size: 12px;">লাভ</p>
								<p style="margin: 0; font-weight: bold; color: #5548c8; font-size: 15px;">
									<?php echo number_format($profit); ?> ৳
								</p>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php } ?>
	</div>
</div>




<!-- Charts Section -->
<div class="clearfix" style="margin-top: 40px;">
	<h2 style="text-align: center; margin-bottom: 30px; font-size: 28px; color: #333;">📊 Analytics & Charts</h2>
	
	<!-- Row 1: Sales, Return, and Due Trends -->
	<div class="row" style="margin-bottom: 30px;">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #5548c8 0%, #5e3a87 100%); color: white;">
					<h4><i class="fa fa-line-chart"></i> গত ১০ দিনের বিক্রয় চিত্র</h4>
				</div>
					<canvas id="salesTrendChart" height="180"></canvas>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%); color: white;">
					<h4><i class="fa fa-undo"></i> গত ১০ দিনের ফেরতের চিত্র</h4>
				</div>
					<canvas id="returnTrendChart" height="180"></canvas>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #d35400 0%, #e67e22 100%); color: white;">
					<h4><i class="fa fa-credit-card"></i> গত ১০ দিনের বাঁকির চিত্র</h4>
				</div>
					<canvas id="dueTrendChart" height="180"></canvas>
			</div>
		</div>
	</div>
	
	<!-- Row 2: Category Sales, Profit/Loss, and Payment Status -->
	<div class="row">
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #27ae60 0%, #16a085 100%); color: white;">
					<h4><i class="fa fa-bar-chart"></i> শ্রেণী অনুযায়ী বিক্রয়</h4>
				</div>
					<canvas id="categorySalesChart" height="200"></canvas>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%); color: white;">
					<h4><i class="fa fa-bar-chart"></i> পণ্য অনুযায়ী বিক্রয়</h4>
				</div>
					<canvas id="productSalesChart" height="200"></canvas>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #c44569 0%, #d63447 100%); color: white;">
					<h4><i class="fa fa-pie-chart"></i> বিক্রয়-ফেরত-লাভ বিশ্লেষণ</h4>
				</div>
				<div class="panel-body">
					<canvas id="profitLossChart" height="200"></canvas>
					<!-- Visual Summary will be added by JavaScript after chart data is loaded -->
					<div id="profitLossSummary" style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 5px;">
						<!-- Summary will be populated by JavaScript -->
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading" style="background: linear-gradient(135deg, #1e88e5 0%, #0d7377 100%); color: white;">
					<h4><i class="fa fa-credit-card"></i> পেমেন্ট স্ট্যাটাস</h4>
				</div>
				<div class="panel-body">
					<canvas id="paymentStatusChart" height="200"></canvas>
					<!-- Visual Summary will be added by JavaScript after chart data is loaded -->
					<div id="paymentStatusSummary" style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 5px;">
						<!-- Summary will be populated by JavaScript -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Chart Data Preparation -->
<?php
// Sales, Return & Due Trend Data (Last 10 days)
$salesTrendData = [];
$returnTrendData = [];
$dueTrendData = [];
$salesTrendLabels = [];
for ($i = 9; $i >= 0; $i--) {
	$date = date('Y-m-d', strtotime("-$i days"));
	$dateLabel = date('d/m', strtotime("-$i days"));
	
	// Daily Sales
	$sqlSales = "SELECT SUM(grand_total) as daily_sales FROM orders WHERE order_date = '$date' AND order_status = 1";
	$resultSales = mysqli_query($connection, $sqlSales);
	$rowSales = mysqli_fetch_assoc($resultSales);
	$salesTrendData[] = $rowSales['daily_sales'] ?? 0;
	
	// Daily Returns
	$sqlReturn = "SELECT SUM(return_amount) as daily_return FROM order_returns WHERE DATE(return_date) = '$date'";
	$resultReturn = mysqli_query($connection, $sqlReturn);
	$rowReturn = mysqli_fetch_assoc($resultReturn);
	$returnTrendData[] = $rowReturn['daily_return'] ?? 0;

	// Daily Due
	$sqlDue = "SELECT SUM(due) as daily_due FROM orders WHERE order_date = '$date' AND order_status = 1";
	$resultDue = mysqli_query($connection, $sqlDue);
	$rowDue = mysqli_fetch_assoc($resultDue);
	// Subtract returns from due to get net daily due
	$dailyDueAmount = ($rowDue['daily_due'] ?? 0) - ($rowReturn['daily_return'] ?? 0);
	$dueTrendData[] = max($dailyDueAmount, 0);
	
	$salesTrendLabels[] = $dateLabel;
}

// Profit/Loss Data (Correction based on USER request)
// 1. Total Revenue (আয়)
$totalRevenueSql = "SELECT SUM(grand_total) as total FROM orders WHERE order_status = 1";
$revenueResult = mysqli_query($connection, $totalRevenueSql);
$revenueRow = mysqli_fetch_assoc($revenueResult);
$chartRevenue = $revenueRow['total'] ?? 0;

// 2. Total Expense (খরচ) - From spend table only as requested
$totalExpenseSql = "SELECT SUM(total) as expense FROM spend WHERE status = 1";
$expenseResult = mysqli_query($connection, $totalExpenseSql);
$expenseRow = mysqli_fetch_assoc($expenseResult);
$chartExpense = $expenseRow['expense'] ?? 0;

// 3. Profit (লাভ) - Following Today's Profit Card logic: (Revenue - COGS) - ReturnMargin
// Calculate total COGS
$totalCogsSql = "SELECT SUM(brate) as total_cost FROM order_item WHERE order_item_status = 1";
$cogsResult = mysqli_query($connection, $totalCogsSql);
$cogsRow = mysqli_fetch_assoc($cogsResult);
$totalCogs = $cogsRow['total_cost'] ?? 0;

// Calculate total Return Margin
$totalReturnLossSql = "SELECT SUM(or_ret.return_amount - (oi.brate / oi.quantity) * or_ret.return_quantity) as return_margin 
                        FROM order_returns or_ret 
                        JOIN order_item oi ON or_ret.order_item_id = oi.order_item_id";
$returnLossResult = mysqli_query($connection, $totalReturnLossSql);
$returnLossRow = mysqli_fetch_assoc($returnLossResult);
$totalReturnLoss = $returnLossRow['return_margin'] ?? 0;

// Gross Profit = (Revenue - COGS) - ReturnLoss
$chartProfit = ($chartRevenue - $totalCogs) - $totalReturnLoss;

// Total Return Amount for Chart (Recalculated)
$chartReturnSql = "SELECT SUM(or_ret.return_amount) as total_return 
                   FROM order_returns or_ret 
                   JOIN orders o ON or_ret.order_id = o.order_id 
                   WHERE o.order_status = 1";
$chartReturnQuery = mysqli_query($connection, $chartReturnSql);
$chartReturnRow = mysqli_fetch_assoc($chartReturnQuery);
$chartTotalReturn = $chartReturnRow['total_return'] ?? 0;

// Payment Status Data - Using backup file's simple query
$paidSql = "SELECT SUM(paid) as total_paid FROM orders WHERE order_status = 1";
$paidResult = mysqli_query($connection, $paidSql);
$paidRow = mysqli_fetch_assoc($paidResult);
$totalPaid = $paidRow['total_paid'] ?? 0;

// Use simple due query like backup file (payment_status = 3 means partial payment/due)
$dueSql = "SELECT SUM(due) as total_due FROM orders WHERE payment_status = 3 AND order_status = 1";
$dueResult = mysqli_query($connection, $dueSql);
$dueRow = mysqli_fetch_assoc($dueResult);
$totalDueChart = $dueRow['total_due'] ?? 0;

// Debug: Show values
echo "<!-- Debug Payment Chart: Total Paid = $totalPaid, Total Due Chart (Simple) = $totalDueChart, Safe Due (Complex) = $safeTotalDue -->";


// Category Sales Data
$categorySql = "SELECT c.categories_name, SUM(oi.quantity) as total_qty 
				FROM order_item oi 
				INNER JOIN product p ON oi.product_id = p.product_id 
				INNER JOIN categories c ON p.categories_id = c.categories_id 
				GROUP BY c.categories_id 
				ORDER BY total_qty DESC 
				LIMIT 5";
$categoryResult = mysqli_query($connection, $categorySql);
$categoryLabels = [];
$categoryData = [];
while ($row = mysqli_fetch_assoc($categoryResult)) {
	$categoryLabels[] = $row['categories_name'];
	$categoryData[] = $row['total_qty'];
}

// Product Sales Data
$productSql = "SELECT p.product_name, SUM(oi.quantity) as total_qty 
				FROM order_item oi 
				INNER JOIN product p ON oi.product_id = p.product_id 
				GROUP BY oi.product_id 
				ORDER BY total_qty DESC 
				LIMIT 5";
$productResult = mysqli_query($connection, $productSql);
$productLabels = [];
$productData = [];
while ($row = mysqli_fetch_assoc($productResult)) {
	$productLabels[] = $row['product_name'];
	$productData[] = $row['total_qty'];
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

	// Return Trend Chart
	const returnTrendCtx = document.getElementById('returnTrendChart').getContext('2d');
	new Chart(returnTrendCtx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($salesTrendLabels); ?>,
			datasets: [{
				label: 'দৈনিক ফেরত (৳)',
				data: <?php echo json_encode($returnTrendData); ?>,
				borderColor: '#ff4757',
				backgroundColor: 'rgba(255, 71, 87, 0.1)',
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

	// Due Trend Chart
	const dueTrendCtx = document.getElementById('dueTrendChart').getContext('2d');
	new Chart(dueTrendCtx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($salesTrendLabels); ?>,
			datasets: [{
				label: 'দৈনিক বাঁকি (৳)',
				data: <?php echo json_encode($dueTrendData); ?>,
				borderColor: '#e67e22',
				backgroundColor: 'rgba(230, 126, 34, 0.1)',
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
			labels: ['বিক্রয়', 'ফেরত', 'লাভ'],
			datasets: [{
				data: [<?php echo $chartRevenue; ?>, <?php echo $chartTotalReturn; ?>, <?php echo $chartProfit; ?>],
				backgroundColor: ['#4facfe', '#f5576c', '#43e97b'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { 
					display: true, 
					position: 'bottom'
				},
				tooltip: {
					callbacks: {
						label: function(context) {
							let label = context.label || '';
							if (label) {
								label += ': ';
							}
							const value = context.parsed;
							const total = context.dataset.data.reduce((a, b) => a + b, 0);
							const percentage = ((value / total) * 100).toFixed(1);
							label += value.toLocaleString() + ' ৳ (' + percentage + '%)';
							return label;
						}
					}
				}
			}
		}
	});
	
	// Populate Profit/Loss Summary
	const profitLossData = [<?php echo $chartRevenue; ?>, <?php echo $chartTotalReturn; ?>, <?php echo $chartProfit; ?>];
	const profitLossLabels = ['বিক্রয়', 'ফেরত', 'লাভ'];
	const profitLossColors = ['#4facfe', '#f5576c', '#43e97b'];
	const profitLossTotal = profitLossData.reduce((a, b) => a + b, 0);
	
	let summaryHTML = '';
	profitLossData.forEach((value, index) => {
		const percentage = ((value / profitLossTotal) * 100).toFixed(1);
		summaryHTML += `
			<div style="display: flex; align-items: center; margin-bottom: ${index < 2 ? '8px' : '0'};">
				<div style="width: 15px; height: 15px; background: ${profitLossColors[index]}; border-radius: 3px; margin-right: 8px;"></div>
				<span style="font-size: 13px; color: #555;">${profitLossLabels[index]}: ${value.toLocaleString()} ৳ (${percentage}%)</span>
			</div>
		`;
	});
	document.getElementById('profitLossSummary').innerHTML = summaryHTML;
	
	// Payment Status Chart
	const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
	const totalPaidValue = <?php echo $totalPaid; ?>;
	const totalDueValue = <?php echo $totalDueChart; ?>;
	
	console.log('Payment Status Chart Data:');
	console.log('Total Paid:', totalPaidValue);
	console.log('Total Due:', totalDueValue);
	
	new Chart(paymentStatusCtx, {
		type: 'doughnut',
		data: {
			labels: ['পরিশোধ', 'বাঁকি'],
			datasets: [{
				data: [totalPaidValue, totalDueValue],
				backgroundColor: ['#43e97b', '#f5576c'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { 
					display: true, 
					position: 'bottom'
				},
				tooltip: {
					callbacks: {
						label: function(context) {
							let label = context.label || '';
							if (label) {
								label += ': ';
							}
							const value = context.parsed;
							const total = context.dataset.data.reduce((a, b) => a + b, 0);
							const percentage = ((value / total) * 100).toFixed(1);
							label += value.toLocaleString() + ' ৳ (' + percentage + '%)';
							return label;
						}
					}
				}
			}
		}
	});
	
	// Populate Payment Status Summary
	const paymentStatusData = [totalPaidValue, totalDueValue];
	const paymentStatusLabels = ['পরিশোধ', 'বাঁকি'];
	const paymentStatusColors = ['#43e97b', '#f5576c'];
	const paymentStatusTotal = paymentStatusData.reduce((a, b) => a + b, 0);
	
	let paymentSummaryHTML = '';
	paymentStatusData.forEach((value, index) => {
		const percentage = ((value / paymentStatusTotal) * 100).toFixed(1);
		paymentSummaryHTML += `
			<div style="display: flex; align-items: center; margin-bottom: ${index < 1 ? '8px' : '0'};">
				<div style="width: 15px; height: 15px; background: ${paymentStatusColors[index]}; border-radius: 3px; margin-right: 8px;"></div>
				<span style="font-size: 13px; color: #555;">${paymentStatusLabels[index]}: ${value.toLocaleString()} ৳ (${percentage}%)</span>
			</div>
		`;
	});
	document.getElementById('paymentStatusSummary').innerHTML = paymentSummaryHTML;
	
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
	
	// Product Sales Chart
	const productSalesCtx = document.getElementById('productSalesChart').getContext('2d');
	new Chart(productSalesCtx, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($productLabels); ?>,
			datasets: [{
				label: 'বিক্রিত পরিমাণ',
				data: <?php echo json_encode($productData); ?>,
				backgroundColor: '#e67e22',
				borderColor: '#f39c12',
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

/* Background Icon Size Adjustment */
.cni {
    font-size: 45px !important;
    right: 15px !important;
    top: 15px !important;
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



        
