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

define('HOME_DIR', realpath(dirname(__FILE__) . '/../..'));
define('SRC_DIR', HOME_DIR . '/src');
define('DATA_DIR', HOME_DIR . '/data');

require_once SRC_DIR . "/cloudwords_client.php";
require_once "PHPUnit/Framework.php";

