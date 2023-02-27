<?php

    // download the clients secrets json file from Google Developer Console under service accounts and save in project directory
    // example of client_secrets.json structure
    /*
        {
            "type": "service_account",
            "project_id": "***********************",
            "private_key_id": "***********************",
            "private_key": "-----BEGIN PRIVATE KEY-----\n***********************************\n-----END PRIVATE KEY-----\n",
            "client_email": "****************************.iam.gserviceaccount.com",
            "client_id": "***********************",
            "auth_uri": "https://accounts.google.com/o/oauth2/auth",
            "token_uri": "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509****************************.iam.gserviceaccount.com"
        }

    */

    // load the file
    $application_creds = json_decode (file_get_contents ('client_secrets.json'));

    // specify the headers and base64url encode
    // Google JWT uses RS256 encryption
    $headers = ['alg'=>'RS256','typ'=>'JWT'];
    $headers_encoded = base64url_encode (json_encode ($headers));

    // build the json payload with the required claim set
    $payload = json_encode ([
            'iss' => $application_creds->client_email,    
            'scope' => 'https://www.googleapis.com/auth/calendar',      
            'aud' => $application_creds->token_uri,             
            'exp' => time() + 3600,
            'iat' => time()
        ]);


    /* The claim set fields explained */
    /*
        iss	- The email address of the service account.
        scope - A space-delimited list of the permissions that the application requests.
        aud	- A descriptor of the intended target of the assertion. When making an access token request this value is always https://oauth2.googleapis.com/token.
        exp - The expiration time of the assertion, specified as seconds since 00:00:00 UTC, January 1, 1970. This value has a maximum of 1 hour after the issued time.
        iat	- The time the assertion was issued, specified as seconds since 00:00:00 UTC, January 1, 1970.
    */

    // base64 url encode the payload
    $payload_encoded = base64url_encode ($payload);

    // build the signature using openssl_sign with sha256WithRSAEncryption as required by Google 
    $key = $application_creds->private_key;
    openssl_sign ("$headers_encoded.$payload_encoded", $signature, $key, 'sha256WithRSAEncryption'); 
    $signature_encoded = base64url_encode ($signature);

    // build and return the token
    $token = "$headers_encoded.$payload_encoded.$signature_encoded";
    echo $token; // this will output a signed JWT that is ready for submission, e.g. eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.tcjVtbGpsbG4xcmQ0bHJiaGc3.....

    /*
        You can now pass the JWT ($token) to request an access token from Google OAuth 2.0 Authorization Server,

        A simple curl request for an access token
        curl -d 'grant_type=urn%3Aietf%3Aparams%3Aoauth%3Agrant-type%3Ajwt-bearer&assertion=$token 'https://oauth2.googleapis.com/token

        If the JWT and access token request are properly formed and the service account has permission to perform the operation, 
        then the JSON response from the Authorization Server includes an access token. 
        The following is an example response:

        {
            "access_token": "1/8xbJqaOZXSUZbHLl5EOtu1pxz3fmmetKx9W8CV4t79M",
            "scope": "https://www.googleapis.com/auth/prediction"
            "token_type": "Bearer",
            "expires_in": 3600
        }

        You can then use this access token from the response when making Google Calendar API requests. Below is an example of a curl request for retrieving a calendar's events
        curl \
        'https://www.googleapis.com/calendar/v3/calendars/[CALENDARID]/events?key=[YOUR_API_KEY]' \
        --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
        --header 'Accept: application/json' \
        --compressed 
    */


    function base64url_encode ($data)
    {
        // First of all you should encode $data to Base64 string
        $b64 = base64_encode($data);

        // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
        if ($b64 === false) {
            return false;
        }

        // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
        $url = strtr ($b64, '+/', '-_');

        // Remove padding character from the end of line and return the Base64URL result
        return rtrim ($url, '=');
    }

    
    function base64url_decode ($data, $strict = false)
    {
        // Convert Base64URL to Base64 by replacing “-” with “+” and “_” with “/”
        $b64 = strtr ($data, '-_', '+/');

        // Decode Base64 string and return the original data
        return base64_decode ($b64, $strict);
    }

?>