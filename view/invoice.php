<?php require_once 'includes/header.php'; ?>

<div class="panel panel-default mt20">
    <div class="panel-heading">
    	<i class="fa fa-calculator"></i> চালান তালিকা
	</div>
	<div class="panel-body">
	 <div class="remove-sr-messages"></div>
		<div class="pull-right mb15">
			<button class="add-btn btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#addSrProduct"><i class="glyphicon glyphicon-plus-sign"></i> চালান যুক্ত করুন</button>
		</div>
		<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer text-center" id="managesrtable">
			<thead>
				<tr>
					<th class="text-center">আইডি</th>
					<th class="text-center">কোম্পানীর নাম</th>
					<th class="text-center">পণ্যের নাম</th>
					<th class="text-center">মোট টাকা</th>
					<th class="text-center">পরিশোধ</th>
					<th class="text-center">বাঁকি</th>
					<th class="text-center">ক্রয়ের তারিখ</th>
					<th class="text-center">ব্যবস্থা</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<!--START ADD SR PRODUCT -->
<div id="addSrProduct" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="submitsrForm" action="php_action/creatInvoice.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">চালান যুক্ত করুন</h4>
				</div>
				<div class="modal-body">
					<div id="add-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
						  <div class="form-group">
						  		<label for="brand" class="control-label">কোম্পানীর নাম : </label>
			  					 <select class="form-control input-lg" id="brandName" name="brand">
							      	<option value="">~~নির্বাচন করুন~~</option>
							      <?php
				  							$productSql = "SELECT * FROM company WHERE activity = 1 AND status = 1 ";
				  							$productData = $connect->query($productSql);

				  							while($row = $productData->fetch_array()) {
				  								echo "<option value='".$row['company_id']."''>".$row['name']."</option>";
											 	} // /while

				  						?>
				             </select>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="p_name" class="control-label">পণ্যের নাম ও তথ্য : </label>
								<input type="text" name="p_name" id="p_name" class="form-control input-lg" placeholder="পণ্যের নাম ও তথ্য">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="product" class="control-label">মোট : </label>
								<input type="number" name="product" onkeyup="duecal()"  id="totalamount" class="form-control input-lg" placeholder="মোট" value="0">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="srquantity" class="control-label">পরিশোধ : </label>
								<input type="number" name="srquantity"  class="form-control input-lg" onkeyup="dueFunc()"  id="paid" placeholder="পরিশোধ" value="0">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<label for="user" class="control-label">বাঁকি : </label>
			  					 <input type="number" name="user" id="due" class="form-control input-lg" readonly placeholder="বাঁকি" value="0">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" id="createsrBtn" name="adduser" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--START EDIT SR PRODUCT -->
<div id="editinvoice" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editsrForm" action="php_action/editinvoice.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">চালান সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
				<div id="edit-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
						  <div class="form-group">
						  		<label for="com_name" class="control-label">কোম্পানীর নাম : </label>
			  					 <select class="form-control input-lg" id="editcompany" name="editcompany">
							      	<option value="">~~নির্বাচন করুন~~</option>
							      <?php
				  							$productSql = "SELECT * FROM company WHERE activity = 1 AND status = 1 ";
				  							$productData = $connect->query($productSql);

				  							while($row = $productData->fetch_array()) {
				  								echo "<option value='".$row['company_id']."''>".$row['name']."</option>";
											 	} // /while

				  						?>
				             </select>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editp_name" class="control-label">পণ্যের নাম ও তথ্য : </label>
								<input type="text" name="editp_name" id="editp_name" class="form-control input-lg" placeholder="পণ্যের নাম ও তথ্য">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="edit_totalamount" class="control-label">মোট : </label>
								<input type="number" onkeyup="editduecal()" name="edit_totalamount" id="edit_totalamount" class="form-control input-lg" placeholder="মোট" readonly>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editpaid" class="control-label">পরিশোধ : </label>
								<input type="number" name="editpaid" id="editpaid" class="form-control input-lg" placeholder="পরিশোধ" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editdue" class="control-label">বাঁকি : </label>
			  					 <input type="number" name="editdue" id="editdue" class="form-control input-lg" placeholder="বাঁকি" readonly>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="paidnow" class="control-label">পরিশোধ করুন : </label>
							  	<input type="number" value="0" class="form-control input-lg" id="paidnow" onkeyup="editdueFunc()" 
							  name="paidnow" placeholder="পরিশোধ করুন">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer srFooter  editsrFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" name="adduser" id="editsrBtn" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!--START REMOVE SR PRODUCT -->
<div id="removeSrProduct" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">চালান অপসারণ</h4>
				</div>
				<div class="modal-body">
					<p>আপনি কি সত্যিই এই চালানটি অপসারণ করতে চান?</p>
				</div>
				<div  class="modal-footer removesrFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="button" id="removesrBtn" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="custom/js/invoice.js"></script>

<?php require_once 'includes/footer.php'; ?>
