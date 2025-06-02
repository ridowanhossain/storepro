<?php
function removePhpExtension($url)
{
    return str_replace('.php', '', $url);
}

$request = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir = dirname($scriptName);
$basePath = rtrim($scriptDir, '/') . '/';

// Remove base path from request
if (strpos($request, $basePath) === 0) {
    $request = substr($request, strlen($basePath));
} else {
    $request = ltrim($request, '/');
}

// Remove query string if present
$requestPath = parse_url($request, PHP_URL_PATH);
$queryString = parse_url($request, PHP_URL_QUERY);

// Remove trailing slash if present
$requestPath = rtrim($requestPath, '/');

// Split path into segments and handle special case for edit-order
$pathSegments = explode('/', $requestPath);
$route = $pathSegments[0];
$slug = isset($pathSegments[1]) ? $pathSegments[1] : '';

// Define valid routes with their aliases
function getRouteConfig() {
    return [
        'dashboard.php' => [
            'aliases' => ['dashboard']
        ],
        'index.php' => [
            'aliases' => ['index', '']
        ],
        'order.php' => [
            'aliases' => ['quick-order', 'quick-order-list', 'quick-order-edit']
        ],
        'regular-order.php' => [
            'aliases' => ['regular-order']
        ],
        'order-regular.php' => [
            'aliases' => ['regular-order-list', 'regular-order-edit']
        ],
        'categories.php' => [
            'aliases' => ['category']
        ],
        'brand.php' => [
            'aliases' => ['brands']
        ],
        'product.php' => [
            'aliases' => ['products']
        ],
        'inactive-product.php' => [
            'aliases' => ['inactive-products']
        ],
        'active-customer.php' => [
            'aliases' => ['customers']
        ],
        'inactive-customer.php' => [
            'aliases' => ['inactive-customers']
        ],
        'customer-report.php' => [
            'aliases' => ['customer-report']
        ],
        'invoice.php' => [
            'aliases' => ['invoices']
        ],
        'active-company.php' => [
            'aliases' => ['companies']
        ],
        'inactive-company.php' => [
            'aliases' => ['inactive-companies']
        ],
        'spend.php' => [
            'aliases' => ['spends']
        ],
        'stock-report.php' => [
            'aliases' => ['stock']
        ],
        'spend-reports.php' => [
            'aliases' => ['spend-report']
        ],
        'invoice-reports.php' => [
            'aliases' => ['invoice-report']
        ],
        'sells-report.php' => [
            'aliases' => ['sales']
        ],
        'orders-report.php' => [
            'aliases' => ['order-report']
        ],
        'profit-report.php' => [
            'aliases' => ['profit']
        ],
        'single-report.php' => [
            'aliases' => ['quick-order-view', 'regular-order-view']
        ],
        'settings.php' => [
            'aliases' => ['settings']
        ],
        'due.php' => [
            'aliases' => ['dues']
        ],
        'user.php' => [
            'aliases' => ['users']
        ],
        'seller.php' => [
            'aliases' => ['sellers']
        ],
        'user-setting.php' => [
            'aliases' => ['user-settings']
        ],
        'logout.php' => [
            'aliases' => ['logout']
        ],
    ];
}

// Get route configuration
$routeConfig = getRouteConfig();

// If empty request, default to dashboard
if (empty($route)) {
    $route = 'dashboard';
}

// Handle special URL patterns
if ($route === 'quick-order-list') {
    $route = 'order';
    $_GET['o'] = 'manord';
}

// Handle quick order URL pattern
if ($route === 'quick-order') {
    $route = 'order';
    $_GET['o'] = 'add';
}

// Handle regular order URL pattern
if ($route === 'regular-order') {
    $route = 'regular-order';
    $_GET['o'] = 'add';
}

// Handle view regular order URL pattern
if ($route === 'regular-order-list') {
    $route = 'order-regular';
    $_GET['o'] = 'show';
}

// Handle edit quick order URL pattern
if ($route === 'quick-order-edit') {
    $route = 'order';
    $_GET['o'] = 'editOrd';
    if (!empty($slug)) {
        $_GET['i'] = $slug;
    }
}

// Handle regular order edit pattern
if (strpos($route, 'regular-order-edit=') === 0) {
    $orderId = substr($route, strlen('regular-order-edit='));
    $route = 'order-regular';
    $_GET['o'] = 'editOrd';
    $_GET['i'] = $orderId;
}

// Handle quick order view pattern
if (strpos($route, 'quick-order-view=') === 0) {
    $orderId = substr($route, strlen('quick-order-view='));
    $route = 'single-report';
    $_GET['id'] = $orderId;
}

// Handle regular order view pattern
if (strpos($route, 'regular-order-view=') === 0) {
    $orderId = substr($route, strlen('regular-order-view='));
    $route = 'single-report';
    $_GET['id'] = $orderId;
}

// Handle customer report pattern
if (strpos($route, 'customer-report=') === 0) {
    $customerId = substr($route, strlen('customer-report='));
    $route = 'customer-report';
    $_GET['id'] = $customerId;
}

// Resolve route alias to actual route file
$resolvedRoute = null;
foreach ($routeConfig as $routeFile => $config) {
    // Check if the current route matches the route file (without .php)
    $routeWithoutExt = removePhpExtension($routeFile);
    if ($route === $routeWithoutExt) {
        $resolvedRoute = $routeFile;
        break;
    }
    
    // Check if the current route matches any aliases
    if (in_array($route, $config['aliases'])) {
        $resolvedRoute = $routeFile;
        break;
    }
}

// If route was resolved, use it; otherwise keep the original route
if ($resolvedRoute) {
    $route = removePhpExtension($resolvedRoute);
}

// Handle edit-order format
if (strpos($route, 'quick-order-edit=') === 0) {
    $orderId = substr($route, strlen('quick-order-edit='));
    $route = 'order';
    $_GET['o'] = 'editOrd';
    $_GET['i'] = $orderId;
    $slug = '';
}

// Add view folder path and .php extension for file lookup
$filePath = __DIR__ . '/view/' . $route . '.php';

// Check if route is valid and file exists
$validRoutesArray = array_keys($routeConfig);
if (in_array($route . '.php', $validRoutesArray) && file_exists($filePath)) {
    // Set the slug as a GET parameter if it exists
    if (!empty($slug)) {
        $_GET['o'] = $slug;
    }

    require_once $filePath;
} else {
    // Handle 404
    header('HTTP/1.0 404 Not Found');
    echo '404 - Page not found';
}
