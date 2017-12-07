#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$programs = array();
	// Get data for each program
	foreach ($input as $details) {
		preg_match('#(.*) \(([0-9]+)\)(?: -> (.*))?#SADi', $details, $m);
		if (!isset($m[3])) { $m[3] = ''; }
		echo $m[0], "\n";
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

	$bottom = array_keys(array_filter($programs, function ($p) { return $p['below'] == NULL; }))[0];

	echo 'Part 1: ', $bottom, "\n";
