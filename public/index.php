<?php
error_reporting(0);

use App\Route\Route;
use App\Http\BaseController;
use App\Http\ImageController;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../app/Bootstrap.php';

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