<?php
$config = require 'config.php';

$url = 'https://www.linkedin.com/oauth/v2/accessToken';

$data = [
    'grant_type' => 'authorization_code',
    'code' => $config['auth_code'],
    'redirect_uri' => $config['redirect_uri'],
    'client_id' => $config['client_id'],
    'client_secret' => $config['client_secret'],
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_POST, true);

$response = curl_exec($ch);
curl_close($ch);

echo "Access Token Response:\n";
echo $response;
