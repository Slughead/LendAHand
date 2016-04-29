<?php

namespace LendAHand\Template;

interface Renderer {
    
	public function render($template, $data = []);
}