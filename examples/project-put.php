<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
$params = array('id' => 10173,
                'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                'deliveryDueDate' => '2051-01-01T00:00:00.000+0000',
                'bidSelectDeadlineDate' => '2051-01-01T00:00:00.000+0000'
               );

/*
try {
    $project = $client->updateProject($params);
    print_r($project);
} catch (\Cloudwords\Exception $e) {
    echo $e->getErrorMessage();
} 
*/

try {
    $fileUpload = 'files/attachment.zip';
    $projectSource = $client->uploadProjectSource(10173, $fileUpload);
} catch (\Cloudwords\Exception $e) {
    echo $e->getErrorMessage();
} 
