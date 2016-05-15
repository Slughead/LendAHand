<?php

namespace LendAHand\Menu;

use \LendAHand\Environment;

class ArrayMenuReader implements MenuReader {
	
	private $env; 
	
	public function __construct(Environment $env) {
		$this->env = $env;
	}
	
	public function readMenu() {
		switch ($this->env->getEnvironment()) {
			case 'development':
				$basePath = '/edsa-lendahand/';
				break;
			case 'test':
				$basePath = '/'; //TODO: Fix this if needed later on...
				break;
			case 'production':
				$basePath = '/';
				break;
			default: 
				throw new \Exception('Unhandled environment!');
		}
		return [
			// ['href' => '/edsa-Lend%20A%20Hand/', 'text' => 'Homepage'],
			['href' => '/edsa-lendahand/', 'text' => 'Homepage'],
		];
	}
}