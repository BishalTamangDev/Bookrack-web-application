<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_POST['csrf_token_signup'])) {
    echo "An error occured.";
    exit;
}

if ($_POST['csrf_token_signup'] != $_SESSION['csrf_token']) {
    echo "An error occured.";
    exit;
}

require_once __DIR__ . '/../../classes/admin.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;

global $auth;
global $database;

$email = $_POST['email'];
$password = $_POST['password'];

$adminProperties = [
    'email' => $email,
    'emailVerified' => false,
    'password' => $password,
    'displayName' => '',
    'photoUrl' => '',
    'disabled' => false,
];

try {
    $createdUser = $auth->createUser($adminProperties);
    $uid = $createdUser->uid;

    date_default_timezone_set('Asia/Kathmandu');
    $currentDate = date("Y:m:d H:i:s");

    // create space in the realtime database
    $extraAdminProperties = [
        'name_first' => "",
        'name_last' => "",
        'dob' => '',
        'gender' => '',
        'address_district' => '',
        'address_location' => '',
        'photo' => '',
        'document_type' => 'citizenship',
        'document_front' => '',
        'document_back' => '',
        'joined_date' => $currentDate,
        'account_status' => 'pending',
        'role' => 'admin'
    ];
    $database->getReference("admins/$uid")->set($extraAdminProperties);
    $message = true;
} catch (EmailExists $e) {
    $message = "This email address is already in use.";
} catch (InvalidPassword $e) {
    $message = "The password is not valid.";
} catch (AuthException $e) {
    $message = "An unexpected error occurred.";
} catch (Exception $e) {
    $message = "An unexpected error occurred.";
}

echo $message;

exit;