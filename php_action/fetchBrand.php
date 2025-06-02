<?php
header('Content-Type: application/json');

require_once 'core.php';

// Initialize response array
$output = array(
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => array()
);

// Get total number of records
$sqlTotal = "SELECT COUNT(*) as total FROM brands WHERE brand_status = 1";
$resultTotal = $connect->query($sqlTotal);
$row = $resultTotal->fetch_array();
$output['recordsTotal'] = $row['total'];
$output['recordsFiltered'] = $row['total'];

// Prepare the main query
$sql = "SELECT brand_id, brand_name, brand_active, brand_status 
        FROM brands 
        WHERE brand_status = 1";

// Handle searching
if(!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $sql .= " AND (brand_name LIKE '%$search%' OR brand_id LIKE '%$search%')";
    
    // Get filtered count
    $sqlFiltered = "SELECT COUNT(*) as total FROM brands 
                    WHERE brand_status = 1 
                    AND (brand_name LIKE '%$search%' OR brand_id LIKE '%$search%')";
    $resultFiltered = $connect->query($sqlFiltered);
    $row = $resultFiltered->fetch_array();
    $output['recordsFiltered'] = $row['total'];
}

// Handle ordering
if(isset($_POST['order'])) {
    $column = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $columns = array('brand_id', 'brand_id', 'brand_name', 'brand_active');
    if(isset($columns[$column])) {
        $sql .= " ORDER BY " . $columns[$column] . " " . $order;
    }
} else {
    $sql .= " ORDER BY brand_id DESC";
}

// Handle pagination
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$sql .= " LIMIT " . $start . ", " . $length;

$result = $connect->query($sql);
$counter = $start + 1;

if($result->num_rows > 0) {
    while($row = $result->fetch_array()) {
        $brandId = $row[0];
        
        // Active status
        $activeBrands = ($row[2] == 1) ? 
            "<label class='label label-success'>সক্রিয়</label>" : 
            "<label class='label label-danger'>নিষ্ক্রিয়</label>";

        // Action buttons
        $button = '';
        if($_SESSION['Status']=='5'){
            $button = '<div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ব্যবস্থা <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a type="button" data-toggle="modal" data-target="#editBrandModel" onclick="editBrands('.$brandId.')"> <i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>
                    <li><a type="button" data-toggle="modal" data-target="#removeMemberModal" onclick="removeBrands('.$brandId.')"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>
                </ul>
            </div>';
        }

        $output['data'][] = array(
            $counter,
            $brandId,
            $row[1],
            $activeBrands,
            $button
        );
        $counter++;
    }
}

$connect->close();
echo json_encode($output);