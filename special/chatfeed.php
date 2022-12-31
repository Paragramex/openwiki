<?php
 

__halt_compiler();
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
	$since = time();
	while (true) {
		$since++;
		sleep(1);
	}