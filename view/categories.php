<?php require_once 'includes/header.php'; ?>

<div class="row">
	<ol class="breadcrumb">
		<li><a href="dashboard">ড্যাসবোর্ড</a></li>		  
		<li class="active">শ্রেণী</li>
	</ol>

	<div class="main-table">
		<div class="product-table-card">
			<div class="table-title">
				<i class="glyphicon glyphicon-edit"></i> <span>শ্রেণী পরিচালনা</span>
			</div>
			
				<div class="remove-messages"></div>

				<div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
					<button class="btn btn-modern" data-toggle="modal" id="addCategoriesModalBtn" data-target="#addCategoriesModal"> <i class="glyphicon glyphicon-plus-sign"></i> শ্রেণী যুক্ত করুন </button>
				</div>
				
				<table class="table table-bordered modern-table dataTable no-footer dtr-inline" id="manageCategoriesTable">
					<thead>
						<tr>							
							<th class="text-center">ক্রমিক</th>
							<th class="text-center">আইডি</th>
							<th class="text-center">শ্রেণীর নাম</th>
							<th class="text-center">অবস্থা</th>
							<?php if($_SESSION['Status']=='5' ){ ?>
							<th class="text-center" style="width:15%;">ব্যবস্থা</th>
							<?php } ?>
						</tr>
					</thead>
				</table>
		</div>
	</div>
</div>


<!-- add categories -->
<div class="modal fade" id="addCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

    	<form class="form-horizontal" id="submitCategoriesForm" action="php_action/createCategories.php" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> শ্রেণী যুক্ত করুন</h4>
	      </div>
	      <div class="modal-body">

	      	<div id="add-categories-messages"></div>

	        <div class="form-group">
	        	<label for="categoriesName" class="col-sm-4 control-label">শ্রেণীর নাম: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-7">
				      <input type="text" class="form-control" id="categoriesName" placeholder="Categories Name" name="categoriesName" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	         	        
	        <div class="form-group">
	        	<label for="categoriesStatus" class="col-sm-4 control-label">অবস্থা: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-7">
				      <select class="form-control" id="categoriesStatus" name="categoriesStatus" disabled>
				      	<option value="1">সক্রিয়</option>
				      </select>
                      <input type="hidden" name="categoriesStatus" value="1">
				    </div>
	        </div> <!-- /form-group-->	         	        
	      </div> <!-- /modal-body -->
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
	        
	        <button type="submit" class="btn btn-success" id="createCategoriesBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /.form -->	     
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->
</div> 
<!-- /add categories -->


<!-- edit categories brand -->
<div class="modal fade" id="editCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	
    	<form class="form-horizontal" id="editCategoriesForm" action="php_action/editCategories.php" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> শ্রেণী সম্পাদনা</h4>
	      </div>
	      <div class="modal-body">

	      	<div id="edit-categories-messages"></div>

	      	<div class="modal-loading div-hide" style="width:50px; margin:auto;padding-top:50px; padding-bottom:50px;">
						<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">লোড হচ্ছে...</span>
					</div>

		      <div class="edit-categories-result">
		      	<div class="form-group">
		        	<label for="editCategoriesName" class="col-sm-4 control-label">শ্রেণীর নাম: </label>
		        	<label class="col-sm-1 control-label">: </label>
					    <div class="col-sm-7">
					      <input type="text" class="form-control" id="editCategoriesName" placeholder="Categories Name" name="editCategoriesName" autocomplete="off">
					    </div>
		        </div> <!-- /form-group-->	         	        
		        <div class="form-group">
		        	<label for="editCategoriesStatus" class="col-sm-4 control-label">অবস্থা: </label>
		        	<label class="col-sm-1 control-label">: </label>
					    <div class="col-sm-7">
					      <select class="form-control" id="editCategoriesStatus" name="editCategoriesStatus">
					      	<option value="1">সক্রিয়</option>
					      	<option value="2">নিষ্ক্রিয়</option>
					      </select>
					    </div>
		        </div> <!-- /form-group-->	 
		      </div>         	        
		      <!-- /edit brand result -->

	      </div> <!-- /modal-body -->
	      
	      <div class="modal-footer editCategoriesFooter">
	        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
	        
	        <button type="submit" class="btn btn-success" id="editCategoriesBtn" data-loading-text="লোড হচ্ছে..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
	      </div>
	      <!-- /modal-footer -->
     	</form>
	     <!-- /.form -->
    </div>
    <!-- /modal-content -->
  </div>
  <!-- /modal-dailog -->
</div>
<!-- /categories brand -->

<!-- categories brand -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeCategoriesModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> শ্রেণী অপসারণ</h4>
      </div>
      <div class="modal-body">
        <p>আপনি কি সত্যিই এই শ্রেণীটি অপসারণ করতে চান ?</p>
      </div>
      <div class="modal-footer removeCategoriesFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
        <button type="button" class="btn btn-danger" id="removeCategoriesBtn" data-loading-text="লোড হচ্ছে..."> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /categories brand -->

 </div>
    <!-- /#wrapper -->
<script src="custom/js/categories.js?v=<?php echo filemtime('custom/js/categories.js'); ?>"></script>

<?php require_once 'includes/footer.php'; ?>