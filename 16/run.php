#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();
	$dance = explode(',', $input);

	$line = [];
	for ($i = 0; $i < (isTest() ? 5 : 16); $i++) { $line[] = chr(97 + $i); }
	$line = implode('', $line);

	function doDance($line, $dance) {
		$line = str_split($line);
		foreach ($dance as $step) {
			if (preg_match('#s([0-9]+)#', $step, $m)) {
				$len = count($line);
				array_splice($line, 0, 0, array_slice($line, $len - $m[1]));
				$line = array_slice($line, 0, $len);
			} else if (preg_match('#([xp])(.+)/(.+)#', $step, $m)) {
				$a = ($m[1] == 'x') ? $m[2] : array_search($m[2], $line);
				$b = ($m[1] == 'x') ? $m[3] : array_search($m[3], $line);

				$line[$a] ^= $line[$b] ^= $line[$a] ^= $line[$b];
			}
		}
		return implode('', $line);
	}

	function multiDance($line, $dance, $times) {
		$known = [];
		for ($i = 0; $i < $times; $i++) {
			if ($loopback = array_search($line, $known)) {
				$loopsize = ($i - 1) - ($loopback - 1);
				return $known[(($times - $i) % $loopsize) + $loopback];
			} else {
				$known[] = $line;
				$line = doDance($line, $dance);
			}
		}

		return $line;

	}

	echo 'Part 1: ', multiDance($line, $dance, 1), "\n";
	echo 'Part 2: ', multiDance($line, $dance, 1000000000), "\n";
