<?php require_once 'includes/header.php'; ?>

<div class="panel panel-default mt20">
    <div class="panel-heading">
		<i class="fa fa-calculator"></i> খরচ
	</div>
	<div class="panel-body">
	 <div class="remove-messages"></div>
	<?php if($_SESSION['Status']=='5' || $_SESSION['Status']=='2'){ ?>
		<div class="pull-right mb15">
			<button class="add-btn btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#addspend"><i class="glyphicon glyphicon-plus-sign"></i> খরচের খাত যোগ করুন</button>
			<?php } ?>
		</div>
		<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer text-center" id="manageinvocetable">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">খরচের খাত</th>
					<th class="text-center">তারিখ</th>
					<th class="text-center">মোট টাকা</th>
					<th class="text-center">পরিশোধ</th>
					<th class="text-center">বাঁকি</th>
					<th class="text-center">ব্যবস্থা</th>
				</tr>
			</thead> 
		</table> 
	</div>
</div>
<!--START ADD spend -->
<div id="addspend" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="submitspendForm" action="php_action/createSpend.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">খরচের হিসাব যুক্ত করুন</h4>
				</div>
				<div class="modal-body">
					<div id="add-spend-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="com_name" class="control-label">খরচের খাত : </label>
								<input type="text" class="form-control input-lg" id="com_name" name="com_name" placeholder="খরচের খাত" />
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="totalamount" class="control-label">মোট টাকা : </label>
								<input type="number" class="form-control input-lg" onkeyup="duecal()"  id="totalamount" name="totalamount" placeholder="মোট টাকা">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="totalamount" class="control-label">পরিশোধ : </label>
								<input type="number" onkeyup="dueFunc()" name="paid" id="paid" class="form-control input-lg" placeholder="পরিশোধ">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="totalamount" class="control-label">বাঁকি : </label>
							  <input type="number" class="form-control input-lg" id="due" value="0" readonly name="due" placeholder="বাঁকি">
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

<!--START EDIT SR PRODUCT -->
<div id="editspend" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editspendForm" action="php_action/editSpend.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">খরচের হিসাব সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
					<div id="edit-spend-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editcom_name" class="control-label">খরচের খাত : </label>
								<input type="text" class="form-control input-lg" id="editcom_name" name="editcom_name" placeholder="খরচের খাত" />
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="edit_totalamount" class="control-label">সর্বমোট টাকা : </label>
								<input type="number" class="form-control input-lg" id="edit_totalamount" name="edit_totalamount" onkeyup="editduecal()"  placeholder="সর্বমোট টাকা">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editpaid" class="control-label">পূর্বে পরিশোধ : </label>
								<input type="number"  name="editpaid" id="editpaid" class="form-control input-lg" READONLY placeholder="পূর্বে পরিশোধ">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editdue" class="control-label">বাঁকি : </label>
								<input type="number"  class="form-control input-lg" id="editdue" READONLY value="0" name="editdue" placeholder="বাঁকি">
							</div>
						</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<label for="paidnow" class="control-label">নতুন পরিশোধ : </label>
							<input type="number" value="0" class="form-control input-lg" id="paidnow" onkeyup="editdueFunc()" name="paidnow" placeholder="নতুন পরিশোধ">
						</div>
					</div>
					</div>
				</div>
				<div class="modal-footer srFooter  editspendFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" name="edutspend" id="editspendBtn" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--START REMOVE SR PRODUCT -->
<div id="removespend" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">খরচের হিসাব অপসারণ</h4>
				</div>
				<div class="modal-body">
					<p>আপনি কি সত্যিই এই খরচের হিসাব অপসারণ করতে চান?</p>
				</div>
				<div  class="modal-footer removespendFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="button" id="removespendBtn" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="custom/js/spend.js"></script>
<?php require_once 'includes/footer.php'; ?>
