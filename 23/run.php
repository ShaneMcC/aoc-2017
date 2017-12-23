#!/usr/bin/php
<?php
	$__CLI['long'] = ['none', 'partial'];
	$__CLI['extrahelp'] = [];
	$__CLI['extrahelp'][] = '      --none               Do no optimisations.';
	$__CLI['extrahelp'][] = '      --partial            Only do partial optimisations.';

	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/../18/VM.php');
	require_once(dirname(__FILE__) . '/VMOptimisations.php');

	$data = VM::parseInstrLines(getInputLines());
	$vm = new VM($data);
	$part1 = 0;

	/**
	 * sub
	 *   - sub X Y
	 *
	 * decreases register X by the value of Y.
	 *
	 * @param $vm VM to execute on.
	 * @param $args Args for this instruction.
	 */
	$vm->setInstr('sub', function($vm, $args) {
		$x = $args[0];
		$y = $vm->getValue($args[1]);

		$vm->setReg($x, $vm->getReg($x) - $y);
	});

	/**
	 * mul
	 *   - mul X Y
	 *
	 * sets register X to the result of multiplying the value contained
	 * in register X by the value of Y.
	 *
	 * @param $vm VM to execute on.
	 * @param $args Args for this instruction.
	 */
	$vm->setInstr('mul', function($vm, $args) use (&$part1) {
		$x = $args[0];
		$y = $vm->getValue($args[1]);

		$vm->setReg($x, $vm->getReg($x) * $y);
		$part1++;
	});

	/**
	 * jnz
	 *   - jnz X Y
	 *
	 * jumps with an offset of the value of Y, but only if the value
	 * of X is not zero. (An offset of 2 skips the next instruction, an offset
	 * of -1 jumps to the previous instruction, and so on.)
	 *
	 * @param $vm VM to execute on.
	 * @param $args Args for this instruction.
	 */
	$vm->setInstr('jnz', function($vm, $args) {
		$x = $vm->getValue($args[0]);
		$y = $vm->getValue($args[1]);

		if ($x != 0) {
			$newloc = $vm->getLocation() + (int)$y;
			$vm->jump($newloc - 1); // (-1 because step() always does +1)
		}
	});

	$vm->run();

	echo 'Part 1: ', $part1, "\n";

	$vm->reset();
	$vm->setReg('a', 1);
	$vm->setDebug(isDebug(), 0);

	if (!isset($__CLIOPTS['none'])) {
		if (isset($__CLIOPTS['partial'])) {
			$vm->addReadAhead('partialOptimise');
		} else {
			$vm->addReadAhead('fullyOptimise');
		}
	}

	$vm->run();
	echo 'Part 2: ', $vm->getReg('h'), "\n";
