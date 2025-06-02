<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
    	  <li><a href="dashboard"> ড্যাসবোর্ড</a></li>
		  <li class="active"> সক্রিয় পণ্য</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> সক্রিয় পণ্য পরিচালনা</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">

				<div class="remove-messages"></div>
				<div class="pull-left"><a type="button" onclick="printOrder('1')" class="btn btn-primary"> <i class="glyphicon glyphicon-print"></i> প্রিন্ট </a></div>
				<?php if($_SESSION['Status']=='5' || $_SESSION['Status']=='2') { ?>
				<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal"> <i class="glyphicon glyphicon-plus-sign"></i> পণ্য যুক্ত করুন </button><?php } ?>
				</div> <!-- /div-action -->

				<table class="table table-bordered table-condensed table-hover table-striped dataTable" id="manageProductTable">
					<thead>
						<tr>
							<th>ক্রমিক</th>
							<th style="width:10%;">ছবি</th>
							<th>কোড</th>
							<th>ব্র্যান্ড</th>
							<th>পণ্যের নাম</th>
							<th>পণ্যের শ্রেণী</th>
							<th>বৈশিষ্ট্য</th>
							<th>ক্রয়মূল্য</th>
							<th>বিক্রয়মূল্য</th>
							<th>পরিমাণ</th>
							<th>পরিমাপ</th>
							<th>অবস্থা</th>
							<?php if($_SESSION['Status']=='5' || $_SESSION['Status']=='2') { ?>
							<th>ব্যবস্থা</th>
							<?php } ?>
						</tr>
					</thead>
				</table>
				<!-- /table -->

			</div> <!-- /panel-body -->
		</div> <!-- /panel -->
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->


<!-- add product -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

    	<form class="form-horizontal" id="submitProductForm" action="php_action/createProduct.php" method="POST" enctype="multipart/form-data">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> সক্রিয় পণ্য সংযুক্ত করুন</h4>
	      </div>

	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div id="add-product-messages"></div>

	      	<div class="form-group">
	        	<label for="productImage" class="col-sm-3 control-label">পণ্যের ছবি </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
					    <!-- the avatar markup -->
							<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
					    <div class="kv-avatar center-block">
					        <input type="file" class="form-control" id="productImage" placeholder="পণ্যের নাম" name="productImage" class="file-loading" style="width:auto;"/>
					    </div>

				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="productName" class="col-sm-3 control-label">পণ্যের নাম </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="productName" placeholder="পণ্যের নাম" name="productName" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="quantity" class="col-sm-3 control-label">পরিমাণ </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="quantity" placeholder="পরিমাণ" name="quantity" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="clor" class="col-sm-3 control-label">পরিমাপ </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="clor" name="clor">
				      	<option value="ব্যাগ">ব্যাগ</option>
				      	<option value="কেজি">কেজি</option>
				      	<option value="টি">টি</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="brate" class="col-sm-3 control-label">ক্রয়মূল্য </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="brate" placeholder="ক্রয়মূল্য" name="brate" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="rate" class="col-sm-3 control-label">বিক্রয়মূল্য </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="rate" placeholder="বিক্রয়মূল্য" name="rate" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="brandName" class="col-sm-3 control-label">ব্র্যান্ডের নাম </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="brandName" name="brandName">
				      	<option value="">~~নির্বাচন করুন~~</option>
				      	<?php
				      	$sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1 AND brand_active = 1";
								$result = $connect->query($sql);

								while($row = $result->fetch_array()) {
									echo "<option value='".$row[0]."'>".$row[1]."</option>";
								} // while

				      	?>
				      </select>
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="categoryName" class="col-sm-3 control-label">শ্রেণির নাম </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select type="text" class="form-control" id="categoryName" placeholder="পণ্যের নাম" name="categoryName" >
				      	<option value="">~~নির্বাচন করুন~~</option>
				      	<?php
				      	$sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
								$result = $connect->query($sql);

								while($row = $result->fetch_array()) {
									echo "<option value='".$row[0]."'>".$row[1]."</option>";
								} // while

				      	?>
				      </select>
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="productStatus" class="col-sm-3 control-label">অবস্থা </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="productStatus" name="productStatus" disabled>
				        <option value="1" selected>সক্রিয়</option>
				        <option value="2">নিষ্ক্রিয়</option>
				      </select>
				      <input type="hidden" name="productStatus" value="1">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
	        	<label for="feature" class="col-sm-3 control-label">বৈশিষ্ট্য </label>
	        	 <label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				     <textarea name="feature" id="feature" cols="30" rows="10"></textarea>
				    </div>
	        </div> <!-- /form-group-->
	      </div> <!-- /modal-body -->

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>

	        <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
	      </div> <!-- /modal-footer -->
     	</form> <!-- /.form -->
    </div> <!-- /modal-content -->
  </div> <!-- /modal-dailog -->
</div>
<!-- /add categories -->


<!-- edit categories -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> সক্রিয় পণ্য সম্পাদনা</h4>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div class="div-loading">
	      		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">লোড হচ্ছে...</span>
	      	</div>

	      	<div class="div-result">

				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#photo" aria-controls="home" role="tab" data-toggle="tab">ছবি</a></li>
				    <li role="presentation"><a href="#productInfo" aria-controls="profile" role="tab" data-toggle="tab">পণ্যের তথ্য</a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">


				    <div role="tabpanel" class="tab-pane active" id="photo">
				    	<form action="php_action/editProductImage.php" method="POST" id="updateProductImageForm" class="form-horizontal" enctype="multipart/form-data">

				    	<br />
				    	<div id="edit-productPhoto-messages"></div>

				    	<div class="form-group">
			        	<label for="editProductImage" class="col-sm-3 control-label">পণ্যের ছবি </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <img src="" id="getProductImage" class="thumbnail" style="width:250px; height:250px;" onerror="this.onerror=null; this.src='assests/images/product.jpg';" />
						    </div>
			        </div> <!-- /form-group-->

			      	<div class="form-group">
			        	<label for="editProductImage" class="col-sm-3 control-label">ছবি নির্বাচন </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
							    <!-- the avatar markup -->
									<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
							    <div class="kv-avatar center-block">
							        <input type="file" class="form-control" id="editProductImage" placeholder="Product Name" name="editProductImage" class="file-loading" style="width:auto;"/>
							    </div>

						    </div>
			        </div> <!-- /form-group-->

			        <div class="modal-footer editProductPhotoFooter">
				        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>

				        <!-- <button type="submit" class="btn btn-success" id="editProductImageBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button> -->
				      </div>
				      <!-- /modal-footer -->
				      </form>
				      <!-- /form -->
				    </div>
				    <!-- product image -->
				    <div role="tabpanel" class="tab-pane" id="productInfo">
				    	<form class="form-horizontal" id="editProductForm" action="php_action/editProduct.php" method="POST">
				    	<br />

				    	<div id="edit-product-messages"></div>

				    	<div class="form-group">
			        		<label for="editProductName" class="col-sm-3 control-label">পণ্যের নাম</label>
	        	 			<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editProductName" placeholder="পণ্যের নাম" name="editProductName" autocomplete="off" <?php  if($_SESSION['Status']!=='5'){echo'readonly';} ?>>
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
			        	<label for="editQuantity" class="col-sm-3 control-label">বর্তমান পরিমাণ </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editQuantity" placeholder="বর্তমান পরিমাণ" name="editQuantity" autocomplete="off" readonly>
						    </div>
			        </div> <!-- /form-group-->

			          <div class="form-group">
			        	<label for="addpro" class="col-sm-3 control-label">পরিমাণ যোগ করুন </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="addpro" placeholder="পরিমাণ যোগ করুন" name="addpro" autocomplete="off" value="0">
						    </div>
			        </div> <!-- /form-group-->

		        <div class="form-group">
			        	<label for="editclor" class="col-sm-3 control-label">পরিমাপ </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select class="form-control" id="editclor" name="editclor" <?php  if($_SESSION['Status']!=='5'){echo'readonly';} ?>>
						      	<option value="ব্যাগ">ব্যাগ</option>
						      	<option value="কেজি">কেজি</option>
						      	<option value="টি">টি</option>
						      </select>
						    </div>

			        </div> <!-- /form-group-->
				 	<div class="form-group">
			        	<label for="editbRate" class="col-sm-3 control-label">ক্রয়মূল্য </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editbRate" placeholder="ক্রয়মূল্য" name="editbRate" autocomplete="off"<?php  if($_SESSION['Status']!=='5'){echo'readonly';} ?>>
						    </div>
			        </div>


			        <div class="form-group">
			        	<label for="editRate" class="col-sm-3 control-label">বিক্রয়মূল্য </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editRate" placeholder="বিক্রয়মূল্য" name="editRate" autocomplete="off"<?php  if($_SESSION['Status']!=='5'){echo'readonly';} ?>>
						    </div>
			        </div> <!-- /form-group-->


			        <div class="form-group">
			        	<label for="editBrandName" class="col-sm-3 control-label">ব্র্যান্ডের নাম </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select class="form-control" id="editBrandName" name="editBrandName" <?php  if($_SESSION['Status']!=='5'){echo'readonly';} ?>>
						      	<option value="">~~নির্বাচন করুন~~</option>
						      	<?php
						      	$sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1 AND brand_active = 1";
										$result = $connect->query($sql);

										while($row = $result->fetch_array()) {
											echo "<option value='".$row[0]."'>".$row[1]."</option>";
										} // while

						      	?>
						      </select>
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
			        	<label for="editCategoryName" class="col-sm-3 control-label">শ্রেণীর নাম </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select type="text" class="form-control" id="editCategoryName" name="editCategoryName" <?php  if($_SESSION['Status']!=='5'){echo'readonly';} ?>>
						      	<option value="">~~নির্বাচন করুন~~</option>
						      	<?php
						      	$sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
										$result = $connect->query($sql);

										while($row = $result->fetch_array()) {
											echo "<option value='".$row[0]."'>".$row[1]."</option>";
										} // while

						      	?>
						      </select>
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
			        	<label for="editProductStatus" class="col-sm-3 control-label">অবস্থা </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select class="form-control" id="editProductStatus" name="editProductStatus">
						      	<option value="1">সক্রিয়</option>
						      	<option value="2">নিষ্ক্রিয়</option>
						      </select>
						    </div>
			        </div> <!-- /form-group-->

	      	<div class="form-group">
	        		<label for="editfeature" class="col-sm-3 control-label">বৈশিষ্ট্য </label>
	        	 	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				     <textarea <?php  if($_SESSION['Status']!=='5'){echo'readonly';} ?> name="editfeature" id="editfeature" cols="30" rows="10"></textarea>
				    </div>
	        </div> <!-- /form-group-->

			        <div class="modal-footer editProductFooter">
				        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>

				        <button type="submit" class="btn btn-success" id="editProductBtn" data-loading-text="্লোড হচ্ছে..."> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				      </div> <!-- /modal-footer -->
			        </form> <!-- /.form -->
				    </div>
				    <!-- /product info -->
				  </div>

				</div>

	      </div> <!-- /modal-body -->


    </div>
    <!-- /modal-content -->
  </div>
  <!-- /modal-dailog -->
</div>
<!-- /categories brand -->

<!-- categories brand -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> সক্রিয় পণ্য অপসারণ</h4>
      </div>
      <div class="modal-body">

      	<div class="removeProductMessages"></div>

        <p>আপনি কি সত্যিই এই সক্রিয় পণ্যটি অপসারণ করতে চান ?</p>
      </div>
      <div class="modal-footer removeProductFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
        <button type="button" class="btn btn-primary" id="removeProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /categories brand -->

<script>
    $(document).ready(function(){
        $('[id^=quantity]').keypress(validateNumber);
        $('[id^=brate]').keypress(validateNumber);
        $('[id^=rate]').keypress(validateNumber);
        $('[id^=addpro]').keypress(validateNumber);
        $('[id^=editbRate]').keypress(validateNumber);
        $('[id^=editRate]').keypress(validateNumber);
    });

    function validateNumber(event) {
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode === 8 || event.keyCode === 46) {
            return true;
        } else if ( key < 48 || key > 57 ) {
            return false;
        } else {
            return true;
        }
    };
</script>
<script src="custom/js/product.js"></script>

<?php require_once 'includes/footer.php'; ?>
