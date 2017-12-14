#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/KnotHash.php');
	$input = getInputLine();

	$kh = new KnotHash();
	$kh ->setInputArray(explode(',', $input));
	$list = $kh->runRound();
	echo 'Part 1: ', ($list[0] * $list[1]), "\n";

	echo 'Part 2: ', $kh->setInputString($input)->hash(), "\n";
