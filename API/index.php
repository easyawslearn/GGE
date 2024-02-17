<?php
// Define your base directory
define('BASE_DIR', __DIR__);

// Define your routes
$routes = [
    '/GGE/API/login' => '/loginApi.php',
    '/GGE/API/region' => '/region.php',
    '/GGE/API/constituency' => '/constituency.php',
    '/GGE/API/pollingStation' => '/pollingStation.php',
    '/GGE/API/pollingStationWiseParty' => '/pollingStationWiseParty.php',
    '/GGE/API/regionWiseParty' => '/regionWiseParty.php',
    '/GGE/API/pollingAgentWiseParty' => '/pollingAgentWiseParty.php',
    '/GGE/API/saveVote' => '/saveVote.php',

    '/GGE/API/V2/login' => '/V2/loginApiV_2.php',
    '/GGE/API/V2/pollingAgentWiseParty' => '/V2/pollingAgentWisePartyV_2.php',
    '/GGE/API/V2/pollingStationWiseParty' => '/V2/pollingStationWisePartyV_2.php',
    '/GGE/API/V2/constituency' => '/V2/constituencyV_2.php',
    '/GGE/API/V2/pollingStation' => '/V2/pollingStationV_2.php',
    '/GGE/API/V2/region' => '/V2/regionV_2.php',
    '/GGE/API/V2/regionWiseParty' => '/V2/regionWisePartyV_2.php',
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
