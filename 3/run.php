#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();

	function getLocation($input) {
		$x = $y = 0;
		$delta = [0, -1];

		for ($i = 1; $i <= $input; $i++) {
			if ($input == $i) {
				return [$x, $y];
			}

			// Calculate next location
			// Based on https://stackoverflow.com/a/13413224
			if ($x === $y || ($x < 0 && $x === -$y) || ($x > 0 && $x === 1-$y)){
				// Change Direction
				$delta = [-$delta[1], $delta[0]];
			}

			$x += $delta[0];
			$y += $delta[1];
		}
	}

	function getHigherValue($input) {
		$grid = [];
		$x = $y = 0;
		$delta = [0, -1];

		while (true) {
			$grid[$y][$x] = 0;
			for ($i = -1; $i <= 1; $i++) {
				for ($j = -1; $j <= 1; $j++) {
					if ($i == 0 && $j == 0) { continue; }

					if (isset($grid[$y + $i][$x + $j])) {
						$grid[$y][$x] += $grid[$y + $i][$x + $j];
					}
				}
			}
			if ($grid[$y][$x] == 0) { $grid[$y][$x] = 1; }

			if ($grid[$y][$x] > $input) { return $grid[$y][$x]; }

			// Calculate next location
			// Based on https://stackoverflow.com/a/13413224
			if ($x === $y || ($x < 0 && $x === -$y) || ($x > 0 && $x === 1-$y)){
				// Change Direction
				$delta = [-$delta[1], $delta[0]];
			}

			$x += $delta[0];
			$y += $delta[1];
		}
	}

	$loc = getLocation($input);
	echo 'Part 1: ', (abs($loc[0]) + abs($loc[1])), "\n";

	$ans = getHigherValue($input);
	echo 'Part 2: ', $ans, "\n";
