#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();

	$current = $total = $garbageTotal = 0;

	$cancelled = $inGarbage = FALSE;

	foreach (str_split($input) as $c) {
		debugOut($c, ': ');

		if ($cancelled) {
			debugOut('CANCELLED', "\n");
			$cancelled = false;
		} else if ($c == '!') {
			debugOut('NEXT CANCELLED', "\n");
			$cancelled = true;
		} else if (!$inGarbage && $c == '<') {
			debugOut('ENTER GARBAGE', "\n");
			$inGarbage = true;
		} else if ($c == '>') {
			debugOut('EXIT GARBAGE', "\n");
			$inGarbage = false;
		} else if (!$inGarbage && $c == '{') {
			$current++;
			debugOut('NEW GROUP: ', $current, "\n");
		} else if (!$inGarbage && $c == '}') {
			debugOut('END GROUP: ', $current, "\n");
			$total += $current;
			$current--;
		} else if ($inGarbage) {
			debugOut('GARBAGE', "\n");
			$garbageTotal++;
		} else {
			debugOut("\n");
		}
	}

	echo 'Part 1: ', $total, "\n";
	echo 'Part 2: ', $garbageTotal, "\n";
