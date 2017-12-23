<?php

	/**
	 * Optimisation for entire prime check loop.
	 *
	 *    0: set f 1
	 *    1: set d 2
	 *    2: set e 2
	 *    3: set g d
	 *    4: mul g e
	 *    5: sub g b
	 *    6: jnz g 2
	 *    7: set f 0
	 *    8: sub e -1
	 *    9: set g e
	 *   10: sub g b
	 *   11: jnz g -8
	 *   12: sub d -1
	 *   13: set g d
	 *   14: sub g b
	 *   15: jnz g -13
	 *   16: jnz f 2
	 *   17: sub h -1
	 *   18: set g b
	 *   19: sub g c
	 *   20: jnz g 2
	 *   21: jnz 1 3
	 *   23: sub b -17
	 *   23: jnz 1 -23
	 */
	function fullyOptimise($vm) {
		$loc = $vm->getLocation();
		if (!$vm->hasData($loc + 23)) { return FALSE; }

		$data = [];
		for ($i = 0; $i <= 23; $i++) { $data[$i] = $vm->getData($loc + $i); }

		// Check for matching instructions.
		if ($data[0][0] == 'set' && $data[0][1][0] == 'f' && $data[0][1][1] == '1' &&
			$data[1][0] == 'set' && $data[1][1][0] == 'd' && $data[1][1][1] == '2' &&
			$data[2][0] == 'set' && $data[2][1][0] == 'e' && $data[2][1][1] == '2' &&
			$data[3][0] == 'set' && $data[3][1][0] == 'g' && $data[3][1][1] == 'd' &&
			$data[4][0] == 'mul' && $data[4][1][0] == 'g' && $data[4][1][1] == 'e' &&
			$data[5][0] == 'sub' && $data[5][1][0] == 'g' && $data[5][1][1] == 'b' &&
			$data[6][0] == 'jnz' && $data[6][1][0] == 'g' && $data[6][1][1] == '2' &&
			$data[7][0] == 'set' && $data[7][1][0] == 'f' && $data[7][1][1] == '0' &&
			$data[8][0] == 'sub' && $data[8][1][0] == 'e' && $data[8][1][1] == '-1' &&
			$data[9][0] == 'set' && $data[9][1][0] == 'g' && $data[9][1][1] == 'e' &&
			$data[10][0] == 'sub' && $data[10][1][0] == 'g' && $data[10][1][1] == 'b' &&
			$data[11][0] == 'jnz' && $data[11][1][0] == 'g' && $data[11][1][1] == '-8' &&
			$data[12][0] == 'sub' && $data[12][1][0] == 'd' && $data[12][1][1] == '-1' &&
			$data[13][0] == 'set' && $data[13][1][0] == 'g' && $data[13][1][1] == 'd' &&
			$data[14][0] == 'sub' && $data[14][1][0] == 'g' && $data[14][1][1] == 'b' &&
			$data[15][0] == 'jnz' && $data[15][1][0] == 'g' && $data[15][1][1] == '-13' &&
			$data[16][0] == 'jnz' && $data[16][1][0] == 'f' && $data[16][1][1] == '2' &&
			$data[17][0] == 'sub' && $data[17][1][0] == 'h' && $data[17][1][1] == '-1' &&
			$data[18][0] == 'set' && $data[18][1][0] == 'g' && $data[18][1][1] == 'b' &&
			$data[19][0] == 'sub' && $data[19][1][0] == 'g' && $data[19][1][1] == 'c' &&
			$data[20][0] == 'jnz' && $data[20][1][0] == 'g' && $data[20][1][1] == '2' &&
			$data[21][0] == 'jnz' && $data[21][1][0] == '1' && $data[21][1][1] == '3' &&
			$data[22][0] == 'sub' && $data[22][1][0] == 'b' && $data[22][1][1] == '-17' &&
			$data[23][0] == 'jnz' && $data[23][1][0] == '1' && $data[23][1][1] == '-23'
		) {
			debugOut('Optimised prime check loop: ');
			for ($i = 0; $i < 23; $i++) { debugOut(VM::instrToString($data[$i]), ' -> '); }
			debugOut(VM::instrToString($data[23]), "\n");

			// for ($b = $b; $b <= $c; $b += 17) {
			for ($b = $vm->getReg('b'); $b <= $vm->getReg('c'); $b -= $data[22][1][1]) {
				// f = 1
				$vm->setReg($data[0][1][0], $data[0][1][1]);
				for ($d = 2; $d < $b; $d++) {
					if ($b % $d == 0) {
						// f = 2
						$vm->setReg($data[7][1][0], $data[7][1][1]);
						break;
					}
				}
				// if ($f == 0) { $h -= -1; }
				if ($vm->getReg($data[16][1][0]) == 0) { $vm->setReg($data[17][1][0], $vm->getReg($data[17][1][0]) - $data[17][1][1]); }
			}
			// Set correct state.
			$vm->setReg('g', 0);
			$vm->setReg('d', $vm->getReg('c'));
			$vm->setReg('e', $vm->getReg('c'));
			$vm->setReg('b', $vm->getReg('c'));

			// Jump to after the thing.
			return $loc + 24;
		}

		return FALSE;
	}

	/**
	 * Optimisation for prime check.
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
	function partialOptimise($vm) {
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
	}
