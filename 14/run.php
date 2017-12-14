#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/../10/KnotHash.php');
	$input = getInputLine();

	$rows = [];
	$part1 = 0;
	for ($i = 0; $i < 128; $i++) {
		$row = '';
		foreach (KnotHash::getHash($input . '-' . $i, false) as $val) {
			$row .= str_pad(base_convert($val, 10, 2), 8, '0', STR_PAD_LEFT);
		}

		$row = str_replace('0', '.', $row);
		$row = str_replace('1', '#', $row);
		$part1 += substr_count($row, '#');
		$rows[$i] = str_split($row);
	}

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
