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
				array_splice($line, 0, 0, array_slice($line, $len - $m[1]));
				$line = array_slice($line, 0, $len);
			} else if (preg_match('#([xp])(.+)/(.+)#', $step, $m)) {
				$a = ($m[1] == 'x') ? $m[2] : array_search($m[2], $line);
				$b = ($m[1] == 'x') ? $m[3] : array_search($m[3], $line);

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
