<?php 	
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
    $productName     = $_POST['productName'];
    $quantity        = $_POST['quantity'];
    $rate           = $_POST['rate'];
    $brandName      = $_POST['brandName'];
    $categoryName   = $_POST['categoryName'];
    $productStatus  = $_POST['productStatus'];
    $clor          = $_POST['clor'];
    $feature       = $_POST['feature'];
    $brate         = $_POST['brate'];
    $post_date     = date('Y-m-d');
    
    // Default image path
    $url = '../assests/images/product.jpg';
    
    // Check if image was uploaded
    if(!empty($_FILES['productImage']['name'])) {
        $type = explode('.', $_FILES['productImage']['name']);
        $type = $type[count($type)-1];		
        $url = '../assests/images/stock/'.uniqid(rand()).'.'.$type;
        
        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['productImage']['tmp_name'])) {			
                if(!move_uploaded_file($_FILES['productImage']['tmp_name'], $url)) {
                    $url = '../assests/images/product.jpg'; // Use default if upload fails
                }
            }
        }
    }
    
    $sql = "INSERT INTO product (product_name, product_image, brand_id, categories_id, quantity, rate, active, status,brate,pdate,clor,features) 
            VALUES ('$productName', '$url', '$brandName', '$categoryName', '$quantity', '$rate', '$productStatus', 1,'$brate','$post_date','$clor','$feature')";

    $prosql = "INSERT INTO pro (pro_name, brand_name, cat_name, qty, pdate,brate,clor) 
            VALUES ('$productName', '$brandName', '$categoryName', '$quantity', '$post_date', '$brate','$clor')";
    
    $connect->query($prosql);
    
    if($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "সফলভাবে যুক্ত হয়েছে";	
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while adding the members";
    }

    $connect->close();
    echo json_encode($valid);
} // /if $_POST