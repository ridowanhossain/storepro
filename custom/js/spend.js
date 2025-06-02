	var manageinvocetable;
$(document).ready(function() {
	managespendtable = $("#manageinvocetable").DataTable({
		'ajax': 'php_action/fetchSpend.php',
		'order': []		
	});

   	if(paid) {
 		dueAmount = Number($("#totalamount").val()) - Number($("#paid").val());
 		dueAmount = dueAmount.toFixed(2);
 		$("#due").val(dueAmount);
 	} 


		// submit brand form function
	$("#submitspendForm").unbind('submit').bind('submit', function() {
		// remove the error text
		$(".text-danger").remove();
		// remove the form error
		$('.form-group').removeClass('has-error').removeClass('has-success');			
		var com_name = $("#com_name").val();
		var totalamount = $("#totalamount").val();
		var paid = $("#paid").val();

		if(com_name == "") {
			$("#com_name").after('<p class="text-danger">Company Name field is required</p>');
			$('#com_name').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
			$("#com_name").find('.text-danger').remove();
			// success out for form 
			$("#com_name").closest('.form-group').addClass('has-success');	  	
		}	
      
		if(totalamount == "") {
			$("#totalamount").after('<p class="text-danger">Total Amount  field is required</p>');
			$('#totalamount').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
			$("#totalamount").find('.text-danger').remove();
			// success out for form 
			$("#totalamount").closest('.form-group').addClass('has-success');	  	
		}
        

		if(paid == "") {
			$("#paid").after('<p class="text-danger">Paid field is required</p>');
			$('#paid').closest('.form-group').addClass('has-error');
		} else {
			// remov error text field
			$("#paid").find('.text-danger').remove();
			// success out for form 
			$("#paid").closest('.form-group').addClass('has-success');	  	
		}


		if(com_name && totalamount && paid) {
			var form = $(this);
			// button loading
			$("#createspendBtn").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {
					// button loading
					$("#createspendBtn").button('reset');

					if(response.success == true) {
						// reload the manage member table 
						managespendtable.ajax.reload(null, false);						

  	  			// reset the form text
						$("#submitspendForm")[0].reset();
						// remove the error text
						$(".text-danger").remove();
						// remove the form error
						$('.form-group').removeClass('has-error').removeClass('has-success');
  	  			
  	  			$('#add-spend-messages').html('<div class="alert alert-success">'+
            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
          '</div>');

  	  			$(".alert-success").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
							});
						}); // /.alert
					}  // if

				}//success
			}); // /ajax	
		} // if

		return false;
	}); // /submit brand form function
 	
});

function editspend(spendId = null) {
	if(spendId) {
		// remove hidden brand id text
		$('#spendId').remove();
/*		alert(spendId);
*/
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
			url: 'php_action/fetchSelectedSpend.php',
			type: 'post',
			data: {spendId : spendId},
			dataType: 'json',
			success:function(response) {
				// modal loading
				$('.modal-loading').addClass('div-hide');
				// modal result
				$('.edit-brand-result').removeClass('div-hide');
				// modal footer
				$('.editsrFooter').removeClass('div-hide');

				// setting the brand name value 
				$('#editcom_name').val(response.c_name);
				$('#edit_totalamount').val(response.total);
				$('#editpaid').val(response.paid);
				$('#editdue').val(response.due);


				// brand id 
				$(".editspendFooter").after('<input type="hidden" name="Id" id="spendId" value="'+response.id+'" />');

				// update brand form 
				$('#editspendForm').unbind('submit').bind('submit', function() {

					// remove the error text
					$(".text-danger").remove();
					// remove the form error
					$('.form-group').removeClass('has-error').removeClass('has-success');			

					var com_name = $("#editcom_name").val();
					var totalamount = $("#edit_totalamount").val();
					var paid = $("#editpaid").val();

					if(com_name == "") {
						$("#editcom_name").after('<p class="text-danger">Company Name field is required</p>');
						$('#editcom_name').closest('.form-group').addClass('has-error');
					} else {
						// remov error text field
						$("#editcom_name").find('.text-danger').remove();
						// success out for form 
						$("#editcom_name").closest('.form-group').addClass('has-success');	  	
					}

				if(totalamount == "") {
					$("#edit_totalamount").after('<p class="text-danger">Total Amount  field is required</p>');
					$('#edit_totalamount').closest('.form-group').addClass('has-error');
				} else {
					// remov error text field
					$("#edit_totalamount").find('.text-danger').remove();
					// success out for form 
					$("#edit_totalamount").closest('.form-group').addClass('has-success');	  	
			  	}

					if(com_name && totalamount) {
						var form = $(this);

						// submit btn
						$('#editspendBtn').button('loading');

						$.ajax({
							url: form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response) {

								if(response.success == true) {
									console.log(response);
									// submit btn
									$('#editspendBtn').button('reset');

									// reload the manage member table 
									managespendtable.ajax.reload(null, false);								  	  										
									// remove the error text
									$(".text-danger").remove();
									// remove the form error
									$('.form-group').removeClass('has-error').removeClass('has-success');
			  	  			
			  	  			$('#edit-spend-messages').html('<div class="alert alert-success">'+
			            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
			            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
			          '</div>');

			  	  			$(".alert-success").delay(500).show(10, function() {
										$(this).delay(3000).hide(10, function() {
											$(this).remove();
										});
									}); // /.alert
								} // /if
									
							},// /succes
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


 function dueFunc(){
   var totalDue=   Number($('#totalamount').val())-Number($('#paid').val());
   $('#due').val(totalDue);
    } 
 
 function duecal(){
   var totalDue=   Number($('#totalamount').val())-Number($('#paid').val());
   $('#due').val(totalDue);
    } 

    function editduecal(){
   var totalDue=   Number($('#edit_totalamount').val())-Number($('#editpaid').val())-Number($('#paidnow').val());;
   $('#editdue').val(totalDue);
    } 

  function editdueFunc(){
   var paid = Number($('#editpaid').val());
   var paidnow = Number($('#paidnow').val());
   var totalpaid = paid+paidnow;
   var totalDue =   Number($('#edit_totalamount').val())-totalpaid;
     $('#editdue').val(totalDue);
    }

function removeBrands(spendId = null) {
	if(spendId) {
		$('#removespendId').remove();
		$.ajax({
			url: 'php_action/fetchSelectedSpend.php',
			type: 'post',
			data: {spendId : spendId},
			dataType: 'json',
			success:function(response) {
				$('.removespendFooter').after('<input type="hidden" name="removespendId" id="removespendId" value="'+response.id+'" /> ');

				// click on remove button to remove the brand
				$("#removespendBtn").unbind('click').bind('click', function() {
					// button loading
					$("#removespendBtn").button('loading');

					$.ajax({
						url: 'php_action/removeSpend.php',
						type: 'post',
						data: {spendId : spendId},
						dataType: 'json',
						success:function(response) {
							console.log(response);
							// button loading
							$("#removespendBtn").button('reset');
							if(response.success == true) {

								// hide the remove modal 
								$('#removeSrProduct').modal('hide');

								// reload the brand table 
								managespendtable.ajax.reload(null, false);
								
								$('.remove-messages').html('<div class="alert alert-success">'+
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