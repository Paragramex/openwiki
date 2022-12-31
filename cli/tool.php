<?php

if (php_sapi_name() !== 'cli') exit('Please run this from the command line.');
if (basename(realpath($_SERVER['SCRIPT_NAME'])) !== 'cli.php') exit("You are not using the CLI properly. Please run php cli.php.");