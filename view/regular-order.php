<?php
require_once 'php_action/db_connect.php';
require_once 'includes/header.php';

if ($_GET['o'] == 'add') {
	// add order
	echo "<div class='div-request div-hide'>add</div>";
} else if ($_GET['o'] == 'manord') {
	echo "<div class='div-request div-hide'>manord</div>";
} else if ($_GET['o'] == 'editOrd') {
	echo "<div class='div-request div-hide'>editOrd</div>";
} // /else manage order
?>

<ol class="breadcrumb">
	<li><a href="dashboard">ড্যাসবোর্ড</a></li>
	<li>অর্ডার</li>
	<li class="active">
		<?php if ($_GET['o'] == 'add') { ?>
			নিয়মিত অর্ডার
		<?php } else if ($_GET['o'] == 'manord') { ?>
			নিয়মিত  অর্ডার পরিচালনা
		<?php } // /else manage order 
		?>
	</li>
</ol>

<style>
/* Fix Select2 height and style to match Bootstrap form-control */
.select2-container .select2-selection--single {
    height: 34px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 32px !important;
    padding-left: 12px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 32px !important;
    right: 5px !important;
}
</style>

<div class="main-table">
	<div class="product-table-card">
		<div class="table-title">
			<?php if ($_GET['o'] == 'add') { ?>
				<i class="glyphicon glyphicon-plus-sign"></i> <span>নিয়মিত অর্ডার</span>
			<?php } else if ($_GET['o'] == 'manord') { ?>
				<i class="glyphicon glyphicon-edit"></i> <span>নিয়মিত অর্ডার পরিচালনা</span>
			<?php } else if ($_GET['o'] == 'editOrd') { ?>
				<i class="glyphicon glyphicon-edit"></i> <span>নিয়মিত অর্ডার সম্পাদনা</span>
			<?php } ?>
		</div>
	
			<?php if ($_GET['o'] == 'add') { ?>
				<div class="success-messages"></div>
	
				<form class="form-horizontal" method="POST" action="php_action/createOrder.php" id="createOrderForm">
					<!-- Top Form Fields -->
					<div class="col-sm-5 col-sm-offset-1">
						<div class="form-group order-group">
							<label for="nmbr" class="control-label">নিয়মিত ক্রেতা</label>
							<select class="form-control select2" name="nmbr" id="nmbr">
								<option value="">~~নির্বাচন করুন~~</option>
								<?php
								$productSql = "SELECT * FROM sr WHERE b_status=1";
								$productData = $connect->query($productSql);
								while ($row = $productData->fetch_array()) {
									echo '<option value="' . $row['sr_id'] . '">' . $row['name'] . ' (' . $row['nmbr'] . ')</option>';
								}
								?>
							</select>
						</div>
					</div>

					<div class="col-sm-5">
						<div class="form-group order-group">
							<label for="orderDate" class="control-label">অর্ডারের তারিখ</label>
							<input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" />
						</div>
					</div>

					<div class="col-sm-5 col-sm-offset-1">
						<div class="form-group order-group">
							<label for="clientName" class="control-label">ক্রেতার নাম</label>
							<input type="text" class="form-control" id="clientName" name="clientName" readonly />
						</div>
					</div>

					<div class="col-sm-5">
						<div class="form-group order-group">
							<label for="clientContact" class="control-label">ক্রেতার মোবাইল নাম্বার</label>
							<input type="text" class="form-control" id="clientContact" name="clientContact" readonly />
						</div>
					</div>

					<div class="col-sm-5 col-sm-offset-1">
						<div class="form-group order-group">
							<label for="clientaddress" class="control-label">ক্রেতার ঠিকানা</label>
							<input type="text" class="form-control" id="clientaddress" name="clientaddress" readonly />
						</div>
					</div>
					<input type="hidden" id="sr_id" name="sr_id" />

					<div class="col-sm-10 col-sm-offset-1">
						<div class="form-group order-group">
							<label for="o_feature" class="control-label">N.B. (Note Well)</label>
							<input type="text" class="form-control" id="o_feature" name="o_feature" value="N/A" />
						</div>
					</div>
	
					<table class="table table-bordered modern-table dataTable no-footer dtr-inline" id="productTable" style="margin-top: 30px;">
						<thead>
							<tr>
								<th>পণ্য</th>
								<th>ব্র্যান্ড</th>
								<th>দর</th>
								<th>পরিমাণ</th>
								<th>পরিমাপ</th>
								<th>দাম</th>
								<th class="text-center"><button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn"> <i class="glyphicon glyphicon-plus-sign"></i> পণ্য যুক্ত করুন </button></th>
							</tr>
						</thead>
						<tbody>
							<?php
							for ($x = 1; $x < 2; $x++) { ?>
								<tr id="row<?php echo $x; ?>">
									<td>
										<div class="form-group" style="margin: 0;">
											<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)">
												<option value="">~~নির্বাচন করুন~~</option>
												<?php
												$productSql = "SELECT * FROM product WHERE active = 1 AND status = 1 AND quantity != 0";
												$productData = $connect->query($productSql);
												while ($row = $productData->fetch_array()) {
													echo "<option value='" . $row['product_id'] . "'>" . $row['product_name'] . "</option>";
												}
												?>
											</select>
										</div>
									</td>
									<td><input type="text" name="brand[]" id="brand<?php echo $x; ?>" class="form-control" disabled /></td>
									<td>
										<input type="number" name="rate[]" id="rate<?php echo $x; ?>" class="form-control" step="0.01" onkeyup="syncRateValue(<?php echo $x; ?>); getTotal(<?php echo $x; ?>);" />
										<input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" />
									</td>
									<td><input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" class="form-control" step="any" oninput="getTotal(<?php echo $x ?>);" /></td>
									<td><input type="text" name="clor[]" id="clor<?php echo $x; ?>" class="form-control" disabled /></td>
									<td>
										<input type="text" name="total[]" id="total<?php echo $x; ?>" class="form-control" disabled />
										<input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" />
									</td>
									<td><button class="btn btn-default" type="button" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
	
					<div class="row" style="margin-top: 30px;">
						<div class="col-md-6">
							<div class="form-group">
								<label for="subTotal" class="col-sm-3 control-label">মোট</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" />
									<input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" />
								</div>
							</div> <!--/form-group-->
							<div class="form-group">
								<label for="vat" class="col-sm-3 control-label">ভ্যাট 0%</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="vat" name="vat" disabled="true" />
									<input type="hidden" class="form-control" id="vatValue" name="vatValue" />
								</div>
							</div> <!--/form-group-->
							<div class="">
								<div class="">
									<input type="hidden" class="form-control" id="totalAmount" name="totalAmount" disabled="true" />
									<input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" />
								</div>
							</div> <!--/form-group-->
							<div class="form-group">
								<label for="discount" class="col-sm-3 control-label">ছাড় (-)</label>
								<div class="col-sm-9">
									<input type="number" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="0" step="0.01" min="0"/>
								</div>
							</div> <!--/form-group-->
							<div class="form-group">
								<label for="grandTotal" class="col-sm-3 control-label">সর্বমোট</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" />
									<input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" />
								</div>
							</div> <!--/form-group-->
						</div> <!--/col-md-6-->
	
						<div class="col-md-6">
							<div class="form-group">
								<label for="paid" class="col-sm-3 control-label">পরিশোধ</label>
								<div class="col-sm-9">
									<input type="number" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" value="0" step="0.01" min="0"/>
								</div>
							</div> <!--/form-group-->
							<div class="form-group">
								<label for="due" class="col-sm-3 control-label">বাঁকি</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="due" name="due" disabled="true" />
									<input type="hidden" class="form-control" id="dueValue" name="dueValue" />
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
									<select class="form-control" name="paymentStatus" id="paymentStatus" disabled>
										<option value="3">বাঁকি</option>
										<option value="1">পরিশোধ</option>
									</select>
									<input type="hidden" name="paymentStatus" value="3">
								</div>
							</div> <!--/form-group-->
	
							<div class="form-group">
								<label for="sellername" class="col-sm-3 control-label">বিক্রেতার নাম</label>
								<div class="col-sm-9">
									<input class="form-control" type="text" name="sellername" id="sellername" value="<?php echo $_SESSION['Fullname']; ?>" readonly>
									<input class="form-control" type="hidden" name="sellerid" id="sellerid" value="<?php echo $_SESSION['userId']; ?>" readonly>
								</div>
							</div> <!--/form-group-->
						</div> <!--/col-md-6-->
					</div>
	
					<div class="form-group" style="margin-top: 20px;">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" id="createOrderBtn" class="btn btn-modern"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
							<button type="reset" class="btn btn-reset" onclick="resetOrderForm()"><i class="glyphicon glyphicon-erase"></i> পুনঃস্থাপন</button>
						</div>
					</div>
				</form>
			<?php } ?>
	</div>
</div>

<script src="custom/js/order.js?v=<?php echo filemtime('custom/js/order.js'); ?>"></script>
<script>
function syncRateValue(x) {
  var rateInput = document.getElementById('rate' + x);
  var rateValueInput = document.getElementById('rateValue' + x);
  if (rateInput && rateValueInput) rateValueInput.value = rateInput.value;
}

$(document).ready(function() {
    $('.select2').select2({ width: '100%' });
    $('#nmbr').on('change', function() {
        var sr_id = $(this).val();
        if(sr_id) {
            $.ajax({
                url: "php_action/fetchdistrict.php",
                method: "POST",
                dataType: "json",
                data: { division_id: sr_id },
                success: function(data) {
                    $('#clientName').val(data.name);
                    $('#clientContact').val(data.nmbr);
                    $('#clientaddress').val(data.address);
                    $('#sr_id').val(data.sr_id);
                }
            });
        }
    });
});
</script>
<?php require_once 'includes/footer.php'; ?>
