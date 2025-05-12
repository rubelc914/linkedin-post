# LinkedIn Poster (Raw PHP)

## 1. Setup
- Replace YOUR_CLIENT_ID, YOUR_CLIENT_SECRET, YOUR_REDIRECT_URI in config.php
- Upload a test image named `test.jpg` in the root folder

## 2. Steps to Run

1. Get authorization code manually:
   Open in browser:
   https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=YOUR_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=w_member_social

2. Paste the code in config.php → `auth_code`

3. Run:
   php get_access_token.php → copy access_token to config.php

4. Run:
   php get_urn.php → copy LinkedIn ID to config (`urn:li:person:XXXX`)

5. Run:
   php upload_image.php → copy asset URN

6. Run:
   php create_post.php → success!

## Done ✅
