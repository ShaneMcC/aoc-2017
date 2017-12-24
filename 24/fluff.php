<?php

	function bridgeInfo($bridge) {
		global $components;

		$b = [];
		foreach ($bridge as $bits) {
			$b[] = implode('/', $components[$bits]['pins']);
		}
		return implode('--', $b);
	}
