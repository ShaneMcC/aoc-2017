#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$map = [];
	foreach (getInputLines() as $line) { $map[] = str_split($line); }

	$directions = ['up' => ['x' => 0, 'y' => -1, 'left' => 'left', 'right' => 'right', 'reverse' => 'down'],
	               'down' => ['x' => 0, 'y' => 1, 'left' => 'right', 'right' => 'left', 'reverse' => 'up'],
	               'left' => ['x' => -1, 'y' => 0, 'left' => 'down', 'right' => 'up', 'reverse' => 'right'],
	               'right' => ['x' => 1, 'y' => 0, 'left' => 'up', 'right' => 'down', 'reverse' => 'left'],
	               ];

	$simpleBehaviour = ['.' => ['direction' => 'left', 'state' => '#'],
	                    '#' => ['direction' => 'right', 'state' => '.']];

	$advancedBehaviour = ['.' => ['direction' => 'left', 'state' => 'W'],
	                      'W' => ['state' => '#'],
	                      '#' => ['direction' => 'right', 'state' => 'F'],
	                      'F' => ['direction' => 'reverse', 'state' => '.']];

	function doInfection($map, $behaviour, $count) {
		global $directions;

		$carrier = ['x' => floor(count($map[0]) / 2), 'y' => floor(count($map) / 2), 'd' => 'up', 'note' => 'Begin.', 'infections' => 0, 'bursts' => 0];

		drawState($map, $carrier, false);

		for ($i = 0; $i < $count; $i++) {
			$carrier['bursts']++;

			$action = $behaviour[$map[$carrier['y']][$carrier['x']]];
			if (isset($action['direction'])) {
				$carrier['d'] = $directions[$carrier['d']][$action['direction']];
			}
			$map[$carrier['y']][$carrier['x']] = $action['state'];

			if ($action['state'] == '#') { $carrier['infections']++; }

			// Move.
			$carrier['note'] = 'Moving from ' . $carrier['x'] . ', ' . $carrier['y'];
			$carrier['y'] += $directions[$carrier['d']]['y'];
			$carrier['x'] += $directions[$carrier['d']]['x'];
			$carrier['note'] .= ' to ' . $carrier['x'] . ', ' . $carrier['y'];

			if (!isset($map[$carrier['y']][$carrier['x']])) {
				$map[$carrier['y']][$carrier['x']] = '.';
			}

			drawState($map, $carrier, true);
		}

		return $carrier;
	}

	$part1 = doInfection($map, $simpleBehaviour, 10000);
	echo 'Part 1: ', $part1['infections'], "\n";

	$part2 = doInfection($map, $advancedBehaviour, 10000000);
	echo 'Part 2: ', $part2['infections'], "\n";
