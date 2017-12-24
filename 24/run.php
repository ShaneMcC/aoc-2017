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

	function findComponents($components, $pinCount) {
		$results = [];

		foreach ($components as $c => $info) {
			if (in_array($pinCount, $info['pins'])) {
				$results[] = $c;
			}
		}

		return $results;
	}

	function findBridges($connector, $components, $current = []) {
		$possible = findComponents($components, $connector);
		$result = empty($current) ? [] : [$current];

		foreach ($possible as $p) {
			$nextConnector = ($components[$p]['score'] - $connector);
			$nextComponents = $components;
			unset($nextComponents[$p]);

			$result = array_merge($result, findBridges($nextConnector, $nextComponents, array_merge($current, [$p])));
		}

		return $result;
	}

	$part1 = $longest = $part2 = 0;
	foreach (findBridges(0, $components) as $bridge) {
		$score = array_reduce($bridge, function($c, $i) use ($components) { return $c + $components[$i]['score']; }, 0);

		if (count($bridge) > $longest) {
			$part2 = $score;
			$longest = count($bridge);
		} else if (count($bridge) == $longest) {
			$part2 = max($part2, $score);
			$longest = count($bridge);
		}

		$part1 = max($part1, $score);

		if (isDebug()) {
			echo 'Bridge: ', bridgeInfo($bridge), ' => ', $score, "\n";
		}
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
