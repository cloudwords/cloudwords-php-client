<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
print_r($client->getCurrentUser());
