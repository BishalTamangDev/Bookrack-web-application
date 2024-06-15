<?php
// starting session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../bookrack/vendor/autoload.php';

use Kreait\Firebase\Factory;

// google servive connection & realtime database connection
$factory = (new Factory)->withServiceAccount('bookrack-b7541-firebase-adminsdk-94yro-3018dfbec8.json')->withDatabaseUri('https://bookrack-b7541-default-rtdb.firebaseio.com/');

// initializing the realtime database
global $database;

$database = $factory->createDatabase();

// authentication initialized
$auth = $factory->createAuth();