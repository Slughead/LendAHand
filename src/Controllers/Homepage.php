<?php

namespace LendAHand\Controllers;

use \Http\Response;

class Homepage {
	
	private $response;
	
	public function __construct(Response $response) {
		$this->response = $reponse;
	}
	
	public function show() {
		$this->response->setContent('Now we\'re talking :D');
	}
}