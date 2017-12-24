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

	function findBridges($connector, $current = []) {
		global $components;

		$possible = findComponents($connector, $current);
		$result = empty($current) ? [] : [$current];

		foreach ($possible as $p) {
			$nextConnector = ($components[$p]['pins'][0] == $connector) ? $components[$p]['pins'][1] : $components[$p]['pins'][0];
			$result = array_merge($result, findBridges($nextConnector, array_merge($current, [$p])));
		}

		return $result;
	}
	$finishedBridges = findBridges(0);

	function bridgeInfo($bridge) {
		global $components;

		$score = 0;
		$b = [];
		foreach ($bridge as $bits) {
			$score += $components[$bits]['score'];
			$b[] = implode('/', $components[$bits]['pins']);
		}
		return [implode('--', $b), $score];
	}

	$part1 = 0;
	$part2_len = 0;
	$part2 = 0;
	foreach ($finishedBridges as $bridge) {
		$bi = bridgeInfo($bridge);

		if (count($bridge) > $part2_len) {
			$part2 = $bi[1];
			$part2_len = count($bridge);
		} else if (count($bridge) == $part2_len) {
			$part2 = max($part2, $bi[1]);
			$part2_len = count($bridge);
		}

		$part1 = max($part1, $bi[1]);
		debugOut('Bridge: ', $bi[0], ' => ', $bi[1], "\n");
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
