#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$map = getInputLines();
	$max = [0, count($map)];
	$pos = [0, 0];

	foreach ($map as &$line) { $line = str_split($line); $max[0] = max($max[0], count($line)); }

	// Find starting position.
	for ($x = 0; $x < $max[0]; $x++) {
		if (isset($map[$pos[1]][$x]) && $map[$pos[1]][$x] == '|') {
			$pos[0] = $x;
		}
	}

	$direction = [0, 1];
	$last = $pos;
	$part1 = [];
	$part2 = 0;

	while ($pos[0] >= 0 && $pos[1] >= 0 && $pos[0] < $max[0] && $pos[1] < $max[1] && !empty(trim($map[$pos[1]][$pos[0]]))) {
		$part2++;

		if ($map[$pos[1]][$pos[0]] != '+') {
			if (!in_array($map[$pos[1]][$pos[0]], ['|', '-', '+'])) { $part1[] = $map[$pos[1]][$pos[0]]; }
		} else {
			// Find a new direction.
			foreach ([[0, -1], [0, 1], [-1, 0], [1, 0]] as $newDir) {
				$new = [$pos[0] + $newDir[0], $pos[1] + $newDir[1]];
				if (isset($map[$new[1]][$new[0]]) && $new != $last && !empty(trim($map[$new[1]][$new[0]]))) {
					$direction = $newDir;
				}
			}
		}

		// Keep Moving in the current direction.
		$last = $pos;
		$pos[1] += $direction[1];
		$pos[0] += $direction[0];
	}

	echo 'Part 1: ', implode('', $part1), "\n";
	echo 'Part 2: ', $part2, "\n";
