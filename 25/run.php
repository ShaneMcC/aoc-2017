#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	function parseRules($input) {
		$begin = NULL;
		$checksum = NULL;

		$rules = [];

		$currentState = NULL;
		$currentValue = NULL;
		$currentRule = NULL;
		foreach ($input as $details) {
			if (preg_match('#Begin in state (.*).#SADi', $details, $m)) {
				$begin = $m[1];
			} else if (preg_match('#Perform a diagnostic checksum after (.*) steps#SADi', $details, $m)) {
				$checksum = $m[1];
			} else if (preg_match('#In state (.*):#SADi', $details, $m)) {
				if ($currentValue != NULL) {
					$rules[$currentState][$currentValue] = $currentRule;
					$currentValue = null;
				}

				$currentState = $m[1];
			} else if (preg_match('#^$#SADi', $details, $m)) {
				$rules[$currentState][$currentValue] = $currentRule;

				$currentState = NULL;
				$currentValue = NULL;
				$currentRule = NULL;
			} else if ($currentState != NULL) {
				if (preg_match('#\s+If the current value is (.*):#SADi', $details, $m)) {
					if ($currentValue != NULL) {
						$rules[$currentState][$currentValue] = $currentRule;
					}

					$currentValue = $m[1];
					$currentRule = ['write' => NULL, 'move' => NULL, 'continue' => NULL];
				} else if (preg_match('#\s+- Write the value (.*).#SADi', $details, $m)) {
					$currentRule['write'] = $m[1];
				} else if (preg_match('#\s+- Move one slot to the (.*).#SADi', $details, $m)) {
					$currentRule['move'] = $m[1] == 'right' ? 1 : -1;
				} else if (preg_match('#\s+- Continue with state (.*).#SADi', $details, $m)) {
					$currentRule['continue'] = $m[1];
				}
			}
		}

		if ($currentState != NULL) {
			$rules[$currentState][$currentValue] = $currentRule;
		}

		return [$begin, $checksum, $rules];
	}

	list($begin, $checksum, $rules) = parseRules($input);

	$position = 0;
	$tape = [0 => 0];

	$currentState = $begin;
	for ($i = 0; $i < $checksum; $i++) {
		$currentValue = isset($tape[$position]) ? $tape[$position] : 0;
		$rule = $rules[$currentState][$currentValue];

		$tape[$position] = $rule['write'];
		$currentState = $rule['continue'];
		$position += $rule['move'];
	}

	echo 'Part 1: ', array_sum(array_values($tape)), "\n";
