#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();

	$buffer = [0];
	$position = 0;

	for ($i = 1; $i <= 2017; $i++) {
		$position = (($position + $input) % count($buffer)) + 1;

		array_splice($buffer, $position, 0, [$i]);
	}

	echo 'Part 1: ', $buffer[$position + 1], "\n";

	$part2 = 0;
	for ($i = 1; $i <= 50000000; $i++) {
		$position = (($position + $input) % $i) + 1;

		if ($position == 1) { $part2 = $i; }
		if (isDebug() && $i > 0 && $i % 1000000 == 0) { echo $i, "\n"; }
	}

	echo 'Part 2: ', $part2, "\n";
