<?php require_once 'includes/header.php'; ?>

<div class="panel panel-default mt20">
    <div class="panel-heading">
    	<i class="fa fa-calculator"></i> সক্রিয় ক্রেতার তালিকা
	</div>
	<div class="panel-body">
	 <div class="remove-sr-messages"></div>
          <div class="pull-left"><a type="button" onclick="printOrder('3')" class="btn btn-primary"> <i class="glyphicon glyphicon-print"></i> প্রিন্ট </a></div>
		<div class="pull-right mb15">
			<button class="add-btn btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#addSrProduct"><i class="glyphicon glyphicon-plus-sign"></i> ক্রেতা যোগ করুন</button>
		</div>
		<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer text-center" id="managesrtable">
			<thead>
				<tr>
					<th class="text-center">আইডি</th>
					<th class="text-center">নাম</th>
					<th class="text-center">মোবাইল নাম্বার</th>
					<th class="text-center">ঠিকানা</th>
					<th class="text-center">ক্রেতা যুক্ত করার তারিখ</th>
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
			<form id="submitsrForm" action="php_action/creatSr.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">ক্রেতা যুক্ত করুন</h4>
				</div>
				<div class="modal-body">
					<div id="add-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="brand" id="brandName" class="form-control input-lg" placeholder="নাম">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="product" id="user-role" class="form-control input-lg" placeholder="নাম্বার">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="srquantity" id="srquantity" class="form-control input-lg" placeholder="ঠিকানা">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
			  					 <select class="form-control" id="user-role" name="user">
							      	<option value="">~~নির্বাচন করুন~~</option>
							      	<option value="1">সক্রিয়  ক্রেতা</option>
						      	  <option value="2">নিষ্ক্রিয় ক্রেতা</option>
				             </select>
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
<div id="editSrProduct" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editsrForm" action="php_action/editsr.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">ক্রেতা পণ্য সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
				<div id="edit-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="editBrandName" id="editBrandName" class="form-control input-lg" placeholder="নাম">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
							<input type="text" name="editproduct" id="editproduct" class="form-control input-lg" placeholder="নাম">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="editqty" id="editqty" class="form-control input-lg" placeholder="পরিমাণ">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
		  						<select class="form-control" id="editsrsrname" name="editsrsrname">
						      	<option value="">~~নির্বাচন করুন~~</option>
						      	<option value="1">সক্রিয়  করুন</option>
						      	<option value="2">নিষ্ক্রিয় করুন</option>
			             </select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer ">
					<div class="modal-footer srFooter  editsrFooter">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
						<button type="submit" name="adduser" id="editsrBtn" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
					</div>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!--Previous Due-->
<div id="editprevdue" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editprevForm" action="php_action/editprevdue.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">ক্রেতা পণ্য সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
				<div id="edit-prev-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="ptdue" id="ptdue" class="form-control input-lg" placeholder="নাম">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
							<input type="text" name="ptpaid" id="ptpaid" class="form-control input-lg"  onkeyup="pcalFunc()"   placeholder="নাম">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="tdue" id="tdue" class="form-control input-lg" readonly placeholder="পরিমাণ">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" value="0"  name="paidnow" id="paidnow" class="form-control input-lg" onkeyup="pdueFunc()" placeholder="পরিশোধ করুন">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer ">
					<div class="modal-footer srFooter  editprevduefotter">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
						<button type="submit" name="adduser" id="prevdueBtn" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
					</div>
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
					<h4 class="modal-title">ক্রেতা অপসারণ</h4>
				</div>
				<div class="modal-body">
					<p>আপনি কি সত্যিই ক্রেতা অপসারণ করতে চান?</p>
				</div>
				<div  class="modal-footer removesrFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="button" id="removesrBtn" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="custom/js/sr.js"></script>

<?php require_once 'includes/footer.php'; ?>
