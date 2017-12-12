#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$programs = array();
	// Get data for each program
	foreach ($input as $details) {
		preg_match('#(.*) <-> (.*)?#SADi', $details, $m);
		list($all, $name, $pipes) = $m;
		$programs[$name] = ['pipes' => explode(', ', $pipes)];
	}

	function countPrograms($programs, $name, $known = []) {
		if (!in_array($name, $known)) { $known[] = $name; }
		foreach ($programs[$name]['pipes'] as $pipe) {
			if (!in_array($pipe, $known)) {
				$known = countPrograms($programs, $pipe, $known);
			}
		}
		return $known;
	}

	$groups = [];
	foreach ($programs as $p => $pinfo) {
		foreach ($groups as $g) {
			if (in_array($p, $g)) {
				continue 2;
			}
		}

		$groups[$p] = countPrograms($programs, $p);
	}

	echo 'Part 1: ', count($groups[0]), "\n";
	echo 'Part 2: ', count($groups), "\n";
