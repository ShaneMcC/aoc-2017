#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$banks = preg_split('#\s+#', getInputLine());

	function reallocateBanks($banks, $part2 = false) {
		$seen = [];

		while (true) {
			$checksum = crc32(json_encode($banks));
			if (in_array($checksum, $seen)) {
				return $part2 ? count($seen) - array_search($checksum, $seen) : count($seen);
			}
			$seen[] = $checksum;

			$max = max($banks);
			$location = array_search($max, $banks);
			$banks[$location] = 0;
			for ($i = 1; $i <= $max; $i++) {
				$banks[($location + $i) % count($banks)]++;
			}
		}
	}

	echo 'Part 1: ', reallocateBanks($banks), "\n";
	echo 'Part 2: ', reallocateBanks($banks, true), "\n";
