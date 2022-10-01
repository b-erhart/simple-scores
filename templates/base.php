<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <title><?=$siteTitle?> - Simple Scores</title>
        
        <link rel="stylesheet" href="/assets/css/styles.css"/>
        <link rel="icon" type="image/x-icon" href="/favicon.png"/>
    </head>
    <body>
        <header>
            <nav>
                <div class="nav-items">
                    <input type="checkbox" id="nav-hamburger-toggle">
                    <div class="nav-left">
                        <a href="/" class="nav-brand">Simple Scores</a>
                    </div>
                    <div class="nav-right nav-menu">
                        <a href="/" <?=($siteTitle == 'Home') ? 'class="nav-active"' : ''?>>
                            <i class="icon-house"></i>&nbsp;Home
                        </a>
                        <a href="/bundesliga" <?=($siteTitle == '1. Bundesliga') ? 'class="nav-active"' : ''?>>
                            <i class="icon-futbol"></i>&nbsp;Bundesliga
                        </a>
                        <a href="/nfl" <?=($siteTitle == 'NFL') ? 'class="nav-active"' : ''?>>
                        <i class="icon-football"></i>&nbsp;NFL
                        </a>
                    </div>
                    <label for="nav-hamburger-toggle" class="nav-hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </label>
                </div>
            </nav>
<?php include($content) ?>
    </body>
</html>