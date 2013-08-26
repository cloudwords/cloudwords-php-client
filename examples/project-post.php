<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
$params = array('id' => 3,
                        'name' => 'Test Project',
                        'description' => 'Test Description',
                        'notes' => 'Test Notes',
                    	'poNumber' => '123456',
                        'intendedUse' => 1153,
                        'sourceLanguage' => 'en',
                        'targetLanguages' => array('fr', 'de'),
                        'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                        'deliveryDueDate' => '2051-01-01T00:00:00.000+0000'
                       );

try {
    $project = $client->createProject($params);
    print_r($project);
} catch (\Cloudwords\Exception $e) {
    echo $e->getMessage();
} 
