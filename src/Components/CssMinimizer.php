<?php

namespace LendAHand\Components;

/**
 * Minimize CSS
 */
class CssMinimizer {

	/**
	 * Minimizes a css string
	 *
	 * @param string  $buffer The string to minimize
	 * @param boolean $print  If true, the result is printed to screen.
	 * @param integer $ttl    Time to live - applicable when $print === true only.
	 *
	 * @return null|string null if $print === true, otherwise the minimized string
	 */
	public function minimize($buffer, $print = false, $ttl = 86400) {
		// Remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

		// Remove space after colons
		$buffer = str_replace(': ', ':', $buffer);

		// Remove whitespace
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);

		// Collapse adjacent spaces into a single space
		$buffer = preg_replace(" {2,}", ' ', $buffer);

		// Remove spaces that might still be left where we know they aren't needed
		$buffer = str_replace(array('} '), '}', $buffer);
		$buffer = str_replace(array('{ '), '{', $buffer);
		$buffer = str_replace(array('; '), ';', $buffer);
		$buffer = str_replace(array(', '), ',', $buffer);
		$buffer = str_replace(array(' }'), '}', $buffer);
		$buffer = str_replace(array(' {'), '{', $buffer);
		$buffer = str_replace(array(' ;'), ';', $buffer);
		$buffer = str_replace(array(' ,'), ',', $buffer);
		
		if ($print === true) {
			// Enable GZip encoding.
			ob_start("ob_gzhandler");
			
			// Enable caching
			header('Cache-Control: public');
			
			// Expire in one day
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $ttl) . ' GMT');
			
			// Set the correct MIME type, because Apache won't set it for us
			header("Content-type: text/css");
			
			// Write everything out
			echo($buffer);
		} else {
			return $buffer;
		}
	}
	
	/**
	 * Loads css from a file and minimizes it
	 *
	 * @param string $sourcePath      The path to the source css file
	 * @param string $destinationPath Path to a destination file. 
	 *                                If not set, the result is printed to screen.
	 * @param integer $ttl            Time to live for the printed css, if chosen.
	 *
	 * @return null
	 */
	public function minimizeFile($sourcePath, $destinationPath = null, $ttl = 86400) {
		$buffer = file_get_contents($sourcePath);
		if ($destinationPath === null) { // no destination = print to screen
			$this->minimize($buffer, true, $ttl);
		} else {
			file_put_contents($destinationPath, $this->minimize($buffer));
		}
	}
}