<?php
$config = require 'config.php';

$accessToken = $config['access_token'];
$urn = 'urn:li:person:YOUR_LINKEDIN_ID'; // Replace after running get_urn.php

// Step 1: Register image upload
$registerUrl = 'https://api.linkedin.com/v2/assets?action=registerUpload';

$uploadRequest = [
    "registerUploadRequest" => [
        "recipes" => ["urn:li:digitalmediaRecipe:feedshare-image"],
        "owner" => $urn,
        "serviceRelationships" => [
            [
                "relationshipType" => "OWNER",
                "identifier" => "urn:li:userGeneratedContent"
            ]
        ]
    ]
];

$ch = curl_init($registerUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($uploadRequest));

$uploadResponse = json_decode(curl_exec($ch), true);
curl_close($ch);

$uploadUrl = $uploadResponse['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
$asset = $uploadResponse['value']['asset'];

// Step 2: Upload the image binary
$imagePath = $config['test_image'];
$imageData = file_get_contents($imagePath);

$ch = curl_init($uploadUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $imageData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: image/jpeg",
    "Content-Length: " . strlen($imageData)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 201 || $httpCode == 200) {
    echo "Image uploaded successfully!\n";
    echo "ASSET URN: $asset\n";
} else {
    echo "Upload failed. Response:\n$response";
}
