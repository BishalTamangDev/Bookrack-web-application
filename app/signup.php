<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../functions/district-array.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Contract\Auth;

$message = "";

// Validate CSRF token
if (trim($_POST['csrf_token']) != $_SESSION['csrf_token']) {
    $message = 'An error occured.';
} else {
    // check form value
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        $message = "Please enter all the details.";
    } else {
        // Retrieve form data
        $email = $_POST['email'];
        $password = $_POST['password'];

        // signup auth method
        global $auth;
        global $database;

        // getting email && password
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userProperties = [
            'email' => $email,
            'emailVerified' => false,
            // 'phoneNumber' => '',
            'password' => $password,
            'displayName' => '',
            'photoUrl' => '',
            'disabled' => false,
        ];

        // create user
        try {
            $createdUser = $auth->createUser($userProperties);
            $uid = $createdUser->uid;

            date_default_timezone_set('Asia/Kathmandu');
            $currentDate = date("Y:m:d H:i:s");

            // create space in the realtime database
            $extraUserProperties = [
                'name_first' => '',
                'name_last' => '',
                'dob' => '',
                'gender' => '',
                'address_district' => '',
                'address_municipality' => '',
                'address_ward' => '',
                'address_tole_village' => '',
                'photo' => '',
                'document_type' => '',
                'document_front' => '',
                'document_back' => '',
                'joined_date' => $currentDate,
                'account_status' => 'pending',
                'role' => 'user'
            ];

            $response = $database->getReference("users/$uid")->set($extraUserProperties);

            if ($response) {
                $message = "true";
            }
        } catch (EmailExists $e) {
            $message = "This email address is already in use.";
        } catch (InvalidPassword $e) {
            $message = "An unexpected error occurred.";
        } catch (AuthException $e) {
            $message = "An unexpected error occurred.";
        } catch (Exception $e) {
            $message = "An unexpected error occurred.";
        }
    }
}

echo $message;
exit;