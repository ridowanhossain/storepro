<?php require_once 'includes/header.php'; ?>

<?php
// Summary Card Data
$today = date('Y-m-d');
$currentMonth = date('Y-m');

// Total Expense (All Time)
$totalExpenseSql = "SELECT SUM(total) as total_expense FROM spend WHERE status = 1";
$totalExpenseResult = $connect->query($totalExpenseSql);
$totalExpenseData = $totalExpenseResult->fetch_assoc();
$totalExpense = $totalExpenseData['total_expense'] ?? 0;

// This Month's Expense
$monthExpenseSql = "SELECT SUM(total) as month_expense FROM spend WHERE status = 1 AND DATE_FORMAT(spend_date, '%Y-%m') = '$currentMonth'";
$monthExpenseResult = $connect->query($monthExpenseSql);
$monthExpenseData = $monthExpenseResult->fetch_assoc();
$monthExpense = $monthExpenseData['month_expense'] ?? 0;

// Today's Expense
$todayExpenseSql = "SELECT SUM(total) as today_expense FROM spend WHERE status = 1 AND spend_date = '$today'";
$todayExpenseResult = $connect->query($todayExpenseSql);
$todayExpenseData = $todayExpenseResult->fetch_assoc();
$todayExpenseTotal = $todayExpenseData['today_expense'] ?? 0;

// This Year's Expense
$currentYear = date('Y');
$yearExpenseSql = "SELECT SUM(total) as year_expense FROM spend WHERE status = 1 AND YEAR(spend_date) = '$currentYear'";
$yearExpenseResult = $connect->query($yearExpenseSql);
$yearExpenseData = $yearExpenseResult->fetch_assoc();
$yearExpense = $yearExpenseData['year_expense'] ?? 0;

// Bengali Day Name
$days = [
    'Saturday' => 'শনিবার',
    'Sunday' => 'রবিবার',
    'Monday' => 'সোমবার',
    'Tuesday' => 'মঙ্গলবার',
    'Wednesday' => 'বুধবার',
    'Thursday' => 'বৃহস্পতিবার',
    'Friday' => 'শুক্রবার'
];
$todayName = date('l');
$banglaDay = $days[$todayName];
$todayName = date('l');
$banglaDay = $days[$todayName];
$todayCardTitle = $banglaDay . 'ের খরচ';

// Yesterday's Expense
$yesterday = date('Y-m-d', strtotime("-1 days"));
$yesterdayExpenseSql = "SELECT SUM(total) as yesterday_expense FROM spend WHERE status = 1 AND spend_date = '$yesterday'";
$yesterdayExpenseResult = $connect->query($yesterdayExpenseSql);
$yesterdayExpenseData = $yesterdayExpenseResult->fetch_assoc();
$yesterdayExpense = $yesterdayExpenseData['yesterday_expense'] ?? 0;

// Last 7 Days Expense
$sevenDaysAgo = date('Y-m-d', strtotime("-7 days"));
$sevenDaysExpenseSql = "SELECT SUM(total) as seven_days_expense FROM spend WHERE status = 1 AND spend_date >= '$sevenDaysAgo'";
$sevenDaysExpenseResult = $connect->query($sevenDaysExpenseSql);
$sevenDaysExpenseData = $sevenDaysExpenseResult->fetch_assoc();
$sevenDaysExpense = $sevenDaysExpenseData['seven_days_expense'] ?? 0;

// Yesterday Name (Bengali)
$yesterdayName = date('l', strtotime("-1 days"));
$banglaYesterday = $days[$yesterdayName];
$yesterdayCardTitle = $banglaYesterday . 'ের খরচ';

// Bengali Month Names
$months = [
    'January' => 'জানুয়ারি',
    'February' => 'ফেব্রুয়ারি',
    'March' => 'মার্চ',
    'April' => 'এপ্রিল',
    'May' => 'মে',
    'June' => 'জুন',
    'July' => 'জুলাই',
    'August' => 'আগস্ট',
    'September' => 'সেপ্টেম্বর',
    'October' => 'অক্টোবর',
    'November' => 'নভেম্বর',
    'December' => 'ডিসেম্বর'
];
$currentMonthName = date('F');
$banglaMonth = $months[$currentMonthName];
$monthCardTitle = $banglaMonth . ' মাসের খরচ';

// Bengali Year
$engYear = date('Y');
$banglaNumbers = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
$engNumbers = ['0','1','2','3','4','5','6','7','8','9'];
$banglaYear = str_replace($engNumbers, $banglaNumbers, $engYear);
$yearCardTitle = $banglaYear . ' সালের খরচ';

// Previous Month Expense
$prevMonthDate = date('Y-m', strtotime('first day of last month'));
$prevMonthExpenseSql = "SELECT SUM(total) as prev_month_expense FROM spend WHERE status = 1 AND DATE_FORMAT(spend_date, '%Y-%m') = '$prevMonthDate'";
$prevMonthExpenseResult = $connect->query($prevMonthExpenseSql);
$prevMonthExpenseData = $prevMonthExpenseResult->fetch_assoc();
$prevMonthExpense = $prevMonthExpenseData['prev_month_expense'] ?? 0;

// Previous Month Name (Bengali)
$prevMonthNameEng = date('F', strtotime('first day of last month'));
$banglaPrevMonth = $months[$prevMonthNameEng]; // Uses same $months array
$prevMonthCardTitle = $banglaPrevMonth . ' মাসের খরচ'; // (Prev Month)

// Previous Year Expense
$prevYearDate = date('Y', strtotime('-1 year'));
$prevYearExpenseSql = "SELECT SUM(total) as prev_year_expense FROM spend WHERE status = 1 AND YEAR(spend_date) = '$prevYearDate'";
$prevYearExpenseResult = $connect->query($prevYearExpenseSql);
$prevYearExpenseData = $prevYearExpenseResult->fetch_assoc();
$prevYearExpense = $prevYearExpenseData['prev_year_expense'] ?? 0;

// Previous Year Name (Bengali)
$banglaPrevYear = str_replace($engNumbers, $banglaNumbers, $prevYearDate);
$prevYearCardTitle = $banglaPrevYear . ' সালের খরচ'; // (Prev Year)

$connect->close();

// Check if user is Status 5 (Only Status 5 can see Action column)
$isAdmin = ($_SESSION['Status'] == 5);
?>

<!-- Summary Cards -->
<style>
    .expense-cards .upper-cn {
        padding: 15px 10px; /* Adjust padding */
    }
    .expense-cards .upper-cn h4 {
        font-size: 13px !important;
        margin-top: 0 !important;
        margin-bottom: 5px !important;
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .expense-cards .upper-cn h2 {
        font-size: 22px !important;
        margin: 5px 0 !important;
        font-weight: bold;
    }
    .expense-cards .upper-cn p {
        font-size: 11px !important;
        margin-bottom: 0 !important;
    }
    .expense-cards .upper-cn i.cni {
        font-size: 30px !important;
        opacity: 0.3 !important;
    }
</style>

<div class="cn-body clearfix expense-cards" style="margin-bottom: 25px;">
	<div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn8 clearfix" style="background: linear-gradient(135deg, #4c2089 0%, #6e48aa 100%);">
			<h4 class=""><?php echo $todayCardTitle; ?></h4>
			<h2 class=""><?php echo number_format($todayExpenseTotal); ?> ৳</h2>
			<p class="ttn">আজকের মোট খরচ</p>
			<i class="fa fa-minus-square cni"></i>
		</div>
	</div>
    <div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn5 clearfix" style="background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);">
			<h4 class=""><?php echo $yesterdayCardTitle; ?></h4>
			<h2 class=""><?php echo number_format($yesterdayExpense); ?> ৳</h2>
			<p class="ttn">গতকালের মোট খরচ</p>
			<i class="fa fa-clock-o cni"></i>
		</div>
	</div>
    <div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn9 clearfix" style="background: linear-gradient(135deg, #16a085 0%, #c44569 100%);">
			<h4 class="">গত সাত দিনের খরচ</h4>
			<h2 class=""><?php echo number_format($sevenDaysExpense); ?> ৳</h2>
			<p class="ttn">গত ৭ দিনের মোট খরচ</p>
			<i class="fa fa-area-chart cni"></i>
		</div>
	</div>
	<div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn12 clearfix" style="background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);">
			<h4 class=""><?php echo $monthCardTitle; ?></h4>
			<h2 class=""><?php echo number_format($monthExpense); ?> ৳</h2>
			<p class="ttn">এই মাসের মোট খরচ</p>
			<i class="fa fa-calendar cni"></i>
		</div>
	</div>
    <div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn4 clearfix" style="background: linear-gradient(135deg, #2C3E50 0%, #4CA1AF 100%);">
			<h4 class=""><?php echo $prevMonthCardTitle; ?></h4>
			<h2 class=""><?php echo number_format($prevMonthExpense); ?> ৳</h2>
			<p class="ttn">পূর্ববর্তী মাসের খরচ</p>
			<i class="fa fa-calendar-minus-o cni"></i>
		</div>
	</div>
	<div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn2 clearfix" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
			<h4 class=""><?php echo $yearCardTitle; ?></h4>
			<h2 class=""><?php echo number_format($yearExpense); ?> ৳</h2>
			<p class="ttn">এই বছরের মোট খরচ</p>
			<i class="fa fa-bar-chart cni"></i>
		</div>
	</div>
    <div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn6 clearfix" style="background: linear-gradient(135deg, #1e88e5 0%, #0d7377 100%);">
			<h4 class=""><?php echo $prevYearCardTitle; ?></h4>
			<h2 class=""><?php echo number_format($prevYearExpense); ?> ৳</h2>
			<p class="ttn">পূর্ববর্তী বছরের খরচ</p>
			<i class="fa fa-history cni"></i>
		</div>
	</div>
	<div class="clearfix" style="width: 12.5%; float: left; padding: 0 5px;">
		<div class="upper-cn upper-cn1 clearfix" style="background: linear-gradient(135deg, #FF512F 0%, #DD2476 100%);">
			<h4 class="">মোট খরচ</h4>
			<h2 class=""><?php echo number_format($totalExpense); ?> ৳</h2>
			<p class="ttn">সর্বমোট খরচ</p>
			<i class="fa fa-calculator cni"></i>
		</div>
	</div>
</div>

<!-- Expense Table -->
<div class="main-table">
	<div class="product-table-card">
		<div class="table-title" style="display: flex; justify-content: space-between; align-items: center;">
			<div>
				<i class="fa fa-calculator"></i> <span>খরচের হিসাব</span>
			</div>
			<?php if($_SESSION['Status'] == 1 || $_SESSION['Status'] == 2 || $_SESSION['Status'] == 3 || $_SESSION['Status'] == 5) { ?>
			<div>
				<button class="btn btn-modern btn-spend" type="button" data-toggle="modal" data-target="#addspend"><i class="glyphicon glyphicon-plus-sign"></i> খরচ যোগ করুন</button>
			</div>
			<?php } ?>
		</div>
		<div class="remove-messages"></div>
		<table class="table table-bordered modern-table dataTable no-footer dtr-inline" id="manageinvocetable">
			<thead>
				<tr>
					<th class="text-center" style="width: 50px;">#</th>
					<th class="text-center">ক্যাটাগরি</th>
					<th class="text-center">বিবরণ</th>
					<th class="text-center">তারিখ</th>
					<th class="text-center">টাকা</th>
					<?php if($isAdmin) { ?>
					<th class="text-center" style="width: 120px;">ব্যবস্থা</th>
					<?php } ?>
				</tr>
			</thead> 
		</table>
	</div>
</div>

<!--START ADD SPEND -->
<div id="addspend" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="submitspendForm" action="php_action/createSpend.php" method="POST" role="form">
				<div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-plus-circle"></i> নতুন খরচ যোগ করুন</h4>
				</div>
				<div class="modal-body">
					<div id="add-spend-messages"></div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="spend_category" class="control-label">ক্যাটাগরি <span style="color:red;">*</span></label>
								<select class="form-control input-lg" id="spend_category" name="spend_category" required>
									<option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>
									<option value="চা-নাস্তা">🍵 চা-নাস্তা</option>
									<option value="বিদ্যুৎ বিল">⚡ বিদ্যুৎ বিল</option>
									<option value="বেতন">💼 বেতন</option>
									<option value="পরিবহন">🚗 পরিবহন</option>
									<option value="মেরামত">🔧 মেরামত</option>
									<option value="ভাড়া">🏠 ভাড়া</option>
									<option value="যোগাযোগ">📱 যোগাযোগ (মোবাইল/ইন্টারনেট)</option>
									<option value="প্যাকেজিং">📦 প্যাকেজিং</option>
									<option value="অন্যান্য">📋 অন্যান্য</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="spend_date" class="control-label">তারিখ <span style="color:red;">*</span></label>
								<input type="text" class="form-control input-lg" id="spend_date" name="spend_date" placeholder="দিন/মাস/বছর" autocomplete="off" required />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="spend_description" class="control-label">বিবরণ <span style="color:red;">*</span></label>
								<input type="text" class="form-control input-lg" id="spend_description" name="spend_description" placeholder="যেমন: অমুক কাস্টমারের চা বাবদ" required />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="spend_amount" class="control-label">টাকা <span style="color:red;">*</span></label>
								<input type="number" class="form-control input-lg" id="spend_amount" name="spend_amount" placeholder="০" step="0.01" min="0" required />
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" id="createspendBtn" name="adduser" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--START EDIT SPEND -->
<div id="editspend" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editspendForm" action="php_action/editSpend.php" method="POST" role="form">
				<div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-edit"></i> খরচ সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
					<div id="edit-spend-messages"></div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="edit_spend_category" class="control-label">ক্যাটাগরি <span style="color:red;">*</span></label>
								<select class="form-control input-lg" id="edit_spend_category" name="edit_spend_category" required>
									<option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>
									<option value="চা-নাস্তা">🍵 চা-নাস্তা</option>
									<option value="বিদ্যুৎ বিল">⚡ বিদ্যুৎ বিল</option>
									<option value="বেতন">💼 বেতন</option>
									<option value="পরিবহন">🚗 পরিবহন</option>
									<option value="মেরামত">🔧 মেরামত</option>
									<option value="ভাড়া">🏠 ভাড়া</option>
									<option value="যোগাযোগ">📱 যোগাযোগ (মোবাইল/ইন্টারনেট)</option>
									<option value="প্যাকেজিং">📦 প্যাকেজিং</option>
									<option value="অন্যান্য">📋 অন্যান্য</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="edit_spend_date" class="control-label">তারিখ <span style="color:red;">*</span></label>
								<input type="text" class="form-control input-lg" id="edit_spend_date" name="edit_spend_date" placeholder="দিন/মাস/বছর" autocomplete="off" required />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="edit_spend_description" class="control-label">বিবরণ <span style="color:red;">*</span></label>
								<input type="text" class="form-control input-lg" id="edit_spend_description" name="edit_spend_description" placeholder="খরচের বিবরণ" required />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="edit_spend_amount" class="control-label">টাকা <span style="color:red;">*</span></label>
								<input type="number" class="form-control input-lg" id="edit_spend_amount" name="edit_spend_amount" placeholder="০" step="0.01" min="0" required />
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer editspendFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" name="editspend" id="editspendBtn" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--START REMOVE SPEND -->


<script src="custom/js/spend.js?v=<?php echo filemtime('custom/js/spend.js'); ?>"></script>
<?php require_once 'includes/footer.php'; ?>
