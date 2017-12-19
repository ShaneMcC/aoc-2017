#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$map = getInputLines();
	$max = ['x' => 0, 'y' => count($map)];
	$pos = ['x' => 0, 'y' => 0];

	foreach ($map as &$line) { $line = str_split($line); $max['x'] = max($max['x'], count($line)); }

	// Find starting position.
	for ($x = 0; $x < $max['x']; $x++) {
		if (isset($map[$pos['y']][$x]) && $map[$pos['x']][$x] == '|') {
			$pos['x'] = $x;
		}
	}

	$directions = [['x' => 0, 'y' => 1],
	               ['x' => 0, 'y' => -1],
	               ['x' => -1, 'y' => 0],
	               ['x' => 1, 'y' => 0]];
	$direction = $directions[0];

	$last = $pos;
	$collected = [];
	$steps = 0;

	drawState($map, $pos, $max, $collected, $steps, false);

	while (isset($map[$pos['y']][$pos['x']]) && !empty(trim($map[$pos['y']][$pos['x']]))) {
		$steps++;
		drawState($map, $pos, $max, $collected, $steps, true);

		if ($map[$pos['y']][$pos['x']] != '+') {
			if (!in_array($map[$pos['y']][$pos['x']], ['|', '-', '+'])) { $collected[] = $map[$pos['y']][$pos['x']]; }
		} else {
			// Find a new direction.
			foreach ($directions as $newDir) {
				$new = ['x' => $pos['x'] + $newDir['x'], 'y' => $pos['y'] + $newDir['y']];
				if (isset($map[$new['y']][$new['x']]) && $new != $last && !empty(trim($map[$new['y']][$new['x']]))) {
					$direction = $newDir;
				}
			}
		}

		// Keep Moving in the current direction.
		$last = $pos;
		$pos['x'] += $direction['x'];
		$pos['y'] += $direction['y'];
	}
	drawState($map, $pos, $max, $collected, $steps, true);

	echo 'Part 1: ', implode('', $collected), "\n";
	echo 'Part 2: ', $steps, "\n";
