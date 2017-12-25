#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');

	$input = getInputLines();
	$components = [];
	foreach ($input as $details) {
		preg_match('#(.*)/(.*)#SADi', $details, $m);
		list($all, $left, $right) = $m;
		$components[] = ['pins' => [$left, $right], 'score' => ($left + $right)];
	}

	function findComponents($pinCount, $excludes = []) {
		global $components;

		$results = [];

		foreach ($components as $c => $info) {
			if (in_array($pinCount, $info['pins']) && !in_array($c, $excludes)) {
				$results[] = $c;
			}
		}

		return $results;
	}

	$finishedBridges = [];
	$possibleBridges = new SplDoublyLinkedList();


	// Starting Blocks.
	foreach (findComponents(0) as $b) { $possibleBridges->add($possibleBridges->count(), ['bridge' => [$b], 'lastConnector' => 0]); }

	while ($possibleBridges->count() > 0) {
		$current = $possibleBridges->shift();

		// Store valid possible bridge;
		$finishedBridges[] = $current;

		// Get possible future connectors
		$lastElement = $components[$current['bridge'][count($current['bridge']) - 1]]['score'];
		$nextConnector = $lastElement - $current['lastConnector'];
		$connections = findComponents($nextConnector, $current['bridge']);

		foreach ($connections as $c) {
			$possibleBridges->add($possibleBridges->count(), ['bridge' => array_merge($current['bridge'], [$c]), 'lastConnector' => $nextConnector]);
		}
	}

	$part1 = $longest = $part2 = 0;
	foreach ($finishedBridges as $bridge) {
		$score = array_reduce($bridge['bridge'], function($c, $i) use ($components) { return $c + $components[$i]['score']; }, 0);

		if (count($bridge['bridge']) > $longest) {
			$part2 = $score;
			$longest = count($bridge);
		} else if (count($bridge['bridge']) == $longest) {
			$part2 = max($part2, $score);
			$longest = count($bridge);
		}

		$part1 = max($part1, $score);

		if (isDebug()) {
			echo 'Bridge: ', bridgeInfo($bridge['bridge']), ' => ', $score, "\n";
		}
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
