<?php     
require_once 'core.php';

$sql = "SELECT spend.id, spend.spend_date, spend.c_name, spend.total FROM spend WHERE status = 1 ORDER BY spend.id DESC";
$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 
    $x = 1;
    
    while($row = $result->fetch_assoc()) {
        $spendId = $row['id'];
        $c_name = $row['c_name'];
        
        // Parse category and description from c_name format: [category] description
        $category = 'অন্যান্য';
        $description = $c_name;
        
        if(preg_match('/^\[(.+?)\]\s*(.*)$/', $c_name, $matches)) {
            $category = $matches[1];
            $description = $matches[2];
        }
        
        // Category badge colors
        $categoryColors = [
            'চা-নাস্তা' => '#e67e22',
            'বিদ্যুৎ বিল' => '#f39c12',
            'বেতন' => '#3498db',
            'পরিবহন' => '#1abc9c',
            'মেরামত' => '#e74c3c',
            'ভাড়া' => '#9b59b6',
            'যোগাযোগ' => '#2ecc71',
            'প্যাকেজিং' => '#34495e',
            'অন্যান্য' => '#95a5a6'
        ];
        
        $badgeColor = $categoryColors[$category] ?? '#95a5a6';
        $categoryBadge = '<span style="background: ' . $badgeColor . '; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px; white-space: nowrap;">' . htmlspecialchars($category) . '</span>';

        // Format date
        $date = date_create($row['spend_date']);
        $date = date_format($date, "d/m/Y");

        // Amount with formatting
        $amount = '<strong>' . number_format($row['total'], 2) . ' ৳</strong>';

        // Action buttons
        // Check if user is Status 5
        $isAdmin = ($_SESSION['Status'] == 5);

        // Action buttons
        $button = '';
        if($isAdmin) {
            $button = '<button type="button" class="btn btn-primary" onclick="editspend(' . $spendId . ')" data-toggle="modal" data-target="#editspend"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</button>';
        }

        $row_data = array(
            $x,
            $categoryBadge,
            htmlspecialchars($description),
            $date,
            $amount
        );
        
        if($isAdmin) {
             $row_data[] = $button;
        }

        $output['data'][] = $row_data;

        $x++;
    }
}

$connect->close();
echo json_encode($output);