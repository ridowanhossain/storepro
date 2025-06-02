<?php 	
header('Content-Type: application/json');
require_once 'core.php';

// Default values
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$row = isset($_POST['start']) ? intval($_POST['start']) : 0;
$rowperpage = isset($_POST['length']) ? intval($_POST['length']) : 10;
$columnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'product_id';
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Validate sort order
$columnSortOrder = strtolower($columnSortOrder) === 'desc' ? 'DESC' : 'ASC';

// Validate column name to prevent SQL injection
$allowedColumns = ['product_id', 'product_name', 'brand_name', 'categories_name', 'quantity', 'rate', 'brate', 'clor'];
$columnName = in_array($columnName, $allowedColumns) ? $columnName : 'product_id';

// Search condition
$searchQuery = "";
if($searchValue != '') {
    $searchQuery = " AND (
        product.product_id LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        product.product_name LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        brands.brand_name LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        categories.categories_name LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        product.features LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        product.brate LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        product.rate LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        product.quantity LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%' OR 
        product.clor LIKE '%".mysqli_real_escape_string($connect, $searchValue)."%'
    )";
}

// Total records without filtering
$totalRecords = $connect->query("SELECT COUNT(*) as allcount FROM product WHERE status = 1")->fetch_assoc();
$totalRecords = $totalRecords['allcount'];

// Total records with filtering
$totalRecordwithFilter = $connect->query("SELECT COUNT(*) as allcount FROM product 
    INNER JOIN brands ON product.brand_id = brands.brand_id 
    INNER JOIN categories ON product.categories_id = categories.categories_id  
    WHERE product.status = 1 ".$searchQuery)->fetch_assoc();
$totalRecordwithFilter = $totalRecordwithFilter['allcount'];

// Fetch records
// Modify the main query to use proper column name
// Modify WHERE clause to only show inactive products
$sql = "SELECT product.product_id, product.product_name, product.product_image, product.brand_id,
        product.categories_id, product.quantity, product.rate, product.active, product.status,
        brands.brand_name, categories.categories_name, product.clor, product.brate, product.features 
    FROM product 
    INNER JOIN brands ON product.brand_id = brands.brand_id 
    INNER JOIN categories ON product.categories_id = categories.categories_id  
    WHERE product.status = 1 AND product.active = 2 ".$searchQuery." 
    ORDER BY ".$columnName." ".$columnSortOrder." 
    LIMIT ".$row.", ".$rowperpage;

// Also update the count queries
$totalRecords = $connect->query("SELECT COUNT(*) as allcount FROM product WHERE status = 1 AND active = 2")->fetch_assoc();
$totalRecords = $totalRecords['allcount'];

$totalRecordwithFilter = $connect->query("SELECT COUNT(*) as allcount FROM product 
    INNER JOIN brands ON product.brand_id = brands.brand_id 
    INNER JOIN categories ON product.categories_id = categories.categories_id  
    WHERE product.status = 1 AND product.active = 2 ".$searchQuery)->fetch_assoc();
$totalRecordwithFilter = $totalRecordwithFilter['allcount'];

$result = $connect->query($sql);
$data = array();

while($row = $result->fetch_assoc()) {
    // Active status
    $active = ($row['active'] == 1) 
        ? "<div class='text-center'><label class='label label-success'>সক্রিয়</label></div>"
        : "<div class='text-center'><label class='label label-danger'>নিষ্ক্রিয়</label></div>";

    // Button
    $button = '<div class="text-center"><div class="btn-group">
        <button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ব্যবস্থা <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pro-dropdown">
            <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$row['product_id'].')">
                <i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
    
    if($_SESSION['Status']=='5'){
        $button .= '<li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$row['product_id'].')">
            <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';
    }
    
    $button .= '</ul></div></div>';

    // Product image with fallback
    $imageUrl = substr($row['product_image'], 3);
    $productImage = "<img class='img-round product-img' src='".$imageUrl."' style='height:30px; width:50px;' onerror=\"this.onerror=null; this.src='assests/images/product.jpg';\" />";

    // Features - show "নেই" if empty
    $features = !empty($row['features']) ? $row['features'] : "নেই";

    // Data array
    $data[] = array(
        "product_image" => $productImage,
        "product_id" => $row['product_id'],
        "brand_name" => $row['brand_name'],
        "product_name" => $row['product_name'],
        "categories_name" => $row['categories_name'],
        "features" => $features,
        "brate" => $row['brate'],
        "rate" => $row['rate'],
        "quantity" => $row['quantity'],
        "clor" => $row['clor'],
        "active" => $active,
        "button" => $button
    );
}

// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
$connect->close();