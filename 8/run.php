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

	$highestEver = 0;
	foreach ($instructions as $inst) {
		$do = false;
		$if = $inst['if'];

		if ($if['action'] == '>') { $do = $registers[$if['register']] > $if['value']; }
		else if ($if['action'] == '>=') { $do = $registers[$if['register']] >= $if['value']; }
		else if ($if['action'] == '<') { $do = $registers[$if['register']] < $if['value']; }
		else if ($if['action'] == '!=') { $do = $registers[$if['register']] != $if['value']; }
		else if ($if['action'] == '==') { $do = $registers[$if['register']] == $if['value']; }
		else if ($if['action'] == '<=') { $do = $registers[$if['register']] <= $if['value']; }
		else { die('Unknown if: ' . $inst['desc'] . "\n"); }

		if ($do) {
			if ($inst['action'] == 'inc') { $registers[$inst['register']] += $inst['value']; }
			else if ($inst['action'] == 'dec') { $registers[$inst['register']] -= $inst['value']; }
			else { die('Unknown action: ' . $inst['desc'] . "\n"); }

			$highestEver = max($highestEver, $registers[$inst['register']]);
		}
	}

	$highestEnd = 0;
	foreach ($registers as $reg => $value) {
		$highestEnd = max($highestEnd, $value);
	}


	echo 'Part 1: ', $highestEnd, "\n";
	echo 'Part 2: ', $highestEver, "\n";
