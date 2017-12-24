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

	function bridgeInfo($bridge) {
		global $components;

		$score = 0;
		$b = [];
		foreach ($bridge['bridge'] as $bits) {
			$score += $components[$bits]['score'];
			$b[] = implode('/', $components[$bits]['pins']);
		}
		return [implode('--', $b), $score];
	}

	$finishedBridges = [];
	$possibleBridges = [];

	// Starting Blocks.
	foreach (findComponents(0) as $b) { $possibleBridges[] = ['bridge' => [$b], 'lastConnector' => 0]; }

	while (!empty($possibleBridges)) {
		$current = array_shift($possibleBridges);

		// Store valid possible bridge;
		$finishedBridges[] = $current;

		// Get possible future connectors
		$lastElement = $components[$current['bridge'][count($current['bridge']) - 1]]['pins'];
		$nextConnector = ($lastElement[0] == $current['lastConnector']) ? $lastElement[1] : $lastElement[0];
		$connections = findComponents($nextConnector, $current['bridge']);

		foreach ($connections as $c) {
			$possibleBridges[] = ['bridge' => array_merge($current['bridge'], [$c]), 'lastConnector' => $nextConnector];
		}

		debugOut('possibleBridges: ', count($possibleBridges), "\n");
	}


	$part1 = 0;
	$part2_len = 0;
	$part2 = 0;
	foreach ($finishedBridges as $bridge) {
		$bi = bridgeInfo($bridge);

		if (count($bridge['bridge']) > $part2_len) {
			$part2 = $bi[1];
		} else if (count($bridge['bridge']) == $part2_len) {
			$part2 = max($part2, $bi[1]);
		}

		$part1 = max($part1, $bi[1]);
		debugOut('Bridge: ', $bi[0], ' => ', $bi[1], "\n");
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
