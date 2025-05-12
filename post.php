<?php

require_once 'src/LinkedInUploader.php';

$config = require 'config/linkedin.php';

$uploader = new LinkedInUploader(
    $config['access_token'],
    $config['person_urn']
);

$imagePath = __DIR__ . '/images/example.jpg'; // Your image here
$message = "🚀 Sharing an image on LinkedIn using raw PHP!";

// Step 1: Register Upload
$register = $uploader->registerUpload();
$uploadUrl = $register['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
$assetUrn = $register['value']['asset'];

// Step 2: Upload Image
$uploadResponse = $uploader->uploadImage($uploadUrl, $imagePath);

// Step 3: Create Post
$postResponse = $uploader->createPost($assetUrn, $message);

echo "<pre>";
print_r($postResponse);
echo "</pre>";
