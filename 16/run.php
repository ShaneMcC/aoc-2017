#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();
	$dance = explode(',', $input);

	$line = [];

	for ($i = 0; $i < (isTest() ? 5 : 16); $i++) { $line[] = chr(97 + $i); }

	function doDance($line, $dance) {
		foreach ($dance as $step) {
			if (preg_match('#s([0-9]+)#', $step, $m)) {
				$len = count($line);
				$end = array_slice($line, $len - $m[1]);
				array_splice($line, 0, 0, $end);
				$line = array_slice($line, 0, $len);
			} else if (preg_match('#x([0-9]+)/([0-9]+)#', $step, $m)) {
				$a = $m[1];
				$b = $m[2];

				$line[$a] ^= $line[$b];
				$line[$b] ^= $line[$a];
				$line[$a] ^= $line[$b];
			} else if (preg_match('#p(.+)/(.+)#', $step, $m)) {
				$a = array_search($m[1], $line);
				$b = array_search($m[2], $line);

				$line[$a] ^= $line[$b];
				$line[$b] ^= $line[$a];
				$line[$a] ^= $line[$b];
			}
		}
		return $line;
	}

	// Get all possible cycles of the dance
	$known = [];
	while (array_search(implode('', $line), $known) === FALSE) {
		$known[] = implode('', $line);
		$line = doDance($line, $dance);
	}

	echo 'Part 1: ', $known[1], "\n";
	echo 'Part 2: ', $known[1000000000 % count($known)], "\n";
