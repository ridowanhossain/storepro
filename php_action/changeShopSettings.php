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

    // First check if row exists
    $check_sql = "SELECT COUNT(*) as count FROM shop_settings WHERE id = 1";
    $check_result = $connect->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    
    if($check_row['count'] == 0) {
        // Row doesn't exist, INSERT instead of UPDATE
        $sql = "INSERT INTO shop_settings (id, shop_name, owner_name, allproduct_name, shop_address, shop_mobile, company_name, contact_no, email_addr) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $connect->prepare($sql);
        
        if($stmt) {
            $id = 1;
            $stmt->bind_param("issssssss", 
                $id,
                $shop_name, 
                $owner_name, 
                $allproduct_name, 
                $shop_address, 
                $shop_mobile, 
                $company_name, 
                $contact_no, 
                $email_addr
            );
            
            if($stmt->execute()) {
                $valid['success'] = true;
                $valid['messages'] = "সফলভাবে সংরক্ষণ করা হয়েছে";
            } else {
                $valid['success'] = false;
                $valid['messages'] = "ত্রুটি হয়েছে: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $valid['success'] = false;
            $valid['messages'] = "SQL প্রস্তুতিতে ত্রুটি: " . $connect->error;
        }
    } else {
        // Row exists, UPDATE it
        $sql = "UPDATE shop_settings SET 
                shop_name = ?, 
                owner_name = ?,
                allproduct_name = ?,
                shop_address = ?,
                shop_mobile = ?,
                company_name = ?,
                contact_no = ?,
                email_addr = ?
                WHERE id = 1";
        
        $stmt = $connect->prepare($sql);
        
        if($stmt) {
            $stmt->bind_param("ssssssss", 
                $shop_name, 
                $owner_name, 
                $allproduct_name, 
                $shop_address, 
                $shop_mobile, 
                $company_name, 
                $contact_no, 
                $email_addr
            );
            
            if($stmt->execute()) {
                if($stmt->affected_rows > 0) {
                    $valid['success'] = true;
                    $valid['messages'] = "সফলভাবে আপডেট করা হয়েছে";
                } else {
                    $valid['success'] = true;
                    $valid['messages'] = "কোনো পরিবর্তন হয়নি (ডাটা একই আছে)";
                }
            } else {
                $valid['success'] = false;
                $valid['messages'] = "ত্রুটি হয়েছে: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $valid['success'] = false;
            $valid['messages'] = "SQL প্রস্তুতিতে ত্রুটি: " . $connect->error;
        }
    }

    $connect->close();

    echo json_encode($valid);
}