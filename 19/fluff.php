<?php
	$__CLI['long'] = ['draw', 'sleep:'];
	$__CLI['extrahelp'] = [];
	$__CLI['extrahelp'][] = '      --draw               Draw the entire path as we follow it.';
	$__CLI['extrahelp'][] = '      --sleep <#>          Sleep time between output when using drawSearch';

	function drawState($map, $pos, $max, $collected, $steps, $redraw = true) {
		global $visited, $__CLIOPTS;

		if (!isset($__CLIOPTS['draw'])) { return; }

		$visited[$pos['y']][$pos['x']] = true;

		if ($redraw) { echo "\033[" . ($max['y'] + 5) . "A"; }

		echo '┍', str_repeat('━', $max['x']), '┑', "\n";
		for ($y = 0; $y < $max['y']; $y++) {
			echo '│';
			for ($x = 0; $x < $max['x']; $x++) {
				if (isset($visited[$y][$x])) { echo "\033[0;32m"; }
				echo isset($map[$y][$x]) ? $map[$y][$x] : ' ';
				if (isset($visited[$y][$x])) { echo "\033[0m"; }
			}
			echo '│', "\n";
		}
		echo '┕', str_repeat('━', $max['x']), '┙', "\n";
		echo 'Collected: ', implode('', $collected), "\n";
		echo 'Steps: ', $steps, "\n";
		echo "\n";

		usleep(isset($__CLIOPTS['sleep']) ? $__CLIOPTS['sleep'] : 25000);
	}
