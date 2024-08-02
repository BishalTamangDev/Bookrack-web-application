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

            // create space in the realtime database
            $extraUserProperties = [
                'name' => [
                    'first' => '',
                    'last' => ''
                ],
                'dob' => '',
                'gender' => '',
                'address' => [
                    'district' => '',
                    'location' => ''
                ],
                'photo' => '',
                'kyc' => [
                    'document_type' => '',
                    'front' => '',
                    'back' => '',
                ],
                'joined_date' => date("Y:m:d H:i:s"),
                'account_status' => 'pending',
                'role' => 'user'
            ];

            $response = $database->getReference("users/$uid")->set($extraUserProperties);

            if ($response) {
                require_once __DIR__ . '/../classes/notification.php';
                $notificationObj = new Notification();
                $notificationObj->newUser($uid);
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