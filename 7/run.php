#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$programs = array();
	// Get data for each program
	foreach ($input as $details) {
		preg_match('#(.*) \(([0-9]+)\)(?: -> (.*))?#SADi', $details, $m);
		if (!isset($m[3])) { $m[3] = ''; }
		list($all, $name, $weight, $above) = $m;

		$programs[$name] = ['weight' => $weight, 'above' => empty($above) ? [] : explode(', ', $above), 'below' => NULL];
	}

	// Store links to below programs
	foreach ($programs as $name => $data) {
		foreach ($data['above'] as $above) {
			$programs[$above]['below'] = $name;
		}
	}

	function getWeight($programs, $prog) {
		$weight = $programs[$prog]['weight'];

		foreach ($programs[$prog]['above'] as $above) {
			$weight += getWeight($programs, $above);
		}

		return $weight;
	}

	function findOddWeight($programs, $start) {
		$weights = [];
		foreach ($programs[$start]['above'] as $above) {
			$weights[$above] = getWeight($programs, $above);
		}

		$normalWeight = array_keys(array_filter(array_count_values($weights), function ($a) { return $a != 1; }))[0];
		$oddWeight = array_filter(array_count_values($weights), function ($a) { return $a == 1; });

		if (empty($oddWeight)) { return FALSE; }

		$oddWeight = array_keys($oddWeight)[0];
		$oddElement = array_search($oddWeight, $weights);

		return [$oddElement, $oddWeight, $normalWeight];
	}

	function findWrongWeight($programs, $start) {
		$last = [$start, -1, -1];
		while (true) {
			$odd = findOddWeight($programs, $last[0]);
			if ($odd == FALSE) {
				return $last;
			} else {
				$last = $odd;
			}
		}
	}

	$bottom = array_keys(array_filter($programs, function ($p) { return $p['below'] == NULL; }))[0];

	echo 'Part 1: ', $bottom, "\n";

	$wrong = findWrongWeight($programs, $bottom);
	$difference = $wrong[2] - $wrong[1];
	$newWeight = $programs[$wrong[0]]['weight'] + $difference;

	echo 'Part 2: ', $wrong[0], ' is wrong and should be ', $newWeight, "\n";
