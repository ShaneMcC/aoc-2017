#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/../10/KnotHash.php');
	$input = getInputLines();

	$generator = array();
	// Get data for each program
	foreach ($input as $details) {
		preg_match('#Generator ([AB]) starts with ([0-9]+)#SADi', $details, $m);
		list($all, $name, $start) = $m;
		$generator[$name] = ['start' => (int)$start, 'value' => (int)$start];
	}

	$generator['A']['factor'] = 16807;
	$generator['B']['factor'] = 48271;

	function getMatches($generator, $part2 = false) {
		$divider = 2147483647;

		$generator['A']['multiple'] = ($part2) ? 4 : 1;
		$generator['B']['multiple'] = ($part2) ? 8 : 1;

		$matches = 0;
		for ($i = 0; $i < (isTest() ? 5 : ($part2 ? 5000000 : 40000000)); $i++) {
			foreach ($generator as $name => &$info) {
				do {
					$info['value'] = ($info['value'] * $info['factor']) % $divider;
				} while ($info['value'] % $info['multiple'] != 0);
			}

			$a = $generator['A']['value'] & 65535;
			$b = $generator['B']['value'] & 65535;
			if ($a == $b) { $matches++; }
			if (isDebug() && $i > 0 && $i % 1000000 == 0) { echo $i, "\n"; }
		}

		return $matches;
	}

	echo 'This will take a while...', "\n";
	$part1 = getMatches($generator);
	echo 'Part 1: ', $part1, "\n";
	$part2 = getMatches($generator, true);
	echo 'Part 2: ', $part2, "\n";

