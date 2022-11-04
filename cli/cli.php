<?php

 require_once 'tool.php';
function cleanFilename($stuff) {
	$illegal = array(" ","?","/","\\","*","|","<",">",'"');
	$legal = array("-","_","_","_","_","_","_","_","_");
	$cleaned = str_replace($illegal,$legal,$stuff);
	return $cleaned;
}
if (!isset($argv[1])) exit("There is no module provided to run.\n");
$module = cleanFilename($argv[1]);
echo "wiki CLI (c) 2022 paragram. May be distributed under the terms of the GNU GPL.\n";
echo "Module to run: $module\n";
if (!file_exists(__DIR__ . "/$module.php")) die("Fatal: Module not found. Stopping.\n");
echo "Running module $module...\n";
require_once "$module.php";