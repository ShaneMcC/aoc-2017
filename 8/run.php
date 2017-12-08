#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$instructions = array();
	$registers = array();
	foreach ($input as $details) {
		preg_match('#(.*) (.*) (.*) if (.*) (.*) (.*)#SADi', $details, $m);
		list($all, $register, $action, $value, $ifregister, $ifaction, $ifvalue) = $m;

		$registers[$register] = 0;
		$instructions[] = ['register' => $register, 'action' => $action, 'value' => $value, 'if' => ['register' => $ifregister, 'action' => $ifaction, 'value' => $ifvalue], 'desc' => $all];
	}

	$ifActions = ['>' => function ($reg, $value) { return $reg > $value; },
	              '>=' => function ($reg, $value) { return $reg >= $value; },
	              '<' => function ($reg, $value) { return $reg < $value; },
	              '!=' => function ($reg, $value) { return $reg != $value; },
	              '==' => function ($reg, $value) { return $reg == $value; },
	              '<=' => function ($reg, $value) { return $reg <= $value; },
	             ];

	$doActions = ['inc' => function ($reg, $value) { return $reg += $value; },
	              'dec' => function ($reg, $value) { return $reg -= $value; },
	             ];

	$highestEver = 0;
	foreach ($instructions as $inst) {
		$if = $inst['if'];

		if (isset($ifActions[$if['action']])) {
			if ($ifActions[$if['action']]($registers[$if['register']], $if['value'])) {
				if (isset($doActions[$inst['action']])) {
					$registers[$inst['register']] = $doActions[$inst['action']]($registers[$inst['register']], $inst['value']);
				} else {
					die('Unknown action: ' . $inst['desc'] . "\n");
				}

				$highestEver = max($highestEver, $registers[$inst['register']]);
			}
		} else {
			die('Unknown if: ' . $inst['desc'] . "\n");
		}
	}

	$highestEnd = max($registers);
	echo 'Part 1: ', $highestEnd, "\n";
	echo 'Part 2: ', $highestEver, "\n";
