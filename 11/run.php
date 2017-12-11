#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();

	// Hex Grids: https://www.redblobgames.com/grids/hexagons/
	$x = $y = $z = 0;
	$part1 = 0;
	$part2 = 0;

	$directions = explode(',', $input);

	// Flat-Topped Hexes.
	// https://www.redblobgames.com/grids/hexagons/#coordinates-cube
	foreach ($directions as $dir) {
		switch ($dir) {
			case 'nw':
				$y++;
				$x--;
				break;
			case 'n':
				$y++;
				$z--;
				break;
			case 'ne':
				$x++;
				$z--;
				break;
			case 'se':
				$y--;
				$x++;
				break;
			case 's':
				$y--;
				$z++;
				break;
			case 'sw':
				$x--;
				$z++;
				break;
		}
		$part1 = ((abs($x) + abs($y) + abs($z)) / 2);
		$part2 = max($part2, $part1);
	}

	// https://www.redblobgames.com/grids/hexagons/#distances
	// echo 'Final location: [X: ', $x, ', Y: ', $y, ', Z: ', $z, ']', "\n";
	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
