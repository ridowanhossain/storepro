<?php
require_once 'php_action/db_connect.php';
require_once 'includes/header.php';

if ($_GET['o'] == 'add') {
	// add order
	echo "<div class='div-request div-hide'>add</div>";
} else if ($_GET['o'] == 'show') {
	echo "<div class='div-request div-hide'>show</div>";
} else if ($_GET['o'] == 'editOrd') {
	echo "<div class='div-request div-hide'>editOrd</div>";
} // /else manage order


?>

<ol class="breadcrumb">
	<li><a href="dashboard">ড্যাসবোর্ড</a></li>
	<li>অর্ডার</li>
	<li class="active">
		<?php if ($_GET['o'] == 'add') { ?>
			অর্ডার করুন
		<?php } else if ($_GET['o'] == 'show') { ?>
			নিয়মিত অর্ডার পরিচালনা
		<?php } // /else manage order 
		?>
	</li>
</ol>
<div class="panel panel-default">
	<div class="panel-heading">
		<i class="glyphicon glyphicon-edit"></i> নিয়মিত অর্ডার পরিচালনা
	</div> <!--/panel-->
	<div class="panel-body">
		<?php if ($_GET['o'] == 'show') { ?>
			<div id="success-messages"></div>
			<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer" id="rmanageOrderTable">
				<thead>
					<tr>
						<th>ক্রমিক</th>
						<th>আইডি</th>
						<th>তারিখ</th>
						<th>ক্রেতার নাম</th>
						<th>মোবাইল নাম্বার</th>
						<th>ঠিকানা</th>
						<th>N.B.</th>
						<th>বাঁকি</th>
						<th>টাকা প্রদানের অবস্থা</th>
						<th>বিক্রেতার নাম</th>
						<th>ব্যবস্থা</th>
					</tr>
				</thead>
			</table>

		<?php
			// /else manage order
		} else if ($_GET['o'] == 'editOrd') {
			// get order
		?>
			<div class="success-messages"></div> <!--/success-messages-->

			<form class="form-horizontal row" method="POST" action="php_action/reditOrder.php" id="editOrderForm">

				<?php $orderId = $_GET['i'];

				$sql = "SELECT orders.order_id, orders.order_date, orders.client_name, orders.client_contact, orders.sub_total, orders.vat, orders.total_amount, orders.discount, orders.grand_total, orders.paid, orders.due, orders.payment_type, orders.payment_status, orders.address, users.full_name, orders.o_feature, orders.sr_id FROM orders inner join users on
  			   users.user_id = orders.s_name
					WHERE orders.order_id = {$orderId}";

				$result = $connect->query($sql);
				$data = $result->fetch_row();
				$date = $data[1];
				$date = date_create("$date");
				$date = date_format($date, "d/m/Y");
				?>
				<?php $orderId = $_GET['i']; ?>
				<input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId; ?>" />
				<div class="col-sm-5 col-sm-offset-1">
					<div class="form-group order-group">
						<label for="orderDate" class="control-label">নিয়মিত ক্রেতা</label>
						<select class="form-control" name="nmbr" id="nmbr">
							<option value="">~~নির্বাচন করুন~~</option>
							<?php
							$productSql = "SELECT * FROM sr WHERE b_status=1";
							$productData = $connect->query($productSql);
							while ($row = $productData->fetch_array()) {
								$selected = ($row['sr_id'] == $data[16]) ? 'selected' : '';
								echo '<option value="' . $row['sr_id'] . '" ' . $selected . '>' . $row['name'] . ' (' . $row['nmbr'] . ')</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group order-group">
						<label for="orderDate" class="control-label">অর্ডারের তারিখ</label>
						<input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $date; ?>" />
					</div>
				</div> <!--/form-group-->
				<div class="col-sm-5 col-sm-offset-1">
					<div class="form-group order-group">
						<label for="clientName" class="control-label">ক্রেতার নাম</label>
						<input type="text" class="form-control" id="clientName" name="clientName" value="<?php echo $data[2] ?>" readonly />
					</div>
				</div>

				<div class="col-sm-5">
					<div class="form-group order-group">
						<label for="clientContact" class="control-label">ক্রেতার মোবাইল নাম্বার</label>
						<input type="text" class="form-control" id="clientContact" name="clientContact" value="<?php echo $data[3] ?>" readonly />
					</div>
				</div>

				<div class="col-sm-5 col-sm-offset-1">
					<div class="form-group order-group">
						<label for="clientaddress" class="control-label">ক্রেতার ঠিকানা</label>
						<input type="text" class="form-control" id="clientaddress" name="clientaddress" value="<?php echo $data[13] ?>" readonly />
					</div>
				</div>

				<div class="col-sm-5">
					<div class="form-group order-group">
						<label for="edit_o_feature" class="control-label">N.B. (Note Well)</label>
						<input type="text" class="form-control" id="edit_o_feature" name="edit_o_feature" value="<?php echo $data[15] ?>" placeholder="Note Well" />
					</div>
				</div> <!--/form-group-->
				<table class="table" id="productTable">
					<thead>
						<tr>
							<th>পণ্য</th>
							<th>ব্র্যান্ড</th>
							<th>দর</th>
							<th>পরিমান</th>
							<th>পরিমাপ</th>
							<th>দাম</th>
							<th><button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> সারি যুক্ত করুন </button></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$orderItemSql = "SELECT order_item.order_item_id, order_item.order_id, order_item.product_id, order_item.quantity, order_item.rate, order_item.total,brands.brand_name,product.clor FROM order_item  inner join product
						  on order_item.product_id = product.product_id
							inner join brands
							on product.brand_id = brands.brand_id
						   WHERE order_item.order_id = {$orderId}";
						$orderItemResult = $connect->query($orderItemSql);
						// $orderItemData = $orderItemResult->fetch_all();

						// print_r($orderItemData);
						$arrayNumber = 0;
						// for($x = 1; $x <= count($orderItemData); $x++) {
						$x = 1;
						while ($orderItemData = $orderItemResult->fetch_array()) {
							// print_r($orderItemData); 
						?>
							<tr id="row<?php echo $x; ?>" class="table-order <?php echo $arrayNumber; ?>">
								<td>
									<div class="form-group form-order">
										<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)">
											<option value="">~~নির্বাচন করুন~~</option>
											<?php
											$productSql = "SELECT * FROM product WHERE  status = 1 ";
											$productData = $connect->query($productSql);

											while ($row = $productData->fetch_array()) {
												$selected = "";
												if ($row['product_id'] == $orderItemData['product_id']) {
													$selected = "selected";
												} else {
													$selected = "";
												}

												echo "<option value='" . $row['product_id'] . "' id='changeProduct" . $row['product_id'] . "' " . $selected . " >" . $row['product_name'] . "</option>";
											} // /while

											?>
										</select>
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="text" name="brand[]" id="brand<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" value="<?php echo $orderItemData['brand_name']; ?>" />
										<input type="hidden" name="brand[]" id="brand<?php echo $x; ?>" autocomplete="off" class="form-control" />
									</div>
								</td>

								<td>
									<div class="form-group">
										<input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />
										<input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="0" value="<?php echo $orderItemData['quantity']; ?>" />
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="text" name="clor[]" id="clor<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" value="<?php echo $orderItemData['clor']; ?>" />
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" value="<?php echo $orderItemData['total']; ?>" />
										<input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['total']; ?>" />
									</div>
								</td>
								<td>
									<div class="form-group">
										<button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)" disabled>
											<i class="glyphicon glyphicon-trash"></i>
										</button>
									</div>
								</td>
							</tr>
						<?php
							$arrayNumber++;
							$x++;
						} // /for
						?>
					</tbody>
				</table>
				<div class="col-md-6">
					<div class="form-group">
						<label for="subTotal" class="col-sm-3 control-label">মোট</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" value="<?php echo $data[4] ?>" />
							<input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" value="<?php echo $data[4] ?>" />
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="vat" class="col-sm-3 control-label">ভ্যাট 0%</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="vat" name="vat" disabled="true" value="<?php echo $data[5] ?>" />
							<input type="hidden" class="form-control" id="vatValue" name="vatValue" value="<?php echo $data[5] ?>" />
						</div>
					</div> <!--/form-group-->
					<div class="">
						<div class="">
							<input type="hidden" class="form-control" id="totalAmount" name="totalAmount" disabled="true" value="<?php echo $data[6] ?>" />
							<input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" value="<?php echo $data[6] ?>" />
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="discount" class="col-sm-3 control-label">ছাড় (-)</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="<?php echo $data[7] ?>" />
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="grandTotal" class="col-sm-3 control-label">সর্বমোট</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" value="<?php echo $data[8] ?>" />
							<input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" value="<?php echo $data[8] ?>" />
						</div>
					</div> <!--/form-group-->
				</div> <!--/col-md-6-->

				<div class="col-md-6">
					<div class="form-group">
						<label for="paid" class="col-sm-3 control-label">পরিশোধ</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" value="<?php echo $data[9] ?>" readonly />
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="due" class="col-sm-3 control-label">বাঁকি</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="due" name="due" disabled="true" value="<?php echo $data[10] ?>" />
							<input type="hidden" class="form-control" id="dueValue" name="dueValue" value="<?php echo $data[10] ?>" />
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="clientContact" class="col-sm-3 control-label">পরিশোধের ধরন</label>
						<div class="col-sm-9">
							<select class="form-control" name="paymentType" id="paymentType" disabled>
								<option value="1" <?php if ($data[11] == 2) {
														echo "selected";
													} ?>>টাকা</option>
								<option value="2" <?php if ($data[11] == 1) {
														echo "selected";
													} ?>>চেক</option>
							</select>
							<!-- Add hidden input to ensure the value is submitted -->
							<input type="hidden" name="paymentType" value="<?php echo $data[11] == 2 ? '1' : '2'; ?>">
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="clientContact" class="col-sm-3 control-label">পরিশোধের অবস্থা</label>
						<div class="col-sm-9">
							<select class="form-control" name="paymentStatus" id="paymentStatus" disabled>
								<option value="1" <?php if ($data[12] == 1) {
														echo "selected";
													} ?>>পরিশোধ</option>
								<option value="3" <?php if ($data[12] == 3) {
														echo "selected";
													} ?>>বাঁকি</option>
							</select>
							<input type="hidden" name="paymentStatus" value="<?php echo $data[12]; ?>">
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="s_name" class="col-sm-3 control-label">বিক্রেতার নাম</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="paid" name="paid" autocomplete="off" value="<?php echo $data[14] ?>" readonly />
						</div>
					</div> <!--/form-group-->
				</div> <!--/col-md-6-->


				<div class="form-group editButtonFooter">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="hidden" name="orderId" id="orderId" value="<?php echo $_GET['i']; ?>" />

						<button type="submit" id="editOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>

					</div>
				</div>
			</form>

		<?php
		} // /get order else  
		?>


	</div> <!--/panel-->
</div> <!--/panel-->


<!-- edit order -->
<div class="modal fade" tabindex="-1" role="dialog" id="paymentOrderModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> পরিশোধের সম্পাদনা</h4>
			</div>

			<div class="modal-body form-horizontal" style="max-height:500px; overflow:auto;">

				<div class="paymentOrderMessages"></div>
				<div class="form-group">
					<div class="col-sm-9">
						<input type="hidden" class="form-control" id="sr_id" name="sr_id" />
					</div>
				</div> <!--/form-group-->
				<div class="form-group">
					<label for="due" class="col-sm-3 control-label">বাঁকি ৳</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="due" name="due" disabled="true" />
					</div>
				</div> <!--/form-group-->
				<div class="form-group">
					<label for="payAmount" class="col-sm-3 control-label">পরিশোধের পরিমান ৳</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="payAmount" name="payAmount" />
					</div>
				</div> <!--/form-group-->
				<div class="form-group">
					<label for="clientContact" class="col-sm-3 control-label">পরিশোধের ধরন</label>
					<div class="col-sm-9">
						<select class="form-control" name="paymentType" id="paymentType">
							<option value="2">টাকা</option>
							<option value="1">চেক</option>
						</select>
					</div>
				</div> <!--/form-group-->
				<div class="form-group">
					<label for="clientContact" class="col-sm-3 control-label">পরিশোধের অবস্থা</label>
					<div class="col-sm-9">
						<select class="form-control" name="paymentStatus" id="paymentStatus">
							<option value="1">পরিশোধ</option>
							<option value="3">বাঁকি</option>
						</select>
					</div>
				</div> <!--/form-group-->
			</div> <!--/modal-body-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
				<button type="button" class="btn btn-primary" id="updatePaymentOrderBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুণ</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /edit order-->

<!-- remove order -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeOrderModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> অর্ডার অপসারণ</h4>
			</div>
			<div class="modal-body">

				<div class="removeOrderMessages"></div>

				<p>আপনি কি সত্যিই অপসারণ করতে চান ?</p>
			</div>
			<div class="modal-footer removeProductFooter">
				<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
				<button type="button" class="btn btn-primary" id="removeOrderBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /remove order-->


<script src="custom/js/orders.js"></script>
<?php require_once 'includes/footer.php'; ?>