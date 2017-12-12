#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$programs = array();
	// Get data for each program
	foreach ($input as $details) {
		preg_match('#(.*) <-> (.*)?#SADi', $details, $m);
		list($all, $name, $pipes) = $m;
		$pipes = explode(', ', $pipes);

		$programs[$name] = ['pipes' => $pipes];
	}

	function countPrograms($programs, $name, &$known) {
		if (!in_array($name, $known)) { $known[] = $name; }

		foreach ($programs[$name]['pipes'] as $pipe) {
			if (!in_array($pipe, $known)) {
				countPrograms($programs, $pipe, $known);
			}
		}
	}

	$part1 = [];
	countPrograms($programs, '0', $part1);
	echo 'Part 1: ', count($part1), "\n";

	$groups = [];
	foreach ($programs as $p => $pinfo) {
		foreach ($groups as $g) {
			if (in_array($p, $g)) {
				continue 2;
			}
		}

		$groups[$p] = [];
		countPrograms($programs, $p, $groups[$p]);
	}

	echo 'Part 2: ', count($groups), "\n";
