#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/../18/VM.php');

	$data = VM::parseInstrLines(getInputLines());
	$vm = new VM($data);
	$part1 = 0;

	$vm->setInstr('set', function($vm, $args) {
		$x = $args[0];
		$y = $vm->getValue($args[1]);

		$vm->setReg($x, $y);
	});

	$vm->setInstr('sub', function($vm, $args) {
		$x = $args[0];
		$y = $vm->getValue($args[1]);

		$vm->setReg($x, $vm->getReg($x) - $y);
	});

	$vm->setInstr('mul', function($vm, $args) use (&$part1) {
		$x = $args[0];
		$y = $vm->getValue($args[1]);

		$vm->setReg($x, $vm->getReg($x) * $y);
		$part1++;
	});

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

	/**
	 * Add optimisation for prime check.
	 *
	 *    0: set e 2
	 *    1: set g d
	 *    2: mul g e
	 *    3: sub g b
	 *    4: jnz g 2
	 *    5: set f 0
	 *    6: sub e -1
	 *    7: set g e
	 *    8: sub g b
	 *    9: jnz g -8
	 *   10: sub d -1
	 *   11: set g d
	 *   12: sub g b
	 *   13: jnz g -13
	 *
	 *	D = E = B
	 *	G = 0
	 *	F = isPrime(B) ? 1 : 0;
	 */
	$vm->addReadAhead(function ($vm) {
		$loc = $vm->getLocation();
		if (!$vm->hasData($loc + 13)) { return FALSE; }

		$data = [];
		for ($i = 0; $i <= 13; $i++) { $data[$i] = $vm->getData($loc + $i); }

		// Check for matching instructions.
		if ($data[0][0] == 'set' && $data[0][1][0] == 'e' && $data[0][1][1] == '2' &&
			$data[1][0] == 'set' && $data[1][1][0] == 'g' && $data[1][1][1] == 'd' &&
			$data[2][0] == 'mul' && $data[2][1][0] == 'g' && $data[2][1][1] == 'e' &&
			$data[3][0] == 'sub' && $data[3][1][0] == 'g' && $data[3][1][1] == 'b' &&
			$data[4][0] == 'jnz' && $data[4][1][0] == 'g' && $data[4][1][1] == '2' &&
			$data[5][0] == 'set' && $data[5][1][0] == 'f' && $data[5][1][1] == '0' &&
			$data[6][0] == 'sub' && $data[6][1][0] == 'e' && $data[6][1][1] == '-1' &&
			$data[7][0] == 'set' && $data[7][1][0] == 'g' && $data[7][1][1] == 'e' &&
			$data[8][0] == 'sub' && $data[8][1][0] == 'g' && $data[8][1][1] == 'b' &&
			$data[9][0] == 'jnz' && $data[9][1][0] == 'g' && $data[9][1][1] == '-8' &&
			$data[10][0] == 'sub' && $data[10][1][0] == 'd' && $data[10][1][1] == '-1' &&
			$data[11][0] == 'set' && $data[11][1][0] == 'g' && $data[11][1][1] == 'd' &&
			$data[12][0] == 'sub' && $data[12][1][0] == 'g' && $data[12][1][1] == 'b' &&
			$data[13][0] == 'jnz' && $data[13][1][0] == 'g' && $data[13][1][1] == '-13'
		) {
			debugOut('Optimised prime check: ');
			debugOut(VM::instrToString($data[0]), ' -> ');
			debugOut(VM::instrToString($data[1]), ' -> ');
			debugOut(VM::instrToString($data[2]), ' -> ');
			debugOut(VM::instrToString($data[3]), ' -> ');
			debugOut(VM::instrToString($data[4]), ' -> ');
			debugOut(VM::instrToString($data[5]), ' -> ');
			debugOut(VM::instrToString($data[6]), ' -> ');
			debugOut(VM::instrToString($data[7]), ' -> ');
			debugOut(VM::instrToString($data[8]), ' -> ');
			debugOut(VM::instrToString($data[9]), ' -> ');
			debugOut(VM::instrToString($data[10]), ' -> ');
			debugOut(VM::instrToString($data[11]), ' -> ');
			debugOut(VM::instrToString($data[12]), ' -> ');
			debugOut(VM::instrToString($data[13]), "\n");

			for ($d = $data[0][1][1]; $d < $vm->getReg('b'); $d++) {
				if ($vm->getReg('b') % $d == 0) {
					$vm->setReg($data[5][1][0], $data[5][1][1]);
					break;
				}
			}

			$vm->setReg('d', $vm->getReg('b'));
			$vm->setReg('e', $vm->getReg('b'));
			$vm->setReg('g', 0);

			// Jump to after the thing.
			return $loc + 14;
		}

		return FALSE;
	});

	$vm->run();
	echo 'Part 2: ', $vm->getReg('h'), "\n";
