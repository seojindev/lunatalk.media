<?php
error_reporting(1);

use App\Route\Route;
use App\Http\BaseController;
use App\Http\ImageController;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Bootstrap.php';

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

header('Content-type: application/json');


Route::add('/info', function() {
    $check = BaseController::checkHeader();
    if ($check['state'] === false) {
        return BaseController::serverResponse($check, 403);
    }
    echo BaseController::getRootDomain();
});

Route::add('/media-upload', function() {
    $task = new ImageController();
    $task::start();
}, 'post');

Route::run(BASEPATH);
?>