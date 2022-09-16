<?php
$requestUri = $_SERVER['REQUEST_URI'];

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
}
?>