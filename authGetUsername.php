<?php

header('Content-Type: application/json');
if (!isset($_GET['token'])) exit('false');
$auth = json_decode(file_get_contents(__DIR__ . "/authusers.json"));
$token = $_GET['token'];
if (!isset($auth->$token)) exit('null');
echo json_encode($auth->$token);
unset($auth->$token);
file_put_contents('authusers.json', json_encode($auth));