<?php

class LinkedInUploader
{
    private $accessToken;
    private $personUrn;

    public function __construct($accessToken, $personUrn)
    {
        $this->accessToken = $accessToken;
        $this->personUrn = $personUrn;
    }

    public function registerUpload()
    {
        $url = 'https://api.linkedin.com/v2/assets?action=registerUpload';

        $postData = [
            "registerUploadRequest" => [
                "owner" => $this->personUrn,
                "recipes" => ["urn:li:digitalmediaRecipe:feedshare-image"],
                "serviceRelationships" => [[
                    "relationshipType" => "OWNER",
                    "identifier" => "urn:li:userGeneratedContent"
                ]]
            ]
        ];

        return $this->makeRequest($url, $postData);
    }

    public function uploadImage($uploadUrl, $imagePath)
    {
        $imageData = file_get_contents($imagePath);

        $ch = curl_init($uploadUrl);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $imageData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: image/jpeg',
            'Content-Length: ' . strlen($imageData),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function createPost($assetUrn, $message)
    {
        $url = 'https://api.linkedin.com/v2/ugcPosts';

        $postData = [
            "author" => $this->personUrn,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $message
                    ],
                    "shareMediaCategory" => "IMAGE",
                    "media" => [[
                        "status" => "READY",
                        "description" => ["text" => "Shared via API"],
                        "media" => $assetUrn,
                        "title" => ["text" => "LinkedIn Image Upload"]
                    ]]
                ]
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC"
            ]
        ];

        return $this->makeRequest($url, $postData);
    }

    private function makeRequest($url, $data)
    {
        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json",
            "X-Restli-Protocol-Version: 2.0.0"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
