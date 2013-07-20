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

require_once "bootstrap.php";

// create an instance of the api client
const BASE_API_URL = 'https://api.cloudwords.com';
const API_VERSION = '1';
const AUTHENTICATION_TOKEN = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new CloudwordsClient(BASE_API_URL, API_VERSION, AUTHENTICATION_TOKEN);

// create a new project
$params = array('name' => 'Example Project Name',
                'description' => 'Example Project Description',
                'notes' => 'Example Project Notes',
                'poNumber' => '123456789',
                'intendedUse' => '<ENTER_INTENDED_USE_ID_HERE>',
                'sourceLanguage' => 'en',
                'targetLanguages' => array('fr', 'de'),
                'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                'deliveryDueDate' => '2050-12-00T00:00:00.000+0000');
$project = $client->create_project($params);

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
$project = $client->update_project($params);

// get a specific project by id
$project = $client->get_project($project->getId());

// get a list of open projects
$projects = $client->get_open_projects();

// get a list of preferred vendors
$preferred_vendors = $client->get_preferred_vendors();

// get a list of source languages
$source_languages = $client->get_source_languages();

// get a list of target languages
$target_languages = $client->get_target_languages();

// get a list of intended uses
$intended_uses = $client->get_intended_uses();

