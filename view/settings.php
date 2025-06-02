<?php 
header('Content-Type: text/html; charset=utf-8');
require_once 'includes/header.php'; 
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php if($_SESSION['Status']!='5') {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: https://$host$uri");
    exit;    
} ?>
<?php 
$sql = "SELECT * FROM shop_settings WHERE id = 1";
$query = $connect->query($sql);
$result = $query->fetch_assoc();
$connect->close();
?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
          <li><a href="dashboard">ড্যাসবোর্ড</a></li>          
          <li class="active">দোকানের সেটিংস</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-wrench"></i> দোকানের সেটিং</div>
            </div>
            <div class="panel-body">
                <form action="php_action/changeShopSettings.php" method="post" class="form-horizontal" id="shopSettingsForm">
                    <fieldset>
                        <legend>দোকানের তথ্য পরিবর্তন</legend>

                        <div class="shopSettingsMessages"></div>            

                        <div class="form-group">
                            <label class="col-sm-2 control-label">দোকানের নাম</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="shop_name" name="shop_name" value="<?php echo $result['shop_name']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">মালিকের নাম</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="owner_name" name="owner_name" value="<?php echo $result['owner_name']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">পণ্যের বিবরণ</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="allproduct_name" name="allproduct_name"><?php echo $result['allproduct_name']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">ঠিকানা</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="shop_address" name="shop_address"><?php echo $result['shop_address']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">মোবাইল নম্বর</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="shop_mobile" name="shop_mobile" value="<?php echo $result['shop_mobile']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Company Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $result['company_name']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Contact No</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo $result['contact_no']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email_addr" name="email_addr" value="<?php echo $result['email_addr']; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success" id="saveSettingsBtn"> 
                                    <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন 
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>        
    </div>
</div>

<script src="custom/js/settings.js"></script>
<?php require_once 'includes/footer.php'; ?>