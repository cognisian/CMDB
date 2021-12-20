<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set of Form helper functions to aid in setting input elements
 *
 * @author schalmers
 */
class inputelems_Core {

	/**
	 * Determines if the HTML element should be enabloed or not based on the users
	 * functional area and the device/application functional area.
	 *
	 * @param mixed $test The Business Unit name or array or names to test
	 * for enabling/disabling.
	 * @param array $userFuncAreas The array of business units the user belongs to.
	 * @param bool $enableFlag A flag indicating whether the enabled CSS class
	 * should be outputted if TRUW else the disabled attribute is used.  Default = TRUE.
	 * @return string The form input element attributes to enable/disable an input
	 * element.
	 */
	public static function enable($test, $userFuncAreas, $enableFlag = TRUE) {

		if ($enableFlag) {
			$result = 'disabled';
		}
		else {
			$result = 'disabled=disabled';
		}
		$done = false;

		// If the user does not belong to anything then disable
		if (!isset($userFuncAreas) || empty($userFuncAreas)) {
			return $result;
		}

		// If there is nothing to test then enable
		if (!isset($test) || empty($test)) {
			if ($enableFlag) {
				$result = 'enabled';
			}
			else {
				$result = '';
			}
		}

		// If are testing a single business unit name then convert to array
		$testFuncAreas = array();
		if (!is_array($test)) {
			$testFuncAreas[] = $test;
		}
		else {
			$testFuncAreas = $test;
		}

		foreach ($userFuncAreas as $userFuncArea) {
			// Test each name against list of user's business unit
			foreach ($testFuncAreas as $testFuncArea) {
				if ($userFuncArea->name == $testFuncArea) {
					if ($enableFlag) {
						$result = 'enabled';
					}
					else {
						$result = '';
					}
					$done = TRUE;
					break;
				}
			}

			if ($done) {
				break;
			}
		}

		return $result;
	}
}
?>