#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();

	$current = $total = $garbageTotal = 0;
	$cancelled = $inGarbage = FALSE;

	foreach (str_split($input) as $c) {
		if ($cancelled) { $cancelled = false; }
		else if ($c == '!') { $cancelled = true; }
		else if ($c == '>') { $inGarbage = false; }
		else if ($inGarbage) { $garbageTotal++; }
		else if (!$inGarbage && $c == '<') { $inGarbage = true; }
		else if (!$inGarbage && $c == '{') { $current++; }
		else if (!$inGarbage && $c == '}') { $total += $current--; }
	}

	echo 'Part 1: ', $total, "\n";
	echo 'Part 2: ', $garbageTotal, "\n";
