var manageDuetable;

       $(document).ready(function () {

         manageDuetable = $("#manageDuetable").DataTable({
    	'ajax': 'php_action/fetchdue.php',
		'order': []
		});

    });

function printOrder() {
	if('2') {

		$.ajax({
			url: 'php_action/printDue.php',
			type: 'post',
			data: {'2': '2'},
			dataType: 'text',
			success:function(response) {

				var mywindow = window.open('', 'Stock Management System', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Due Report</title>');
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
