<?php
$injector = new \Auryn\Injector();
$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
	':get' => $_GET,
	':post' => $_POST,
	':cookies' => $_COOKIE,
	':files' => $_FILES,
	':server' => $_SERVER,
]);
$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');
$injector->delegate(
	'Twig_Environment', 
	function() use ($injector) {
		$loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
		$twig = new Twig_Environment($loader);
		return $twig;
	}
);
$injector->alias('LendAHand\Template\Renderer', 'LendAHand\Template\TwigRenderer');
$injector->alias('LendAHand\Template\FrontendRenderer', 'LendAHand\Template\FrontendTwigRenderer');
/*$injector->alias('LendAHand\Template\Renderer', 'LendAHand\Template\MustacheRenderer');
$injector->define('Mustache_Engine', [
	':options' => [
		'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
			'extension' => '.html'
		]),
	],
]); 
*/
$injector->define('LendAHand\Page\FilePageReader', [
	':pageFolder' => __DIR__ . '/../pages',
]);

$injector->alias('LendAHand\Page\PageReader', 'LendAHand\Page\FilePageReader');
$injector->share('LendAHand\Page\FilePageReader');
$injector->alias('LendAHand\Menu\MenuReader', 'LendAHand\Menu\ArrayMenuReader');
$injector->share('LendAHand\Menu\ArrayMenuReader');
return $injector;