#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$banks = preg_split('#\s+#', getInputLine());

	function reallocateBanks($banks, $part2 = false) {
		$seen = [];
		$part2Check = NULL;
		$part2Count = 0;

		while (true) {
			$checksum = crc32(json_encode($banks));
			if (in_array($checksum, $seen)) {
				if (!$part2) { break; }

				if ($part2Check === NULL) {
					$part2Check = $checksum;
				} else {
					$part2Count++;
					if ($part2Check == $checksum) {
						break;
					}
				}
			} else {
				$seen[] = $checksum;
			}

			$max = max($banks);
			$location = array_search($max, $banks);
			$banks[$location] = 0;
			for ($i = 1; $i <= $max; $i++) {
				$banks[($location + $i) % count($banks)]++;
			}
		}

		return $part2 ? $part2Count : count($seen);
	}

	echo 'Part 1: ', reallocateBanks($banks), "\n";
	echo 'Part 2: ', reallocateBanks($banks, true), "\n";
