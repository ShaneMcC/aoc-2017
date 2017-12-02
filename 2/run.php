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
			for ($i = 0; $i < count($l); $i++) {
				for ($j = $i+1; $j < count($l); $j++) {
					$min = min($l[$i], $l[$j]);
					$max = max($l[$i], $l[$j]);
					if ($max % $min == 0) {
						$checksum += $max / $min;
					}
				}
			}
		}
		return $checksum;
	}

	echo 'Part 1: ', getChecksumAll($lines), "\n";
	echo 'Part 2: ', getChecksumEven($lines), "\n";
