<?php
$config['base_url'] = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
$config['base_target_url'] = 'https://lpse.acehprov.go.id/';

$config['statuses'] = array(
    'selesai' => 'Lelang sudah selesai',
    'kontrak' => 'Penandatanganan Kontrak',
);

$config['use_curl'] = false;

session_start();