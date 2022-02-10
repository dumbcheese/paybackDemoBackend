<?php
require __DIR__ . "/config/config.php";
require PROJECT_ROOT_PATH . "/src/Controllers/DemoController.php";
 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
 
// if ((isset($uri[2]) && $uri[2] != 'demo') || !isset($uri[3]) || count($uri) > 5) {
//     header("HTTP/1.1 404 Not Found");
//     exit();
// }
 
$objFeedController = new DemoController();
$strMethodName = $uri[3] . 'Action';
$objFeedController->{$strMethodName}();
?>