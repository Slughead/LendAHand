<?php

namespace LendAHand\Controllers;

use \Http\Response;
use \LendAHand\Template\Renderer;
use \LendAHand\Page\PageReader;

class Page {
	
	private $response;
	private $renderer;
	private $pageReader;
	
	public function __construct(
		Response $response,
		Renderer $renderer,
		PageReader $pageReader
	) {
		$this->response = $response;
		$this->renderer = $renderer;
		$this->pageReader = $pageReader;
    }
	
    public function show($params) {
		$slug = $params['slug'];
		$data['content'] = $this->pageReader->readBySlug($slug);
		$html = $this->renderer->render('Page', $data);
		$this->response->setContent($html);
    }
}