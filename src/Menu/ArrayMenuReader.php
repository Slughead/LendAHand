<?php

namespace LendAHand\Menu;

class ArrayMenuReader implements MenuReader {
	
	public function readMenu() {
		return [
			['href' => '/edsa-Lend%20A%20Hand/', 'text' => 'Homepage'],
		];
	}
}