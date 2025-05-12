<?php
$config = require 'config.php';

$ch = curl_init('https://api.linkedin.com/v2/me');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$config['access_token']}"
]);

$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);

echo "Your LinkedIn URN: urn:li:person:{$data['id']}\n";
