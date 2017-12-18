#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	require_once(dirname(__FILE__) . '/VM.php');

	$data = VM::parseInstrLines(getInputLines());
	$vm = new VM($data);

	$vm->setDebug(isDebug());
	$vm->run();

	echo 'Part 1: ', $vm->getOutput(), "\n";

	$vm1 = new VM($data);
	$vm2 = new VM($data);

	$vm1->setMiscData('pid', 0);
	$vm1->setMiscData('partner', 1);

	$vm2->setMiscData('pid', 1);
	$vm2->setMiscData('partner', 0);

	$vm1->setReg('p', $vm1->getMiscData('pid'));
	$vm2->setReg('p', $vm2->getMiscData('pid'));

	foreach ([$vm1, $vm2] as $vm) {
		$__SNDCOUNT[$vm->getMiscData('pid')] = 0;
		$__SND[$vm->getMiscData('pid')] = [];

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
		$vm->setInstr('snd', function($vm, $args) {
			global $__SND, $__SNDCOUNT;
			$x = $vm->isReg($args[0]) ? $vm->getReg($args[0]) : $args[0];

			$__SND[$vm->getMiscData('partner')][] = $x;
			$__SNDCOUNT[$vm->getMiscData('pid')]++;
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
		$vm->setInstr('rcv', function($vm, $args) {
			global $__SND;

			$vm->setMiscData('waiting', false);
			if (!empty($__SND[$vm->getMiscData('pid')])) {
				$val = array_shift($__SND[$vm->getMiscData('pid')]);

				$vm->setReg($args[0], $val);
			} else {
				// Jump back one so that we try this again.
				$vm->jump($vm->getLocation() - 1); // (-1 because step() always does +1)
				$vm->setMiscData('waiting', true);
			}
		});
	}


	while (true) {
		$vm1->step();
		$vm2->step();

		if ($vm1->getMiscData('waiting') && $vm2->getMiscData('waiting')) {
			echo 'Both VMs stalled, exiting.', "\n";
			break;
		}
	}

	echo 'Part 2: ', $__SNDCOUNT[1], "\n";
