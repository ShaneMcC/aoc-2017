#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$lines = array();
	foreach ($input as $line) {
		$lines[] = sorted('sort', preg_split("#\s+#", $line));
	}

	function getChecksumAll($lines) {
		$checksum = 0;
		foreach ($lines as $l) {
			$checksum += $l[count($l) - 1] - $l[0];
		}
		return $checksum;
	}

	function getChecksumEven($lines) {
		$checksum = 0;
		foreach ($lines as $l) {
			foreach (getAllSets($l, 2, 2) as $s) {
				if ($s[1] % $s[0] == 0) {
					$checksum += $s[1] / $s[0];
				}
			}
		}
		return $checksum;
	}

	echo 'Part 1: ', getChecksumAll($lines), "\n";
	echo 'Part 2: ', getChecksumEven($lines), "\n";
