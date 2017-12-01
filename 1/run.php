#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();

	function getCaptcha($input, $part2 = false) {
		$total = 0;
		for ($i = 0; $i < strlen($input); $i++) {
			$n = (int)$input{$i};
			$next = (int)$input{($i + ($part2 ? strlen($input) / 2 : 1)) % strlen($input)};
			if ($n == $next) {
				$total += $n;
			}
		}
		return $total;
	}

	// Alternative way that does the same as above, but looks uglier...
	function getCaptcha2($input, $part2 = false) {
		return array_sum(array_filter(str_split($input), function ($n, $i) use ($input, $part2) {
			return $n == $input{($i + ($part2 ? strlen($input) / 2 : 1)) % strlen($input)};
		}, ARRAY_FILTER_USE_BOTH));
	}

	echo 'Part 1: ', getCaptcha($input), "\n";
	echo 'Part 2: ', getCaptcha($input, true), "\n";
