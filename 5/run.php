#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$instructions = getInputLines();

	function runInstructions($instructions, $part2 = false) {
		$count = 0;
		for ($i = 0; $i < count($instructions); ) {
			$diff = $part2 ? ($instructions[$i] >= 3 ? -1 : 1) : 1;
			$instructions[$i] += $diff;
			$i += $instructions[$i] - $diff;
			$count++;
		}
		return $count;
	}

	echo 'Part 1: ', runInstructions($instructions), "\n";
	echo 'Part 2: ', runInstructions($instructions, true), "\n";
