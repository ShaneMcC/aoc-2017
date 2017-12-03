#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();

	function yieldSpiral() {
		$x = $y = 0;
		$delta = [0, -1];

		while (true) {
			yield $x => $y;

			// Calculate next location
			// Based on https://stackoverflow.com/a/13413224
			if ($x === $y || ($x < 0 && $x === -$y) || ($x > 0 && $x === 1-$y)) {
				// Change Direction
				$delta = [-$delta[1], $delta[0]];
			}

			$x += $delta[0];
			$y += $delta[1];
		}
	}

	function getLocation($input) {
		$count = 1;
		foreach (yieldSpiral() as $x => $y) {
			if ($count++ == $input) {
				return [$x, $y];
			}
		}
	}

	function getHigherValue($input) {
		$grid = [];

		foreach (yieldSpiral() as $x => $y) {
			$grid[$y][$x] = 0;

			foreach (yieldXY($x - 1, $y - 1, $x + 1, $y + 1) as $x2 => $y2) {
				if ($y2 == $y && $x2 == $x) { continue; }
				$grid[$y][$x] += isset($grid[$y2][$x2]) ? $grid[$y2][$x2] : 0;
			}
			$grid[$y][$x] = max(1, $grid[$y][$x]);

			if ($grid[$y][$x] > $input) {
				return $grid[$y][$x];
			}
		}
	}

	$loc = getLocation($input);
	echo 'Part 1: ', (abs($loc[0]) + abs($loc[1])), "\n";

	$ans = getHigherValue($input);
	echo 'Part 2: ', $ans, "\n";
