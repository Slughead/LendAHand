<?php

namespace LendAHand;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

include_once('Aliases.php');
$injector = include_once('Dependencies.php');
// $injector->defineParam('env', 'production'); // use this when a production env should be used
// $injector->defineParam('env', 'test'); // use this when a test env should be used
$envHandler = $injector->make('LendAHand\Environment');
$environment = $envHandler->getEnvironment();

/**
* Register the error handler
*/
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    // $whoops->pushHandler(function($e) {
        // echo 'Friendly error page and send an email to the developer'; //TODO!
    // });
}
$whoops->register();

/**
 * Compile LESS if on develop
 */
if ($environment === 'development') {
	$executor = $injector->make('LendAHand\Executor');
	$lessFolder = __DIR__ . DS . 'Less' . DS;
	$cssFolder = __DIR__ . DS . '..' . DS . 'public' . DS . 'css' . DS;
	$cmd = 'lessc ' . $lessFolder . 'base.less ' . $cssFolder . 'style.css';
	// $cmd should result in something like this:
	// lessc D:\Programming\Projects\LendAHand\src\Less\base.less D:\Programming\Projects\LendAHand\src\..\public\css\style.min.css
	$executor->runInBg($cmd);
	$minimizer = $injector->make('\LendAHand\CssMinimizer');
	$minimizer->minimizeFile($cssFolder . 'style.css', $cssFolder . 'style.min.css');
}

/**
 * HTTP handling, part 1 / 2, and creation of the DIC
 */
$request = $injector->make('Http\HttpRequest'); // shouldn't this just be Http\Request due to the alias?
$response = $injector->make('Http\HttpResponse'); // likewise here...
// $request = new \Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
// $response = new \Http\HttpResponse();

/**
 * Route handling
 */
$routeDefinitionCallback = function (\FastRoute\RouteCollector $routeCollector) {
	$routes = include_once('Routes.php');
	foreach ($routes as $route) {
		$routeCollector->addRoute($route[0], $route[1], $route[2]);
	}
};

if ($environment !== 'production') {
	$relativePath = substr($request->getPath(), 15);
} else {
	$relativePath = $request->getPath();
}

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
$routeInfo = $dispatcher->dispatch($request->getMethod(), $relativePath);
// $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

switch ($routeInfo[0]) {
	case \FastRoute\Dispatcher::NOT_FOUND:
		$response->setContent('404 - Page not found');
		$response->setStatusCode(404);
		break;
	case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$response->setContent('405 - Method not allowed');
		$response->setStatusCode(405);
		break;
	case \FastRoute\Dispatcher::FOUND:
		$className = $routeInfo[1][0];
		$method = $routeInfo[1][1];
		$vars = $routeInfo[2];
		$class = $injector->make($className);
		$class->$method($vars);
		break;
	default:
		throw new \Exception('Unsupported route');
		break;
}

/**
 * HTTP handling, part 2 / 2
 */
foreach ($response->getHeaders() as $header) {
	header($header, false);
}
echo $response->getContent();