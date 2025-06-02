    var managesrtable;
    var manageisrtable;
$(document).ready(function() {

	  	manageisrtable = $("#manageisrtable").DataTable({
		'ajax': 'php_action/ifetchcompany.php',
		'order': []
	});

	managesrtable = $("#managesrtable").DataTable({
		'ajax': 'php_action/fetchcompany.php',
		'order': []
	});



		// submit brand form function
	$("#submitsrForm").unbind('submit').bind('submit', function() {
		// remove the error text
		$(".text-danger").remove();
		// remove the form error
		$('.form-group').removeClass('has-error').removeClass('has-success');

		var brandName = $("#brandName").val();


		if(brandName == "") {
			$("#brandName").after('<p class="text-danger">Company Name field is required</p>');
			$('#brandName').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
			$("#brandName").find('.text-danger').remove();
			// success out for form
			$("#brandName").closest('.form-group').addClass('has-success');
		}


		if(brandName) {
			var form = $(this);
			// button loading
			$("#createsrBtn").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {
					// button loading
					$("#createsrBtn").button('reset');

					if(response.success == true) {
						// reload the manage member table
						managesrtable.ajax.reload(null, false);
						manageisrtable.ajax.reload(null, false);

  	  			// reset the form text
						$("#submitsrForm")[0].reset();
						// remove the error text
						$(".text-danger").remove();
						// remove the form error
						$('.form-group').removeClass('has-error').removeClass('has-success');

  	  			$('#add-sr-messages').html('<div class="alert alert-success">'+
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
          '</div>');

  	  			$(".alert-success").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
							});
						}); // /.alert
					}  // if

				} // /success
			}); // /ajax
		} // if

		return false;
	}); // /submit brand form function

});

function editBrands(brandId = null) {
	if(brandId) {
		// remove hidden brand id text
		$('#brandId').remove();

		// remove the error
		$('.text-danger').remove();
		// remove the form-error
		$('.form-group').removeClass('has-error').removeClass('has-success');

		// modal loading
		$('.modal-loading').removeClass('div-hide');
		// modal result
		$('.edit-brand-result').addClass('div-hide');
		// modal footer
		$('.editsrFooter').addClass('div-hide');

		$.ajax({
			url: 'php_action/fetchSelectedcompany.php',
			type: 'post',
			data: {brandId : brandId},
			dataType: 'json',
			success:function(response) {
				// modal loading
				$('.modal-loading').addClass('div-hide');
				// modal result
				$('.edit-brand-result').removeClass('div-hide');
				// modal footer
				$('.editsrFooter').removeClass('div-hide');

				// setting the brand name value
				$('#editBrandName').val(response.name);
				$('#editsrsrname').val(response.status);

				// brand id
				$(".editsrFooter").after('<input type="hidden" name="Id" id="brandId" value="'+response.company_id+'" />');

				// update brand form
				$('#editsrForm').unbind('submit').bind('submit', function() {

					// remove the error text
					$(".text-danger").remove();
					// remove the form error
					$('.form-group').removeClass('has-error').removeClass('has-success');

					var brandName = $('#editBrandName').val();

					if(brandName == "") {
						$("#editBrandName").after('<p class="text-danger">Company Name field is required</p>');
						$('#editBrandName').closest('.form-group').addClass('has-error');
					} else {
						// remov error text field
						$("#editBrandName").find('.text-danger').remove();
						// success out for form
						$("#editBrandName").closest('.form-group').addClass('has-success');
					}


					if(brandName) {
						var form = $(this);

						// submit btn
						$('#editsrBtn').button('loading');

						$.ajax({
							url: form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response) {

								if(response.success == true) {
									console.log(response);
									// submit btn
									$('#editsrBtn').button('reset');

									// reload the manage member table
									managesrtable.ajax.reload(null, false);
									manageisrtable.ajax.reload(null, false);
									// remove the error text
									$(".text-danger").remove();
									// remove the form error
									$('.form-group').removeClass('has-error').removeClass('has-success');

			  	  			$('#edit-sr-messages').html('<div class="alert alert-success">'+
			            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
			            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
			          '</div>');

			  	  			$(".alert-success").delay(500).show(10, function() {
										$(this).delay(3000).hide(10, function() {
											$(this).remove();
										});
									}); // /.alert
								} // /if

							}// /success
						});	 // /ajax
					} // /if

					return false;
				}); // /update brand form

			} // /success
		}); // ajax function

	} else {
		alert('error!! Refresh the page again');
	}
} // /edit brands function



function removeBrands(brandId = null) {
	if(brandId) {
		$('#removeBrandId').remove();
		$.ajax({
			url: 'php_action/fetchSelectedsr.php',
			type: 'post',
			data: {brandId : brandId},
			dataType: 'json',
			success:function(response) {
				$('.removesrFooter').after('<input type="hidden" name="removeBrandId" id="removeBrandId" value="'+response.company_id+'" /> ');

				// click on remove button to remove the brand
				$("#removesrBtn").unbind('click').bind('click', function() {
					// button loading
					$("#removesrBtn").button('loading');

					$.ajax({
						url: 'php_action/removecompany.php',
						type: 'post',
						data: {brandId : brandId},
						dataType: 'json',
						success:function(response) {
							console.log(response);
							// button loading
							$("#removesrBtn").button('reset');
							if(response.success == true) {

								// hide the remove modal
								$('#removeSrProduct').modal('hide');

								// reload the brand table
								managesrtable.ajax.reload(null, false);
								manageisrtable.ajax.reload(null, false);

								$('.remove-sr-messages').html('<div class="alert alert-success">'+
			            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
			            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
			          '</div>');

			  	  			$(".alert-success").delay(500).show(10, function() {
										$(this).delay(3000).hide(10, function() {
											$(this).remove();
										});
									}); // /.alert
							} else {

							} // /else
						} // /response messages
					}); // /ajax function to remove the brand

				}); // /click on remove button to remove the brand

			} // /success
		}); // /ajax

		$('.removesrFooter').after();
	} else {
		alert('error!! Refresh the page again');
	}
} // /remove brands function

function printOrder() {
    if('3') {

		$.ajax({
			url: 'php_action/printuser.php',
			type: 'post',
			data: {'3': '3'},
			dataType: 'text',
			success:function(response) {

				var mywindow = window.open('', 'Stock Management System', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Active User Report</title>');
        mywindow.document.write('</head><body>');
        mywindow.document.write(response);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

			}// /success function
		}); // /ajax function to fetch the printable order
	} // /if orderId
} // /print order function
