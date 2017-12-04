#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$passwords = getInputLines();

	$part1 = $part2 = 0;

	foreach ($passwords as $password) {
		$password = preg_split("#\s+#", $password);
		if (array_unique($password) == $password) { $part1++; }

		foreach ($password as &$word) { $word = implode('', sorted('sort', str_split($word))); }
		if (array_unique($password) == $password) { $part2++; }
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
