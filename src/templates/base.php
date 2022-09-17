<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <title><?=$siteTitle?> - Simple Scores</title>

        <link rel="stylesheet" href="/assets/css/bulmaswatch-lumen.min.css"/>
        <link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico"/>
    </head>
    <body>
        <nav class="navbar is-primary" role="navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="/"><strong>Simple Scores</strong></a>
                <a class="navbar-burger" id="navbar-burger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>
            <div class="navbar-menu navbar-end" id="navbar-menu">
                <a class="navbar-item" href="/">Home</a>
                <a class="navbar-item" href="/bundesliga">Bundesliga</a>
                <a class="navbar-item" href="/nfl">NFL</a>
            </div>
            <script>
                const navbarBurgerIcon = document.querySelector("#navbar-burger-icon");
                const navbarMenu = document.querySelector("#navbar-menu");

                navbarBurgerIcon.addEventListener('click', () => {
                    navbarBurgerIcon.classList.toggle('is-active');
                    navbarMenu.classList.toggle('is-active');
                });
            </script>
        </nav>
        <section class="section">
            <div class="container">
<?php include($content) ?>
            </div>
        </section>
    </body>
</html>