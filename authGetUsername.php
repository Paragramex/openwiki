<?php
/*
This file is part of paragrams OpenWiki system, find him here: github.com/Paragramex or on replit: replit.com/@paragram.
*/

header('Content-Type: application/json');
if (!isset($_GET['token'])) exit('false');
$auth = json_decode(file_get_contents(__DIR__ . "/authusers.json"));
$token = $_GET['token'];
if (!isset($auth->$token)) exit('null');
echo json_encode($auth->$token);
unset($auth->$token);
file_put_contents('authusers.json', json_encode($auth));