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
const BASE_API_URL = 'https://api-stage.cloudwords.com';
const API_VERSION = '1';
const AUTHENTICATION_TOKEN = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';

$client = new CloudwordsClient(BASE_API_URL, API_VERSION, AUTHENTICATION_TOKEN);

// get detail project
$project = $client->get_project(10173);
print_r($project);

