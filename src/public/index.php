<?php
require_once '../vendor/autoload.php';

use Steampixel\Route;

Route::add('/', function() {
    $siteTitle = 'Home';
    $content = '../templates/home.php';
    include('../templates/base.php');
});

Route::add('/bundesliga', function() {
    $siteTitle = '1. Bundesliga';
    $content = '../templates/bundesliga.php';
    include('../templates/base.php');
});

Route::add('/nfl', function() {
    $siteTitle = 'NFL';
    $content = '../modules/nfl.php';
    include('../templates/base.php');
});

Route::run('/');

/*$requestUri = $_SERVER['REQUEST_URI'];

switch ($requestUri) {
    case '':
    case '/':
        $siteTitle = 'Home';
        $content = '../templates/home.php';
        include('../templates/base.php');
        break;
    case '/bundesliga':
    case '/bundesliga/':
        $siteTitle = '1. Bundesliga';
        $content = '../templates/bundesliga.php';
        include('../templates/base.php');
        break;
    case '/nfl':
    case '/nfl/':
        $siteTitle = 'NFL';
        $content = '../modules/nfl.php';
        include('../templates/base.php');
        break;
}*/
?>