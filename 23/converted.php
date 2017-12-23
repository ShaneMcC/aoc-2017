#!/usr/bin/php
<?php
	$a = 1;
	$b = 0;
	$c = 0;
	$d = 0;
	$e = 0;
	$f = 0;
	$g = 0;
	$h = 0;


	while (true) {
		$b = 67;					/* 00: set b 67 */
		$c = $b;					/* 01: set c b */
		// jnz a 2;					/* 02: jnz a 2 */
		// jnz 1 5;					/* 03: jnz 1 5 */
		if ($a == 0) { break; }
		$b *= 100;					/* 04: mul b 100 */
		$b -= -100000;				/* 05: sub b -100000 */
		$c = $b;					/* 06: set c b */
		$c -= -17000;				/* 07: sub c -17000 */
		break;
	}

	// A = 1
	// B = 106700
	// C = 123700
	// D = 0
	// E = 0
	// F = 0
	// G = 0
	// H = 0
	$optimiseLoop = true;
	$optimisePrime = true;

	if ($optimiseLoop) {
		// Count primes every 17 from $b to $c, store in $h
		for ($b = $b; $b <= $c; $b += 17) {
			$f = 1;
			for ($d = 2; $d < $b; $d++) {
				if ($b % $d == 0) {
					$f = 0;
					break;
				}
			}
			if ($f == 0) { $h -= -1; }
		}
		// Set correct state.
		$g = 0;
		$d = $e = $b = $c;
	} else {
		do {
			if ($optimisePrime) {
				$f = 1;
				for ($d = 2; $d < $b; $d++) {
					if ($b % $d == 0) {
						$f = 0;
						break;
					}
				}
				$g = 0;
				$d = $e = $b;
			} else {
				$f = 1;						/* 08: set f 1 */
				$d = 2;						/* 09: set d 2 */
				do {
					$e = 2;					/* 10: set e 2 */
					do {
						// $g = $d;			/* 11: set g d */
						// $g *= $e;		/* 12: mul g e */
						// $g -= $b;		/* 13: sub g b */
						$g = ($d * $e) - $b;

						// jnz g 2;			/* 14: jnz g 2 */
						if ($g == 0) {
							$f = 0;			/* 15: set f 0 */
						}
						$e -= -1;			/* 16: sub e -1 */
						// $g = $e;			/* 17: set g e */
						// $g -= $b;		/* 18: sub g b */
						$g = $e - $b;

						// jnz g -8;		/* 19: jnz g -8 */
					} while ($g != 0); // for ($e = $e; $e < $b; $e++) { }

					$d -= -1;				/* 20: sub d -1 */
					// $g = $d;				/* 21: set g d */
					// $g -= $b;			/* 22: sub g b */
					$g = $d - $b;
					// jnz g -13;			/* 23: jnz g -13 */
				} while ($g != 0); // for ($d = $d; $d < $b; $d++) { }
			}

			// jnz f 2;					/* 24: jnz f 2 */
			if ($f == 0) {
				$h -= -1;				/* 25: sub h -1 */
			}
			// $g = $b;					/* 26: set g b */
			// $g -= $c;				/* 27: sub g c */
			$g = $b - $c;

			// jnz g 2;					/* 28: jnz g 2 */
			if ($g == 0) {
				// jnz 1 3;				/* 29: jnz 1 3 */
				break;
			}
			$b -= -17;					/* 30: sub b -17 */
			// jnz 1 -23;				/* 31: jnz 1 -23 */
		} while (1 != 0); // for ($b = $b; $b <= $c; $b += 17) { }
	}


	// A = 1
	// B = 123700
	// C = 123700
	// D = 123700
	// E = 123700
	// F = 0
	// G = 0
	// H = 905
	echo sprintf("A = %s\nB = %s\nC = %s\nD = %s\nE = %s\nF = %s\nG = %s\nH = %s\n\n", $a, $b, $c, $d, $e, $f, $g, $h);
