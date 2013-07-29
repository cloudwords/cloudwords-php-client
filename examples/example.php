<?php

/*
 * Copyright 2011, Cloudwords, Inc.
 *
 * Licensed under the API LICENSE AGREEMENT, Version 1.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.cloudwords.com/developers/license-1.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
//$authToken  = 'UserToken ada9c21adf246e616a1fd52c81bf830db4e7ea5358aecfe78f19165772639dff';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);

// create a new project
$params = array('name' => 'Example Project Name',
                'description' => 'Example Project Description',
                'notes' => 'Example Project Notes',
                'poNumber' => '123456789',
                'intendedUse' => '1153',
                'sourceLanguage' => 'en',
                'targetLanguages' => array('fr', 'de'),
                'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                'deliveryDueDate' => '2050-12-00T00:00:00.000+0000');
$project = $client->createProject($params);

// update an existing project
$params = array('id' => $project->getId(),
                'name' => 'Example Project Name Updated',
                'description' => 'Example Project Description Updated',
                'notes' => 'Example Project Notes Updated',
                'poNumber' => $project->getPoNumber(),
                'intendedUse' => $project->getIntendedUse()->getId(),
                'sourceLanguage' => $project->getSourceLanguage()->getLanguageCode(),
                'targetLanguages' => array('fr', 'de'),
                'bidDueDate' => $project->getBidDueDate(),
                'deliveryDueDate' => $project->getDeliveryDueDate());
$project = $client->updateProject($params);

// get a specific project by id
print_r($project->getId());
$project = $client->getProject($project->getId());
print_r($project);

// get a list of open projects
$projects = $client->getOpenProjects();

// get a list of preferred vendors
$preferredVendors = $client->getPreferredVendors();

// get a list of source languages
$sourceLanguages = $client->getSourceLanguages();

// get a list of target languages
$targetLanguages = $client->getTargetLanguages();

// get a list of intended uses
$intendedUses = $client->getIntendedUses(); 
