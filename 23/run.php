#!/usr/bin/php
<?php
	$__CLI['long'] = ['none', 'partial', 'debug1', 'debug2'];
	$__CLI['extrahelp'] = [];
	$__CLI['extrahelp'][] = '      --none               Do no optimisations.';
	$__CLI['extrahelp'][] = '      --partial            Only do partial optimisations.';
	$__CLI['extrahelp'][] = '      --debug1             Debug part 1.';
	$__CLI['extrahelp'][] = '      --debug2             Debug part 2.';

	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/../common/VM.php');
	require_once(dirname(__FILE__) . '/VMOptimisations.php');

	$data = VM::parseInstrLines(getInputLines());
	$vm = new VM($data);
	$part1 = 0;

	// Count how many times `mul` is called.
	// Move the existing mul to a new name
	$mulname = 'mul_' . time();
	$vm->setInstr($mulname, $vm->getInstr('mul'));
	// Chain mul calls to the renamed method and increment our counter.
	$vm->setInstr('mul', function($vm, $args) use (&$part1, $mulname) {
		$vm->getInstr($mulname)($vm, $args);
		$part1++;
	});

	$vm->setDebug(isDebug() || isset($__CLIOPTS['debug1']), 0);
	$vm->run();
	echo 'Part 1: ', $part1, "\n";

	$vm->reset();
	$vm->setReg('a', 1);
	$vm->setDebug(isDebug() || isset($__CLIOPTS['debug2']), 0);

	if (!isset($__CLIOPTS['none'])) {
		if (isset($__CLIOPTS['partial'])) {
			$vm->addReadAhead('partialOptimise');
		} else {
			$vm->addReadAhead('fullyOptimise');
		}
	}

	$vm->run();
	echo 'Part 2: ', $vm->getReg('h'), "\n";
