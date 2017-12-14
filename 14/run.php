#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/../10/KnotHash.php');
	$input = getInputLine();

	$rows = [];

	for ($i = 0; $i < 128; $i++) {
		$rows[$i] = '';
		foreach (str_split(KnotHash::getHash($input . '-' . $i)) as $hex) {
			$rows[$i] .= str_pad(base_convert($hex, 16, 2), 4, '0', STR_PAD_LEFT);
		}

		$rows[$i] = str_replace('0', '.', $rows[$i]);
		$rows[$i] = str_replace('1', '#', $rows[$i]);
		$rows[$i] = str_split($rows[$i]);
	}

	$part1 = array_reduce($rows, function ($c, $i) { return ($c += substr_count(implode('', $i), '#')); }, 0);
	echo 'Part 1: ', $part1, "\n";

	function setRegion(&$rows, $x, $y, $region) {
		if ($rows[$y][$x] != '#') { return; }
		$rows[$y][$x] = $region;

		foreach ([[$x+1, $y], [$x-1, $y], [$x, $y+1], [$x, $y-1]] as [$x2, $y2]) {
			if (isset($rows[$y2][$x2]) && $rows[$y2][$x2] == '#') {
				setRegion($rows, $x2, $y2, $region);
			}
		}
	}

	$currentRegion = 1;
	foreach (yieldXY(0, 0, 127, 127) as $x => $y) {
		if ($rows[$y][$x] == '#') {
			setRegion($rows, $x, $y, $currentRegion);
			$currentRegion++;
		}
	}

	echo 'Part 2: ', ($currentRegion - 1), "\n";
