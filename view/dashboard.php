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

$connect->close();

?>
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
<div class="last-ten clearfix">
	<div class="col-md-4">
		<div class="last-report">
			<h2 class="ten-header">সর্বশেষ দশটি স্টক</h2>
			<div class="ten-report">
			<?php  
			 $sql = "SELECT * FROM pro   order by  pro.pro_id desc limit 10 ";
			 $qery = mysqli_query($connection, $sql);
          ?>
          <?php while($run = mysqli_fetch_array($qery)){
                $product_name = $run['pro_name'];
                 $product_qty = $run['qty'];
                 $brand_id = $run['brand_name'];
            	  $bidate = $run['pdate'];	
            	  $clor = $run['clor'];	

            	  $date=date_create("$bidate");
					  $bidate =date_format($date,"d/m/Y");
					  $bid = "SELECT brand_name FROM brands  where brand_id = '$brand_id'";
   				 $callbid = mysqli_query($connection, $bid);
   				while ($runbid = mysqli_fetch_array($callbid)){
   				 $bidcall = $runbid['brand_name'];
   				 
					}   
					?>
				<div class="s-ten-report">
					<p class="r-date"><?php echo $bidate; ?></p><p class="r-left"><?php echo $bidcall; ?>  <?php echo $product_name; ?></p><p class="r-right"><?php echo $product_qty; ?> <?php echo $clor; ?></p> <br>					
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="last-report">
			<h2 class="ten-header">সর্বশেষ দশটি অর্ডার</h2>
			<div class="ten-report">
			<?php  
			 $sql = "SELECT * FROM order_item  order by  order_item.order_item_id desc limit 10 ";
			 $qery = mysqli_query($connection, $sql);
          ?>

          <?php while($run = mysqli_fetch_array($qery)){
                	$date = $run['order_date'];
                  $orderid =  $run['order_id'];
                  $orderitemid =  $run['order_item_id'];
                  $date=date_create("$date");
					   $date =date_format($date,"d/m/Y");
               ?>

				
				<?php  
					//Order ID
					$sqlc = " SELECT order_id FROM orders  order by order_id = '$orderid' ";
					$qerycn = mysqli_query($connection, $sqlc);
				?>
				<?php while($run = mysqli_fetch_array($qerycn)){
					$C_name = $run['order_id'];
				}?>


               <?php $select = "SELECT quantity FROM order_item where order_item_id ='$orderitemid'";
   				$queryid = mysqli_query($connection, $select);
   				while ($runid = mysqli_fetch_array($queryid)){
   				 $id = $runid['quantity'];
					}
                ?>

               <?php  $proid = '';
               $selectproid = "SELECT product_id FROM order_item  where order_item_id = '$orderitemid'";
   				$queryproid = mysqli_query($connection, $selectproid);
   				while ($runid = mysqli_fetch_array($queryproid)){
   				 $proid = $runid['product_id'];
					}

					$callproid = "SELECT product_name FROM product  where product_id = '$proid'";
   				$queryproid = mysqli_query($connection, $callproid);
   				while ($callrunid = mysqli_fetch_array($queryproid)){
   				 $proname = $callrunid['product_name'];
					}

					$brandname = '';
					$brandidselect = "SELECT brand_id FROM product  where product_id = '$proid'";
   				$brandid = mysqli_query($connection, $brandidselect);
   				while ($callbrnadid = mysqli_fetch_array($brandid)){
   				 $brandname = $callbrnadid['brand_id'];
					}
		
					$brandsselect = "SELECT brand_name FROM brands  where brand_id = '$brandname'";
   				$brandsid = mysqli_query($connection, $brandsselect);
   				while ($callbrnadsid = mysqli_fetch_array($brandsid)){
   				 $brandsname = $callbrnadsid['brand_name'];
					}


                ?>
				<div class="s-ten-report">
					<p class="r-date"><?php echo $date; ?></p><p class="r-left">ID:- <?php echo $C_name; ?></p><p class="r-model"><?php echo $brandsname; ?> <?php echo $proname; ?></p><p class="r-right"><?php echo $id; ?> </p> <br>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="last-report">
			<h2 class="ten-header">সর্বশেষ দশটি বাঁকি</h2>
			<?php  
			 $sql = "SELECT * FROM order_item where payment_status = 3 order by  order_item.order_id desc limit 10 ";
			 $qery = mysqli_query($connection, $sql);
          ?>
          <?php while($run = mysqli_fetch_array($qery)){
                //$C_name = $run['client_name'];
                 $date = $run['order_date'];
                 $orderid =  $run['order_id'];
                 $orderitemid =  $run['order_item_id'];
                  $date=date_create("$date");
					   $date =date_format($date,"d/m/Y");
               ?>
					
				<?php  
	        		//client name
	          $sqlc10 = " SELECT client_name FROM orders  order by order_id = '$orderid' ";
				 $qerycn10 = mysqli_query($connection, $sqlc10);
	          ?>
			  <?php while($run = mysqli_fetch_array($qerycn10)){
                $C_name = $run['client_name'];
             }
               ?>


		          <?php $select = "SELECT quantity FROM order_item where order_item_id ='$orderitemid'";
					$queryid = mysqli_query($connection, $select);
					while ($runid = mysqli_fetch_array($queryid)){
					 $id = $runid['quantity'];
					}

					 $selectproid = "SELECT product_id FROM order_item  where order_item_id = '$orderitemid'";
   				$queryproid = mysqli_query($connection, $selectproid);
   				while ($runid = mysqli_fetch_array($queryproid)){
   				 $proid = $runid['product_id'];
					}

					$callproid = "SELECT product_name FROM product  where product_id = '$proid'";
   				$queryproid = mysqli_query($connection, $callproid);
   				while ($callrunid = mysqli_fetch_array($queryproid)){
   				 $proname = $callrunid['product_name'];
					}

					$brandidselect = "SELECT brand_id FROM product  where product_id = '$proid'";
   				$brandid = mysqli_query($connection, $brandidselect);
   				while ($callbrnadid = mysqli_fetch_array($brandid)){
   				 $brandname = $callbrnadid['brand_id'];
					}

					$brandsselect = "SELECT brand_name FROM brands  where brand_id = '$brandname'";
   				$brandsid = mysqli_query($connection, $brandsselect);
   				while ($callbrnadsid = mysqli_fetch_array($brandsid)){
   				 $brandsname = $callbrnadsid['brand_name'];
					}

          ?>
			 <div class="ten-report">
				<div class="s-ten-report">
					<p class="r-date"><?php echo $date; ?></p><p class="r-left"><?php echo $C_name; ?></p><p class="r-model"><?php echo $brandsname; ?> <?php echo $proname; ?></p><p class="r-right"><?php echo $id; ?></p> <br>
				</div>
				</div>
				<?php } ?>
		</div>
	</div>
</div>
<div class="dash-report clearfix">
	<div class="col-md-2">
		<div class="report-body">
			<h4 class="report-header">আজ</h4>
			<div class="s-report">
				<?php $today = date('Y-m-d');
					$todaytotall = "SELECT * FROM order_item WHERE order_date = '$today' "; 
					$qeryt = mysqli_query($connection, $todaytotall);
				?>
				<?php  
					$quantity = 0;
					while($run = mysqli_fetch_array($qeryt)){
						$date = $run['order_date']; 
						$id = $run['order_id'];  
						$quantity += $run['quantity'];  
				}?>

				<?php  //paid
					$ttodaypaid = "SELECT * FROM orders WHERE order_date = '$today'"; 
					$qerypt = mysqli_query($connection, $ttodaypaid);
				?>
				<?php  
					$total = 0;
					$gtotal = 0;
					$ppaidqty = 0;
					while($run = mysqli_fetch_array($qerypt)){ 
						$total += $run['paid'];  
						$gtotal += $run['grand_total']; 
						$ppaidqty += $run['due']; 
				}?>
			

				<?php  //lav
					$ttodaylav = "SELECT * FROM order_item WHERE order_date = '$today'"; 
					$qerylv = mysqli_query($connection, $ttodaylav);
				?>
				<?php
					$totalbrate=0;
					while($runbrate = mysqli_fetch_array($qerylv)){ 
						$totalbrate += $runbrate['brate'];  
				}?>
				<?php $lav = $gtotal-$totalbrate; ?>

				<?php //paid qty
					$today = date('Y-m-d');
					$todaypaid = "SELECT * FROM order_item WHERE order_date = '$today' AND payment_status = 1 "; 
					$qeryp = mysqli_query($connection, $todaypaid);
				?>
				<?php  
					$paidqty = 0;
					while($runp = mysqli_fetch_array($qeryp)){ 
						$paidqty += $runp['quantity'];  
				}?>
				<p class="r-left">সর্বমোট বিক্রয় :</p><p class="r-right"><?php echo $quantity; ?> টি</p><br>
				<p class="r-left">পূর্ণ পরিশোধ :</p><p class="r-right"><?php echo $paidqty; ?> টি</p><br>
				<p class="r-left">পরিশোধ :</p><p class="r-right"><?php echo $total; ?> ৳</p><br>
				<p class="r-left">বাঁকি :</p><p class="r-right"><?php echo $ppaidqty; ?> ৳</p><br>
				<p class="r-left">সর্বমোট :</p><p class="r-right"><?php echo $gtotal; ?> ৳</p><br>
				<?php if($_SESSION['Status']=='5') { ?>
				<p class="r-left">লাভ :</p><p class="r-right"><?php echo $lav; ?> ৳</p><?php } ?><br>

			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="report-body">
			<h4 class="report-header">গতকাল</h4>
			<div class="s-report">
				<?php 	//y quantity
					$yesterday = date('Y-m-d',strtotime("-1 days"));
					$todaytotall = "SELECT * FROM order_item WHERE order_date = '$yesterday' "; 
					$qeryy = mysqli_query($connection, $todaytotall);
				?>
				<?php  
					$yquantity = 0;
					while($run = mysqli_fetch_array($qeryy)){
						$date = $run['order_date']; 
						$id = $run['order_id'];  
						$yquantity += $run['quantity'];                   
				}?>
				<?php 
					$todaypaid = "SELECT * FROM order_item WHERE order_date = '$yesterday' AND payment_status = 1 "; 
					$qeryp = mysqli_query($connection, $todaypaid);
				?>
				<?php  
					$ypaidqty = 0;
					while($runp = mysqli_fetch_array($qeryp)){ 
						$ypaidqty += $runp['quantity'];  
				}?>

				<?php  //.............................
					$yesterday = date('Y-m-d',strtotime("-1 days"));
					$ytodaypaid = "SELECT * FROM orders WHERE order_date = '$yesterday'"; 
					$qerypy = mysqli_query($connection, $ytodaypaid);
				?>
				<?php  
					$ytotal = 0;
					$ygtotal = 0;
					$yppaidqty = 0;
					while($run = mysqli_fetch_array($qerypy)){ 
						$ytotal += $run['paid'];
						$ygtotal += $run['grand_total']; 
						$yppaidqty +=$run['due'];
				} ?>
			

				<?php  //y profit
					$ydaylav = "SELECT * FROM order_item WHERE order_date = '$yesterday'"; 
					$qerylvy = mysqli_query($connection, $ydaylav);
				?>
				<?php   
					$ytotalbrate=0;
					while($runbratey = mysqli_fetch_array($qerylvy)){ 
						$ytotalbrate += $runbratey['brate'];  
				}?>
				<?php  $ylav = $ygtotal-$ytotalbrate; ?>

				<p class="r-left">সর্বমোট বিক্রয় :</p><p class="r-right"><?php echo $yquantity; ?> টি</p><br>
				<p class="r-left">পূর্ণ পরিশোধ :</p><p class="r-right"><?php echo $ypaidqty; ?> টি</p><br>
				<p class="r-left">পরিশোধ :</p><p class="r-right"><?php echo $ytotal; ?> ৳</p><br>
				<p class="r-left">বাঁকি :</p><p class="r-right"><?php echo $yppaidqty; ?> ৳</p><br>
				<p class="r-left">সর্বমোট :</p><p class="r-right"><?php echo $ygtotal; ?> ৳</p><br>
				<?php if($_SESSION['Status']=='5') { ?>
				<p class="r-left">লাভ :</p><p class="r-right"><?php echo $ylav; ?> ৳</p><?php } ?><br>
 
			</div>
		</div>
	</div>

	<div class="col-md-2">
		<div class="report-body">
			<h4 class="report-header">সর্বশেষ সাত দিন</h4>
			<div class="s-report">
				<?php $today = date('Y-m-d'); //Total Sells Quantity
					$todaytotall7 = "SELECT * FROM order_item WHERE order_date > (CURDATE()) - INTERVAL 7 DAY"; 
					$qery30 = mysqli_query($connection, $todaytotall7);
				?>
				<?php  
					$quantity7 = 0;
					while($run = mysqli_fetch_array($qery30)){
						$date = $run['order_date']; 
						$id = $run['order_id'];  
						$quantity7 += $run['quantity']; 
				}?>

				<?php  //Grand Total Amount
					$ytodaypaid7 = "SELECT * FROM orders WHERE order_date> (CURDATE()) - INTERVAL 7 DAY"; 
					$qerypy7 = mysqli_query($connection, $ytodaypaid7);
				?>
				<?php  
					$total7 = 0;
					$gtotal17 = 0;
					$ppaidqty7 = 0;
					while($run = mysqli_fetch_array($qerypy7)){ 
						$total7 += $run['paid'];
						$gtotal17 += $run['grand_total']; 
						$ppaidqty7 += $run['due']; 

				} ?>
				

				<?php  //lav
					$daylav7 = "SELECT * FROM order_item WHERE order_date> (CURDATE()) - INTERVAL 7 DAY"; 
					$qerylv7 = mysqli_query($connection, $daylav7);
				?>
				<?php   $totalbrate7=0;
					while($runbrate7 = mysqli_fetch_array($qerylv7)){ 
						$totalbrate7 += $runbrate7['brate'];  
				}?>
				<?php $lav7 = $gtotal17-$totalbrate7;  ?>

				<?php $today = date('Y-m-d'); //Full paid quantity
					$todaypaid7 = "SELECT * FROM order_item WHERE order_date > (CURDATE()) - INTERVAL 7 DAY AND payment_status = 1 "; 
					$qeryp7 = mysqli_query($connection, $todaypaid7);
				?>
				<?php  
					$paidqty7 = 0;
					while($runp = mysqli_fetch_array($qeryp7)){ 
						$paidqty7 += $runp['quantity'];  
				}?>

				<p class="r-left">সর্বমোট বিক্রয় :</p><p class="r-right"><?php echo $quantity7; ?> টি</p><br>
				<p class="r-left">পূর্ণ পরিশোধ :</p><p class="r-right"><?php echo $paidqty7; ?> টি</p><br>
				<p class="r-left">পরিশোধ :</p><p class="r-right"><?php echo $total7; ?> ৳</p><br>
				<p class="r-left">বাঁকি :</p><p class="r-right"><?php echo $ppaidqty7; ?> ৳</p><br>
				<p class="r-left">সর্বমোট :</p><p class="r-right"><?php echo $gtotal17; ?> ৳</p><br>
				<?php if($_SESSION['Status']=='5') { ?>
				<p class="r-left">লাভ :</p><p class="r-right"><?php echo $lav7; ?> ৳</p><?php } ?><br>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="report-body">
			<h4 class="report-header">সর্বশেষ ত্রিশ দিন</h4>
			<div class="s-report">
					<?php $today = date('Y-m-d');
						$todaytotall30 = "SELECT * FROM order_item WHERE order_date > (CURDATE()) - INTERVAL 30 DAY"; 
						$qery30 = mysqli_query($connection, $todaytotall30);
					?>
					<?php  
						$quantity30 = 0;
						while($run = mysqli_fetch_array($qery30)){
							$date = $run['order_date']; 
							$id = $run['order_id'];  
							$quantity30 += $run['quantity'];  
					}?>

					<?php  //Grand total & Total Paid
						$ytodaypaid30 = "SELECT * FROM orders WHERE order_date > (CURDATE()) - INTERVAL 30 DAY"; 
						$qerypy30 = mysqli_query($connection, $ytodaypaid30);
					?>
					<?php  
						$total30 = 0;
						$gtotal30 = 0;
						$due30 = 0;
						while($run = mysqli_fetch_array($qerypy30)){ 
							$total30 += $run['paid'];
							$gtotal30 += $run['grand_total']; 
							$due30 += $run['due']; 
					} ?>

					<?php  //Total Profit
						$daylav30 = "SELECT * FROM order_item WHERE order_date > (CURDATE()) - INTERVAL 30 DAY"; 
						$qerylv30 = mysqli_query($connection, $daylav30);
					?>
					<?php
						$totalbrate30=0;
						while($runbrate7 = mysqli_fetch_array($qerylv30)){ 
							$totalbrate30 += $runbrate7['brate'];  
					}?>
					<?php $lav30 = $gtotal30-$totalbrate30;  ?>

					<?php //Full Paid Qty
						$todaypaid30 = "SELECT * FROM order_item WHERE order_date > (CURDATE()) - INTERVAL 30 DAY AND payment_status = 1 "; 
						$qeryp30 = mysqli_query($connection, $todaypaid30);
					?>
					<?php  
						$paidqty30 = 0;
						while($runp = mysqli_fetch_array($qeryp30)){ 
							$paidqty30 += $runp['quantity'];  
					}?>
				<p class="r-left">সর্বমোট বিক্রয় :</p><p class="r-right"><?php echo $quantity30; ?> টি</p><br>
				<p class="r-left">পূর্ণ পরিশোধ :</p><p class="r-right"><?php echo $paidqty30; ?> টি</p><br>
				<p class="r-left">পরিশোধ :</p><p class="r-right"><?php echo $total30; ?> ৳</p><br>
				<p class="r-left">বাঁকি :</p><p class="r-right"><?php echo $due30; ?> ৳</p><br>
				<p class="r-left">সর্বমোট :</p><p class="r-right"><?php echo $gtotal30; ?> ৳</p><br>
				<?php if($_SESSION['Status']=='5') { ?>
				<p class="r-left">লাভ :</p><p class="r-right"><?php echo $lav30; ?> ৳</p><?php } ?><br>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="report-body">
			<h4 class="report-header">গত মাস</h4>
			<div class="s-report">
				<?php $today = date('Y-m-d');
					$todaytotallm = "SELECT * FROM order_item WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH);"; 
					$qerym = mysqli_query($connection, $todaytotallm);
				?>
				<?php  
					$quantitym = 0;
					while($run = mysqli_fetch_array($qerym)){
						$date = $run['order_date']; 
						$id = $run['order_id'];  
						$quantitym += $run['quantity'];  
				}?>

				<?php  //paid
					$ytodaypaidm = "SELECT * FROM orders WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)"; 
					$qerypym = mysqli_query($connection, $ytodaypaidm);
				?>
				<?php  
					$totalm = 0; 
					$gtotalm = 0; 
					$ppaidqtym = 0; 
					while($run = mysqli_fetch_array($qerypym)){ 
						$totalm += $run['paid'];
						$gtotalm += $run['grand_total']; 
						$ppaidqtym += $run['due']; 
				} ?>
				<?php  //lav
					$daylavm = "SELECT * FROM order_item WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)"; 
					$qerylvm = mysqli_query($connection, $daylavm);
				?>
				<?php 
					$totalbratem=0;
					while($runbrate7 = mysqli_fetch_array($qerylvm)){ 
						$totalbratem += $runbrate7['brate'];  
				}?>
				<?php $lavm = $gtotalm-$totalbratem;  ?>

				<?php $today = date('Y-m-d');
					$todaypaidm = "SELECT * FROM order_item WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND payment_status = 1 "; 
					$qerypm = mysqli_query($connection, $todaypaidm);
				?>
				<?php  
					$paidqtym = 0;
					while($runp = mysqli_fetch_array($qerypm)){ 
						$paidqtym += $runp['quantity'];  
				}?>
				<p class="r-left">সর্বমোট বিক্রয় :</p><p class="r-right"><?php echo $quantitym; ?> টি</p><br>
				<p class="r-left">পূর্ণ পরিশোধ :</p><p class="r-right"><?php echo $paidqtym; ?> টি</p><br>
				<p class="r-left">পরিশোধ :</p><p class="r-right"><?php echo $totalm; ?> ৳</p><br>
				<p class="r-left">বাঁকি :</p><p class="r-right"><?php echo $ppaidqtym; ?> ৳</p><br>
				<p class="r-left">সর্বমোট :</p><p class="r-right"><?php echo $gtotalm; ?> ৳</p><br>
				<?php if($_SESSION['Status']=='5') { ?>
				<p class="r-left">লাভ :</p><p class="r-right"><?php echo $lavm; ?> ৳</p><?php } ?><br>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="report-body">
			<h4 class="report-header">গত বছর</h4>
			<div class="s-report">
					<?php $today = date('Y-m-d');
						$todaytotally = "SELECT * FROM order_item WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 year) AND year(order_date) = year(CURRENT_DATE - INTERVAL 1 year)
						"; 
						$qeryy = mysqli_query($connection, $todaytotally);
					?>
					<?php  
						$quantityy = 0;
						while($run = mysqli_fetch_array($qeryy)){
							$date = $run['order_date']; 
							$id = $run['order_id'];  
							$quantityy += $run['quantity']; 
					}?>

					<?php  //paid
						$ytodaypaidy = "SELECT * FROM orders WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 year) AND year(order_date) = year(CURRENT_DATE - INTERVAL 1 year)"; 
						$qerypymy = mysqli_query($connection, $ytodaypaidy);
					?>
					<?php  
						$totaly = 0; 
						$gtotalmy = 0; 
						$ppaidqtyy = 0; 
						while($run = mysqli_fetch_array($qerypymy)){ 
							$totaly += $run['paid'];
							$gtotalmy+= $run['grand_total']; 
							$ppaidqtyy+= $run['due']; 
					} ?>
					

					<?php  //lav
						$daylavmy = "SELECT * FROM order_item WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 year) AND year(order_date) = year(CURRENT_DATE - INTERVAL 1 year)"; 
						$qerylvmy = mysqli_query($connection, $daylavmy);
					?>
					<?php
						$totalbratemy=0;
						while($runbrate7 = mysqli_fetch_array($qerylvmy)){ 
							$totalbratemy += $runbrate7['brate'];  
					}?>
					<?php $lavmy = $gtotalmy-$totalbratemy;  ?>

					<?php $today = date('Y-m-d');
						$todaypaidy = "SELECT * FROM order_item WHERE YEAR(order_date) = YEAR(CURRENT_DATE - INTERVAL 1 year) AND year(order_date) = year(CURRENT_DATE - INTERVAL 1 year)
						AND payment_status = 1 "; 
						$qerypm = mysqli_query($connection, $todaypaidy);
					?>
					<?php  
						$paidqtyy = 0;
						while($runp = mysqli_fetch_array($qerypm)){ 
							$paidqtyy += $runp['quantity'];  
					}?>
				<p class="r-left">সর্বমোট বিক্রয় :</p><p class="r-right"><?php echo $quantityy; ?> টি</p><br>
				<p class="r-left">পূর্ণ পরিশোধ :</p><p class="r-right"><?php echo $paidqtyy; ?> টি</p><br>
				<p class="r-left">পরিশোধ :</p><p class="r-right"><?php echo $totaly; ?> ৳</p><br>
				<p class="r-left">বাঁকি :</p><p class="r-right"><?php echo $ppaidqtyy; ?> ৳</p><br>
				<p class="r-left">সর্বমোট :</p><p class="r-right"><?php echo $gtotalmy; ?> ৳</p><br>
					<?php if($_SESSION['Status']=='5') { ?>
				<p class="r-left">লাভ :</p><p class="r-right"><?php echo $lavmy; ?> ৳</p><?php } ?><br>
			</div>
		</div>
	</div>
</div>

<?php require_once 'includes/footer.php'; ?>



        
