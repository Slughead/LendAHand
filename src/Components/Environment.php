<?php

namespace LendAHand\Components;

/**
 * Simple environment class
 */
class Environment {
	
	const ENV_DEV = 'development';
	const ENV_TEST = 'test';
	const ENV_PROD = 'production';
	
	private $env;
	
	/**
	 * The constructor
	 *
	 * @param string $env The environment to construct
	 *
	 * @return null
	 */
	public function __construct($env = self::ENV_DEV) {
		$allowedEnvValues = [self::ENV_DEV, self::ENV_TEST, self::ENV_PROD];
		if (!in_array($env, $allowedEnvValues, true)) {
			throw new \Exception('Illegal environment set: ' . $env);
		}
		$this->env = $env;
	}
	
	/**
	 * Gets the current environment
	 *
	 * @return string The environment
	 */
	public function getEnvironment() {
		return $this->env;
	}
}