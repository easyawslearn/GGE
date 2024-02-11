<?php
// Define your base directory
define('BASE_DIR', __DIR__);

// Define your routes
$routes = [
    '/S3_project/API/login' => '/loginApi.php',
    '/S3_project/API/region' => '/region.php',
    '/S3_project/API/constituency' => '/constituency.php',
    '/S3_project/API/pollingStation' => '/pollingStation.php',
    '/S3_project/API/pollingStationWiseParty' => '/pollingStationWiseParty.php',
    '/S3_project/API/regionWiseParty' => '/regionWiseParty.php',
    '/S3_project/API/pollingAgentWiseParty' => '/pollingAgentWiseParty.php',
    '/S3_project/API/saveVote' => '/saveVote.php',
    '/S3_project/API/test' => '/test.php',
];


// Get the current path
$path = strtok($_SERVER['REQUEST_URI'], '?');

// Check if the path exists in the routes array
if (array_key_exists($path, $routes)) {
    // If it does, require the corresponding file
    require BASE_DIR . $routes[$path];
} else {
    // If it doesn't, send a 404 response
    http_response_code(404);
    echo 'Page not found';
}
