#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$banks = preg_split('#\s+#', getInputLine());

	function reallocateBanks($banks) {
		$seen = [];

		while (true) {
			$checksum = json_encode($banks);
			if (in_array($checksum, $seen)) {
				return [count($seen), count($seen) - array_search($checksum, $seen)];
			}
			$seen[] = $checksum;

			$max = max($banks);
			$location = array_search($max, $banks);
			$banks[$location] = 0;

			// Distribute equal amounts
			for ($i = 0; $i < count($banks); $i++) {
				$banks[$i] += floor($max / count($banks));
			}
			// Distribute un-equal remainders.
			for ($i = 1; $i <= ($max % count($banks)); $i++) {
				$banks[($location + $i) % count($banks)]++;
			}
		}
	}

	list($part1, $part2) = reallocateBanks($banks);

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
