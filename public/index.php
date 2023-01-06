<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// disable error display for production
// $app->addErrorMiddleware(false, true, true);

// create Twig
$twig = Twig::create('../templates', ['cache' => false]);

// add Twig-View middleware
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'base.html.twig', [
        'title' => 'Home',
        'text' => 'Welcome to simple scores...',
    ]);
});

$app->run();