#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$particles = array();
	foreach ($input as $details) {
		preg_match('#p=<([-0-9]+),([-0-9]+),([-0-9]+)>, v=<([-0-9]+),([-0-9]+),([-0-9]+)>, a=<([-0-9]+),([-0-9]+),([-0-9]+)>#SADi', $details, $m);
		list($all, $px, $py, $pz, $vx, $vy, $vz, $ax, $ay, $az) = $m;
		$p = ['p' => ['x' => $px, 'y' => $py, 'z' => $pz], 'v' => ['x' => $vx, 'y' => $vy, 'z' => $vz], 'a' => ['x' => $ax, 'y' => $ay, 'z' => $az]];
		$p['md'] = abs($p['p']['x']) + abs($p['p']['y']) + abs($p['p']['z']);
		$particles[] = $p;
	}

	function step($p) {
		foreach (['x', 'y', 'z'] as $c) {
			$p['v'][$c] += $p['a'][$c];
			$p['p'][$c] += $p['v'][$c];
		}

		$p['md'] = abs($p['p']['x']) + abs($p['p']['y']) + abs($p['p']['z']);

		return $p;
	}

	$part2Valid = array_keys($particles);
	for ($i = 0; $i < 1000; $i++) {
		$locations = [];
		foreach ($particles as $num => $p) {
			$particles[$num] = step($p);

			if (in_array($num, $part2Valid)) {
				$locations[sprintf('%s,%s,%s', $p['p']['x'], $p['p']['y'], $p['p']['z'])][] = $num;
			}
		}
		$part2Valid = [];
		foreach ($locations as $loc => $nums) {
			if (count($nums) == 1) {
				$part2Valid[] = $nums[0];
			}
		}
	}

	uasort($particles, function ($a, $b) { return $a['md'] - $b['md']; });

	echo 'Part 1: ', array_keys($particles)[0], "\n";
	echo 'Part 2: ', count($part2Valid), "\n";
