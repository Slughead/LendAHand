<?php

namespace LendAHand;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$environment = 'development';

/**
* Register the error handler
*/
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e) {
        echo 'Friendly error page and send an email to the developer'; //TODO!
    });
}
$whoops->register();

/**
 * HTTP handling
 */
// $request = new \Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
// $response = new \Http\HttpResponse();

// $content = '<h1>Hello World</h1>';
// $response->setContent($content);
// $response->setContent('Some 404 error page...');
// $response->setStatusCode(404);

/**
 * Route handling
 */
 
 //TESTS
$dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', function() {
		echo "/users route hit!";
	});
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\d+}', function() {
		echo "specific user route hit";
	});
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', function() {
		echo "specific article route hit";
	});
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
		$handler($vars);
        // ... call $handler with $vars
        break;
}

/*
foreach ($response->getHeaders() as $header) {
    header($header, false);
}
echo $response->getContent();
*/