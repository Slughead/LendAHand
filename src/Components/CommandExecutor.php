<?php

namespace LendAHand\Components;

/**
 * Class used to execute CLI commands
 */
class CommandExecutor {
	
	private $env;
	
	public function __construct(Environment $env) {
		$this->env = $env; // I might have use of this at some point, I am sure...
	}
	
	/**
	 * Alias for the method execInBackground() to make it easier to use
	 *
	 * @return null
	 */
	public function runInBg($cmd) {
		$this->execInBackground($cmd);
	}
	
	/**
	 * Method for running commands in the background
	 *
	 * @return null
	 */
	public function execInBackground($cmd) {
		if (substr(php_uname(), 0, 7) === "Windows") {
			pclose(popen("start /B " . $cmd, "r"));
		} else {
			exec($cmd . " > /dev/null &");
		}
	}
}