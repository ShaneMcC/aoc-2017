#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$lines = array();
	foreach ($input as $line) {
		preg_match_all('#([0-9]+)#S', $line, $m);
		$lines[] = $m[1];
	}

	function getChecksumAll($lines) {
		$checksum = 0;
		foreach ($lines as $l) {
			$checksum += max($l) - min($l);
		}
		return $checksum;
	}

	function getChecksumEven($lines) {
		$checksum = 0;
		foreach ($lines as $l) {
			foreach (getAllSets(sorted('sort', $l), 2, 2) as $s) {
				if ($s[0] % $s[1] == 0) {
					$checksum += $s[0] / $s[1];
				}
			}
		}
		return $checksum;
	}

	echo 'Part 1: ', getChecksumAll($lines), "\n";
	echo 'Part 2: ', getChecksumEven($lines), "\n";
