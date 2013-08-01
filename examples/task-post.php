<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
$params = array('name' => 'Testing Project Task',
                 'description' => 'This is just for test Project Task PHP Client',
                 'type' => 'custom',
                 'assignee'  => array('customerUser' => array('id' => 853)),
                 'followers' => array(array('customerUser' => array('id' => 853)),
                                       array('vendor' => array('id' => 3037))
                                      ),
                 'targetLanguage' => array('code' => 'de'),
                 'startDate' => '2013-07-18T22:15:59.000+0000',
                 'dueDate' => '2013-08-18T22:15:59.000+0000',
                 'emailReminderDay' => 5
                );
print_r($client->createProjectTask(10173, $params));