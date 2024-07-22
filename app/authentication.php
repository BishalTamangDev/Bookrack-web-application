<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/user-class.php';
require_once __DIR__ . '/functions.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Contract\Auth;

// signup auth method
if (isset($_POST['signup-btn'])) {
    global $auth;
    global $database;

    $status = 0;

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

        $database->getReference("users/$uid")->set($extraUserProperties);

        $status = 1;
        $_SESSION['status-message'] = "Signed up successfully.";
    } catch (EmailExists $e) { 
        $_SESSION['status-message'] = "This email address is already in use.";
    } catch (InvalidPassword $e) {
        $_SESSION['status-message'] = "An unexpected error occurred.";
    } catch (AuthException $e) {
        $_SESSION['status-message'] = "An unexpected error occurred.";
    } catch (Exception $e) {
        $_SESSION['status-message'] = "An unexpected error occurred.";
    }

    $_SESSION['status'] = $status ? true : false;

    if ($status)
        header("Location: /bookrack/signin");
    else
        header("Location: /bookrack/signup");

    exit();
}

// signin auth method
if (isset($_POST['signin-btn'])) {
    global $auth;
    $status = 0;

    $email = $_POST['email'];
    $password = $_POST['password'];

    $_SESSION['temp-email'] = $email;

    try {
        $user = $auth->getUserByEmail("$email");

        $signInResult = $auth->signInWithEmailAndPassword($email, $password);

        // token is generated
        $idTokenString = $signInResult->idToken();

        try {
            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
            $uid = $signInResult->firebaseUserId();

            
            // check if the id belong to the user [role == 'user']
            $userObj = new User();
            $isUser = $userObj->checkIfUser($uid);

            if($isUser) {
                $status = 1;
                $_SESSION['bookrack-user-id'] = $uid;
                $_SESSION['idTokenString'] = $idTokenString;
                unset($_SESSION['temp-email']);
            } else {
                $_SESSION['status-message'] = 'This email address has not been registered yet!';
            }
        } catch (Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
            $_SESSION['status-message'] = 'Unexpected error occured.';
        } catch (Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
            $_SESSION['status-message'] = 'Invalid password.';
        }
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        $_SESSION['status-message'] = 'This email address has not been registered yet!';
    } catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
        $_SESSION['status-message'] = "Invalid password.";
    }

    $_SESSION['status'] = $status ? true : false;

    if ($status)
        header("Location: /bookrack/home");
    else
        header("Location: /bookrack/signin");

    exit();
}