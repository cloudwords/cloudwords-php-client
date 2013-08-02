<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
$params = array('name' => 'Testing Project Task - REVISED',
                 'description' => 'This is just for test Project Task PHP Client - REVISED',
                 'type' => 'revise_language',
                 'assignee'  => array('customerUser' => array('id' => 853)),
                 'followers' => array(array('customerUser' => array('id' => 853)),
                                       array('vendor' => array('id' => 3037))
                                      ),
                 'targetLanguage' => array('code' => 'nl'),
                 'startDate' => '2013-08-18T22:15:59.000+0000',
                 'dueDate' => '2013-09-18T22:15:59.000+0000',
                 'emailReminderDay' => 10
                );
$projectId = 10173;
//$taskId = 29537;
//$taskId = 29097;
//$taskId = 29583;
//$taskId = 29585;
//$taskId = '29587';
$taskId = 29589;
$fileUpload = 'files/PHP.zip';

//print_r($client->updateProjectTask($projectId, $taskId, $params));

print_r($client->uploadTaskAttachment($projectId, $taskId, $fileUpload));