<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

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
    $selectedWeek = '';
    $siteTitle = 'NFL';
    $content = '../templates/nfl-scores.php';
    include('../templates/base.php');
});

Route::add('/nfl/standings', function() {
    $siteTitle = 'NFL';
    $content = '../templates/nfl-standings.php';
    include('../templates/base.php');
});

Route::add('/nfl/([0-9]*)', function($id) {
    $selectedWeek = $id;
    $siteTitle = 'NFL';
    $content = '../templates/nfl-scores.php';
    include('../templates/base.php');
});

Route::run('/');
?>