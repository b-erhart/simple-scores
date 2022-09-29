<?php
require_once __DIR__ . '/../vendor/autoload.php';

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
    $weekId = '';
    $siteTitle = 'NFL';
    $content = '../src/nfl.php';
    include('../templates/base.php');
});

Route::add('/nfl/standings', function() {
    $siteTitle = 'NFL';
    $content = '../src/nfl-standings.php';
    include('../templates/base.php');
});

Route::add('/nfl/([0-9]*)', function($id) {
    $weekId = $id;
    $siteTitle = 'NFL';
    $content = '../src/nfl.php';
    include('../templates/base.php');
});

Route::run('/');
?>