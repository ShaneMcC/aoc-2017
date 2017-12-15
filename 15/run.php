#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/../10/KnotHash.php');
	$input = getInputLines();

	class AOCGenerator {
		private $start = 0;
		private $value = 0;
		private $factor = 0;
		private $multiple = 1;

		public function __construct($start) {
			$this->value = $this->start = $start;
		}
		public function reset() {
			$this->value = $this->start;
		}
		public function setFactor($factor) {
			$this->factor = $factor;
		}
		public function setMultiple($multiple) {
			$this->multiple = $multiple;
		}
		public function getNext() {
			do {
				$this->value = ($this->value * $this->factor) % 2147483647;
			} while ($this->value % $this->multiple != 0);
			return $this->value;
		}
	}

	$generator = array();
	// Get data for each program
	foreach ($input as $details) {
		preg_match('#Generator ([AB]) starts with ([0-9]+)#SADi', $details, $m);
		list($all, $name, $start) = $m;
		$generator[$name] = new AOCGenerator((int)$start);
	}

	$generator['A']->setFactor(16807);
	$generator['B']->setFactor(48271);

	function getMatches($generator, $part2 = false) {
		if ($part2) {
			$generator['A']->setMultiple(4);
			$generator['B']->setMultiple(8);
		}
		$generator['A']->reset();
		$generator['B']->reset();

		$matches = 0;
		for ($i = 0; $i < (isTest() ? 5 : ($part2 ? 5000000 : 40000000)); $i++) {
			$a = $generator['A']->getNext() & 65535;
			$b = $generator['B']->getNext() & 65535;
			if ($a == $b) { $matches++; }
			if (isDebug() && $i > 0 && $i % 1000000 == 0) { echo $i, "\n"; }
		}

		return $matches;
	}

	$part1 = getMatches($generator);
	echo 'Part 1: ', $part1, "\n";
	$part2 = getMatches($generator, true);
	echo 'Part 2: ', $part2, "\n";

