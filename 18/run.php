#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/VM.php');

	$data = VM::parseInstrLines(getInputLines());
	$vm = new VM($data);
	$part1 = 0;

	/**
	 * snd
	 *   - snd X
	 *
	 * plays a sound with a frequency equal to the value of X.
	 *
	 * @param $vm VM to execute on.
	 * @param $args Args for this instruction.
	 */
	$vm->setInstr('snd', function($vm, $args) use (&$part1) {
		$x = $vm->getValue($args[0]);
		$part1 = $x;
	});

	/**
	 * rcv
	 *   - rcv X
	 *
	 * recovers the frequency of the last sound played, but only when
	 * the value of X is not zero. (If it is zero, the command does
	 * nothing.)
	 *
	 * @param $vm VM to execute on.
	 * @param $args Args for this instruction.
	 */
	$vm->setInstr('rcv', function($vm, $args) {
		$x = $vm->getValue($args[0]);

		if ($x > 0) { $vm->end(0); }
	});

	$vm->setDebug(isDebug());
	$vm->run();

	echo 'Part 1: ', $part1, "\n";

	$vms = [['vm' => new VM($data), 'queue' => [], 'sendcount' => 0], ['vm' => new VM($data), 'queue' => [], 'sendcount' => 0]];
	$vms[0]['vm']->setMiscData('pid', 0)->setMiscData('partner', 1);
	$vms[1]['vm']->setMiscData('pid', 1)->setMiscData('partner', 0);

	foreach ($vms as $vminfo) {
		$vm = $vminfo['vm'];
		$vm->setDebug(isDebug());
		$vm->setReg('p', $vm->getMiscData('pid'));
		$vm->setMiscData('waiting', false);

		/**
		 * snd
		 *   - snd X
		 *
		 * sends the value of X to the other program. These values wait in a queue
		 * until that program is ready to receive them. Each program has its own
		 * message queue, so a program can never receive a message it sent.
		 *
		 * @param $vm VM to execute on.
		 * @param $args Args for this instruction.
		 */
		$vm->setInstr('snd', function($vm, $args) use (&$vms) {
			$x = $vm->getValue($args[0]);

			$vms[$vm->getMiscData('partner')]['queue'][] = $x;
			$vms[$vm->getMiscData('pid')]['sendcount']++;
		});

		/**
		 * rcv
		 *   - rcv X
		 *
		 * receives the next value and stores it in register X. If no values
		 * are in the queue, the program waits for a value to be sent to it.
		 * Programs do not continue to the next instruction until they have
		 * received a value. Values are received in the order they are sent.
		 *
		 * @param $vm VM to execute on.
		 * @param $args Args for this instruction.
		 */
		$vm->setInstr('rcv', function($vm, $args) use (&$vms) {
			$x = $args[0];

			$vm->setMiscData('waiting', false);
			if (!empty($vms[$vm->getMiscData('pid')]['queue'])) {
				$val = array_shift($vms[$vm->getMiscData('pid')]['queue']);

				$vm->setReg($x, $val);
			} else {
				// Jump back one so that we try this again.
				$vm->jump($vm->getLocation() - 1); // (-1 because step() always does +1)
				$vm->setMiscData('waiting', true);
			}
		});
	}


	while (true) {
		$waiting = true;
		foreach ($vms as $vminfo) {
			$vminfo['vm']->step();
			if (!$vminfo['vm']->getMiscData('waiting')) { $waiting = false; }
		}

		if ($waiting) {
			debugOut('All VMs stalled, exiting.', "\n");
			break;
		}
	}

	echo 'Part 2: ', $vms[1]['sendcount'], "\n";
