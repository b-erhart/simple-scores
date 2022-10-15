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
    $selectedWeek = '';
    $siteTitle = '1. Bundesliga';
    $content = '../templates/bundesliga-scores.php';
    $showScores = false;
    include('../templates/base.php');
});

Route::add('/bundesliga/scores', function() {
    $selectedWeek = '';
    $siteTitle = '1. Bundesliga';
    $content = '../templates/bundesliga-scores.php';
    $showScores = true;
    include('../templates/base.php');
});

Route::add('/bundesliga/standings', function() {
    $siteTitle = '1. Bundesliga';
    $content = '../templates/bundesliga-standings.php';
    include('../templates/base.php');
});

Route::add('/bundesliga/([0-9]*)', function($id) {
    $selectedWeek = $id;
    $siteTitle = '1. Bundesliga';
    $content = '../templates/bundesliga-scores.php';
    include('../templates/base.php');
});

Route::add('/nfl', function() {
    $selectedWeek = '';
    $siteTitle = 'NFL';
    $content = '../templates/nfl-scores.php';
    $showScores = false;
    include('../templates/base.php');
});

Route::add('/nfl/scores', function() {
    $selectedWeek = '';
    $siteTitle = 'NFL';
    $content = '../templates/nfl-scores.php';
    $showScores = true;
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