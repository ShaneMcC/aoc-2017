<?php
	class FoldedList {
		private $list = [];
		private $skipSize = 0;
		private $position = 0;

		function __construct($size) {
			$this->list = array_keys(array_fill(0, $size, ''));
		}

		function getSize() {
			return count($this->list);
		}

		public function reset() {
			$this->list = array_keys(array_fill(0, $this->getSize(), ''));
			$this->position = 0;
			$this->skipSize = 0;
		}

		public function getNext($length, $start = -1) {
			$start = $start >= 0 ? $start : $this->position;
			$result = [];
			for ($i = $start; $i < $start + $length; $i++) {
				$result[] = $this->list[$i % count($this->list)];
			}

			return $result;
		}

		public function setNext($values) {
			$i = $this->position;
			foreach ($values as $v) {
				$this->list[$i++ % count($this->list)] = $v;
			}
			$this->position = ($i + $this->skipSize)  % count($this->list);
			$this->skipSize++;
		}

		public function getList() {
			return $this->list;
		}
	}

	class KnotHash {
		private $input = [];
		private $foldedlist = null;

		public function __construct() {
			$this->foldedlist = new FoldedList(256);
		}

		public function setInputString($str) {
			$this->input = [];
			if (!empty($str)) {
				foreach (str_split($str) as $s) {
					$this->input[] = ord($s);
				}
			}
			$this->input[] = 17;
			$this->input[] = 31;
			$this->input[] = 73;
			$this->input[] = 47;
			$this->input[] = 23;
			$this->foldedlist->reset();
			return $this;
		}

		public function setInputArray($arr) {
			$this->input = $arr;
			$this->foldedlist->reset();
			return $this;
		}

		public function runRound() {
			foreach ($this->input as $len) {
				$this->foldedlist->setNext(array_reverse($this->foldedlist->getNext($len)));
			}

			return $this->foldedlist->getList();
		}

		public function hash() {
			for ($i = 0; $i < 64; $i++) {
				$this->runRound();
			}

			$denseHash = [];
			for ($i = 0; $i < $this->foldedlist->getSize(); $i += 16) {
				$denseHash[] = sprintf("%02x", array_reduce($this->foldedlist->getNext(16, $i), function ($c, $i) { return ($c == NULL ? $i : $c ^ $i); }));
			}

			return implode($denseHash);
		}

		public static function getHash($input) {
			return (new KnotHash())->setInputString($input)->hash();
		}
	}
