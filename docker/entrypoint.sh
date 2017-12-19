#!/bin/bash

DAY="${1}"
shift;

if ! [[ "${DAY}" =~ ^[0-9]+$ ]]; then
	echo 'Invalid Day: '${DAY};
	exit 1;
fi;

if [ ! -e "/code/${DAY}/run.php" ]; then
	echo 'Unknown Day: '${DAY};
	exit 1;
fi;

php /code/${DAY}/run.php ${@}
