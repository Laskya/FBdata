<?php
ini_set('display_errors', 1);
define('DS', DIRECTORY_SEPARATOR);
require('autoload.php');
$db = new MyDB;
$json = new Json;