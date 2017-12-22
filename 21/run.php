#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$enhancements = array();
	foreach ($input as $details) {
		preg_match('#(.*) => (.*)#SADi', $details, $m);
		list($all, $start, $end) = $m;

		foreach (getPossibilities(stringToBlock($start)) as $possibility) {
			$enhancements[$possibility] = $end;
		}
	}

	$start = [['.', '#', '.'], ['.', '.', '#'], ['#', '#', '#']];

	function rotateBlock($block, $times = 1) {
		$rotate = $block;

		for ($t = 0; $t < $times; $t++) {
			for ($i = 0; $i < count($block); $i++) {
				for ($j = 0; $j < count($block[$i]); $j++) {
					$i2 = $j;
					$j2 = count($block[$i]) - 1 - $i;

					$rotate[$i2][$j2] = $block[$i][$j];
				}
			}
			$block = $rotate;
		}

		return $rotate;
	}

	function flipBlock($block) {
		$flip = [];

		for ($i = 0; $i < count($block); $i++) {
			$flip[$i] = array_reverse($block[$i]);
		}

		return $flip;
	}

	function displayBlock($block) {
		foreach ($block as $row) { echo implode('', $row), "\n"; }
	}

	function blockToString($block) {
		$rows = [];
		foreach ($block as $row) { $rows[] = implode('', $row); }

		return implode('/', $rows);
	}

	function stringToBlock($blockstring) {
		$block = [];
		$rows = explode('/', $blockstring);
		foreach ($rows as $row) { $block[] = str_split($row); }

		return $block;
	}

	function getPossibilities($block) {
		// Find all possibilities for this block.
		$possibilities = [];

		for ($i = 0; $i < 4; $i++) {
			$rb = rotateBlock($block, $i);
			$possibilities[] = blockToString($rb);
			$possibilities[] = blockToString(flipBlock($rb));
		}
		$possibilities = array_unique($possibilities);

		return $possibilities;
	}


	function getBlocks($block) {
		$blocks = [];
		$split = (count($block) % 2 == 0) ? 2 : 3;

		for ($i = 0; $i < count($block); $i += $split) {
			for ($j = 0; $j < count($block); $j += $split) {
				$thisBlock = [];
				foreach (yieldXY(0, 0, $split-1, $split-1) as $x => $y) {
					$thisBlock[$y][$x] = $block[$y + $i][$x + $j];
				}
				$blocks[] = $thisBlock;
			}
		}

		return $blocks;
	}

	function joinBlocks($blocks) {
		$rows = [];

		$size = sqrt(count($blocks));

		for ($i = 0; $i < count($blocks); $i++) {
			$blockRow = floor($i / $size);
			$blockCol = ($i % $size);
			$blockSize = count($blocks[$i]);

			foreach (yieldXY(0, 0, $blockSize - 1, $blockSize - 1) as $x => $y) {
				$rows[($blockSize * $blockRow) + $y][($blockSize * $blockCol) + $x] = $blocks[$i][$y][$x];
			}
		}

		return $rows;
	}

	function enhance($block) {
		global $enhancements;
		$str = blockToString($block);

		if (isset($enhancements[$str])) {
			return stringToBlock($enhancements[$str]);
		}

		die('Unable to enhance: ' . blockToString($block) . "\n");
	}

	$block = $start;
	// if (isDebug()) { displayBlock($block); echo "\n"; }

	for ($i = 0; $i < (isTest() ? 2 : 5); $i++) {
		if (isDebug()) { echo $i, "\n"; }

		$blocks = getBlocks($block);
		foreach ($blocks as &$b) { $b = enhance($b); }
		$block = joinBlocks($blocks);
	}

	echo 'Part 1: ', substr_count(blockToString($block), '#'), "\n";

	if (!isTest()) {
		for ($i = $i; $i < 18; $i++) {
			if (isDebug()) { echo $i, "\n"; }

			$blocks = getBlocks($block);
			foreach ($blocks as &$b) { $b = enhance($b); }
			$block = joinBlocks($blocks);
		}
	}

	echo 'Part 2: ', substr_count(blockToString($block), '#'), "\n";
