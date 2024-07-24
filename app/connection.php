<?php
// starting session
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// google servive connection & realtime database connection

$factory = (new Factory)->withServiceAccount('google-service-account.json')->withDatabaseUri('https://bookrack-b7541-default-rtdb.firebaseio.com/');

// initializing the realtime database
global $database;
global $auth;

$database = $factory->createDatabase();

// cloud storage
$storage = $factory->createStorage();
$bucket = $storage->getBucket();

// authentication
$auth = $factory->createAuth();