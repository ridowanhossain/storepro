<?php 
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
    $shop_name = $_POST['shop_name'];
    $owner_name = $_POST['owner_name'];
    $allproduct_name = $_POST['allproduct_name'];
    $shop_address = $_POST['shop_address'];
    $shop_mobile = $_POST['shop_mobile'];
    $company_name = $_POST['company_name'];
    $contact_no = $_POST['contact_no'];
    $email_addr = $_POST['email_addr'];

    $sql = "UPDATE shop_settings SET 
            shop_name = '$shop_name', 
            owner_name = '$owner_name',
            allproduct_name = '$allproduct_name',
            shop_address = '$shop_address',
            shop_mobile = '$shop_mobile',
            company_name = '$company_name',
            contact_no = '$contact_no',
            email_addr = '$email_addr'
            WHERE id = 1";

    if($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "সফলভাবে আপডেট করা হয়েছে";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "ত্রুটি হয়েছে, আবার চেষ্টা করুন";
    }

    $connect->close();

    echo json_encode($valid);
}