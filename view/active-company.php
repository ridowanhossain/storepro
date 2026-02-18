<?php require_once 'includes/header.php'; ?>

<div class="panel panel-default mt20">
    <div class="panel-heading">
    	<i class="fa fa-calculator"></i> সক্রিয় কোম্পনীর তালিকা
	</div>
	<div class="panel-body">
	 <div class="remove-sr-messages"></div>
		<div class="pull-right mb15">
			<button class="add-btn btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#addSrProduct"><i class="glyphicon glyphicon-plus-sign"></i> কোম্পানী যোগ করুন</button>
		</div>
		<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer text-center" id="managesrtable">
			<thead>
				<tr>
					<th class="text-center">আইডি</th>
					<th class="text-center">নাম</th>
					<th class="text-center">কোম্পানী যুক্ত করার তারিখ</th>
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
			<form id="submitsrForm" action="php_action/creatCompany.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">কোম্পানী যুক্ত করুন</h4>
				</div>
				<div class="modal-body">
					<div id="add-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="brand" class="control-label">কোম্পানীর নাম : </label>
								<input type="text" name="brand" id="brandName" class="form-control input-lg" placeholder="কোম্পানীর নাম">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="user" class="control-label">অবস্থা : </label>
			  					 <select class="form-control input-lg" id="user-role" name="user">
							      	<option value="">~~নির্বাচন করুন~~</option>
							      	<option value="1">সক্রিয়  কোম্পানী</option>
						      	  <option value="2">নিষ্ক্রিয় কোম্পানী</option>
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
<div id="editCompany" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editsrForm" action="php_action/editcompany.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">কোম্পানী সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
				<div id="edit-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editBrandName" class="control-label">কোম্পানীর নাম : </label>
								<input type="text" name="editBrandName" id="editBrandName" class="form-control input-lg" placeholder="কোম্পানীর নাম">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="editsrsrname" class="control-label">অবস্থা : </label>
		  						<select class="form-control input-lg" id="editsrsrname" name="editsrsrname">
						      	<option value="">~~নির্বাচন করুন~~</option>
						      	<option value="1">সক্রিয়  করুন</option>
						      	<option value="2">নিষ্ক্রিয় করুন</option>
			             </select>
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
					<h4 class="modal-title">এস. আর. পণ্য অপসারণ</h4>
				</div>
				<div class="modal-body">
					<p>আপনি কি সত্যিই এই এস. আর. পণ্যটি অপসারণ করতে চান?</p>
				</div>
				<div  class="modal-footer removesrFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="button" id="removesrBtn" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="custom/js/company.js"></script>

<?php require_once 'includes/footer.php'; ?>
