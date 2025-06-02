	
</div><!-- /#page-content-wrapper -->
</div>

		<script src="custom/js/custom.js" type="text/javascript"></script>
		<script src="assests/bootstrap/js/bootstrap.min.js"></script>
		<script src="assests/plugins/fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
		<script src="assests/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
		<script src="assests/plugins/fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
		<script src="assests/plugins/fileinput/js/fileinput.min.js"></script>
		 <script>
		 	//for datepicker formet
			$( "#startDate" ).datepicker({
				dateFormat: "dd/mm/yy"
			});
				$( "#endDate" ).datepicker({
			dateFormat: "dd/mm/yy"
			});
				$( "#orderDate" ).datepicker({
			dateFormat: "dd/mm/yy"
			});

			$(function(){
				// ADD SLIDEDOWN ANIMATION TO DROPDOWN //
				$('.dropdown').on('show.bs.dropdown', function(e){
					$(this).find('.dropdown-menu').first().stop(true, true).slideDown();
				});

				// ADD SLIDEUP ANIMATION TO DROPDOWN //
				$('.dropdown').on('hide.bs.dropdown', function(e){
					e.preventDefault();
					$(this).find('.dropdown-menu').first().stop(true, true).slideUp(400, function(){
					//reset all active dropdown
					//This fixes being removed too fast
						$('.dropdown').removeClass('open');
					$('.dropdown').find('.dropdown-toggle').attr('aria-expanded','false');
					});
				});
			});
		</script>
		<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
		<script>
		(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
		function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
		e=o.createElement(i);r=o.getElementsByTagName(i)[0];
		e.src='https://www.google-analytics.com/analytics.js';
		r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
		ga('create','UA-XXXXX-X','auto');ga('send','pageview');
		</script>
		<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
	</body>
</html>