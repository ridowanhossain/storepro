<?php require_once 'includes/header.php'; ?>
<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

   $sql ="SELECT * from spend where spend.id='$id' ";
    $result = $connect->query($sql);
    $c_name = '';
    $spend_date = '';
    $total_amount = 0;
    
    if($result->num_rows > 0) {
        $row = $result->fetch_array();
        $c_name = $row['c_name'];
        $spend_date = $row['spend_date'];
        $total_amount = $row['total'];
    }
?>
<div class="main-table">
	<div class="product-table-card">
		<div class="table-title">
			<i class="fa fa-file-text"></i> <span>খরচের প্রতিবেদন: <?php echo $c_name; ?></span>
		</div>
			<div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
				<button id="print-btn" class="btn btn-modern"><i class="glyphicon glyphicon-print"></i> প্রিন্ট</button>
			</div>
			
			<div id="printdiv">
			<table class="table table-bordered modern-table dataTable no-footer dtr-inline" cellspacing="0" cellpadding="20" style="margin-top:20px">
				<thead>
					<tr>
						<th><center>পরিশোধের তারিখ</center></th>
						<th><center>পরিশোধ</center></th>
					</tr>
				</thead>
				<tbody>
					<?php 
                        // Format date
                        $date_formatted = date_create($spend_date);
                        $date_formatted = date_format($date_formatted, "d/m/Y");
					?>
						<tr>
						<td><center><?php echo $date_formatted; ?></center></td>
						<td><center><?php echo $total_amount; ?></center></td>
						</tr>
						
						<tr>
							<td class="text-right">সর্বমোট পরিশোধ</td>
							<td><center><?php echo $total_amount; ?></center></td>
						</tr>
						<tr>
							<td class="text-right">বাঁকী</td>
							<td><center>0</center></td>
						</tr>
				</tbody>
			</table>
			</div>
				</tbody>
			</table>
	</div>
</div>
<script src="assests/plugins/printme/jquery-printme.js"></script>
<script type="text/javascript">
	$(document).ready(function () {

		$("#print-btn").click(function(){
			$("#printdiv").printMe({
				"path" : ["https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap", "assests/bootstrap/css/bootstrap.min.css","assests/bootstrap/css/bootstrap-theme.min.css","custom/css/print.css?v=<?php echo filemtime('custom/css/print.css'); ?>"]
			});
		});
	});
</script>
<?php require_once 'includes/footer.php'; ?>
