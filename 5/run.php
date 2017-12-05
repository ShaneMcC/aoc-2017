#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$instructions = getInputLines();

	function runInstructions($instructions, $part2 = false) {
		$i = $count = 0;
		while ($i < count($instructions)) {
			$i += ($part2 && $instructions[$i] >= 3) ? $instructions[$i]-- : $instructions[$i]++;
			$count++;
		}
		return $count;
	}

	echo 'Part 1: ', runInstructions($instructions), "\n";
	echo 'Part 2: ', runInstructions($instructions, true), "\n";
