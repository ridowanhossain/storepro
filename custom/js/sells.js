var manageDuetable;

       $(document).ready(function () {
       	
 		manageDuetable = $("#managesellstable").DataTable({
		'ajax': 'php_action/fetchsells.php',
		'order': []		
		});

    });