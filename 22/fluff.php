<?php
	$__CLI['long'] = ['draw', 'sleep:'];
	$__CLI['extrahelp'] = [];
	$__CLI['extrahelp'][] = '      --draw               Draw the entire path as we follow it.';
	$__CLI['extrahelp'][] = '      --sleep <#>          Sleep time between output when using drawSearch';

	function drawState($map, $carrier, $redraw=true, $windowSize = ['w' => 80, 'h' => 40]) {
		global $visited, $__CLIOPTS;

		if (!isset($__CLIOPTS['draw'])) { return; }

		$visited[$carrier['y']][$carrier['x']] = true;

		$symbols = ['up' => '^',
	                'down' => 'v',
	                'left' => '<',
	                'right' => '>',
	               ];

		if ($redraw) { echo "\033[" . ($windowSize['h'] + 6) . "A"; }

		$offset = $carrier;
		$offset['x'] -= floor($windowSize['w'] / 2);
		$offset['y'] -= floor($windowSize['h'] / 2);

		ob_start();
		echo '┍', str_repeat('━', $windowSize['w']), '┑', "\n";
		for ($y = $offset['y']; $y < $offset['y'] + $windowSize['h']; $y++) {
			echo '│';
			for ($x = $offset['x']; $x < $offset['x'] + $windowSize['w']; $x++) {
				if (isset($visited[$y][$x])) { echo "\033[0;32m"; }
				if ($carrier['y'] == $y && $carrier['x'] == $x) {
					echo $symbols[$carrier['d']];
				} else {
					echo isset($map[$y][$x]) ? $map[$y][$x] : '.';
				}
				if (isset($visited[$y][$x])) { echo "\033[0m"; }
			}
			echo '│', "\n";
		}
		echo '┕', str_repeat('━', $windowSize['w']), '┙', "\n";
		echo (isset($carrier['note']) && !empty($carrier['note']) ? 'Note: ' . $carrier['note'] : ''), "\033[K", "\n";
		echo 'Location: ', $carrier['x'], ', ', $carrier['y'], ' || Direction: ', $carrier['d'], "\033[K", "\n";
		echo 'Bursts: ', $carrier['bursts'], ' || Infections Caused: ', $carrier['infections'], "\033[K", "\n";
		echo "\n";
		ob_end_flush();

		usleep(isset($__CLIOPTS['sleep']) ? $__CLIOPTS['sleep'] : 25000);
	}
