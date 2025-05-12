<?php
$config = require 'config.php';

$accessToken = $config['access_token'];
$urn = 'urn:li:person:YOUR_LINKEDIN_ID'; // Replace this
$asset = 'urn:li:digitalmediaAsset:YOUR_ASSET_ID'; // Replace this after upload_image.php

$postData = [
    "owner" => $urn,
    "subject" => "PHP Test Post",
    "text" => [
        "text" => "Posting from raw PHP with an uploaded image! 🚀"
    ],
    "content" => [
        "contentEntities" => [
            [
                "entity" => $asset
            ]
        ],
        "shareMediaCategory" => "IMAGE"
    ],
    "distribution" => [
        "linkedInDistributionTarget" => []
    ]
];

$ch = curl_init('https://api.linkedin.com/v2/ugcPosts');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json",
    "X-Restli-Protocol-Version: 2.0.0"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 201) {
    echo "✅ Post created successfully!\n";
} else {
    echo "❌ Failed to post. Response:\n$response";
}
