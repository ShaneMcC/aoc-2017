#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$programs = array();
	// Get data for each program
	foreach ($input as $details) {
		preg_match('#(.*): (.*)?#SADi', $details, $m);
		list($all, $depth, $range) = $m;
		$programs[$depth] = $range;
	}

	function getSeverity($programs, $delay = 0, $exitIfCaught = false) {
		$severity = 0;

		foreach ($programs as $depth => $range) {
			$scannerPos = ($depth + $delay) % (($range - 1) * 2);
			if ($scannerPos == 0) {
				if ($exitIfCaught) { return -1; }

				$severity += ($depth * $range);
			}
		}

		return $severity;
	}

	echo 'Part 1: ', getSeverity($programs, 0), "\n";
	$i = 0;
	while (getSeverity($programs, ++$i, true) == -1);
	echo 'Part 2: ', $i, "\n";
