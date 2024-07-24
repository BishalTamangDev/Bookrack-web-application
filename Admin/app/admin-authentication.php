<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../../app/connection.php';
require_once __DIR__ . '/../../classes/admin.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;

// signup
if (isset($_POST['admin-signup-btn'])) {
    $status = 0;

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

    // for retaining form value after submission
    $_SESSION['temp-email'] = $email;
    $_SESSION['temp-password'] = $password;

    try {
        $createdUser = $auth->createUser($adminProperties);
        $uid = $createdUser->uid;

        // create space in the realtime database
        $extraAdminProperties = [
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
                'document_type' => 'citizenship',
                'front' => '',
                'back' => '',
            ],
            'joined_date' => date("Y:m:d H:i:s"),
            'account_status' => 'pending',
            'role' => 'admin'
        ];

        unset($_SESSION['temp-password']);

        $database->getReference('admins/' . $uid)->set($extraAdminProperties);
        $status = 1;

        $_SESSION['status-message'] = "Signed up successfully.";
    } catch (EmailExists $e) {
        $_SESSION['status-message'] = "This email address is already in use.";
    } catch (InvalidPassword $e) {
        $_SESSION['status-message'] = "The password is not valid.";
    } catch (AuthException $e) {
        $_SESSION['status-message'] = "An unexpected error occurred.";
    } catch (Exception $e) {
        $_SESSION['status-message'] = "An unexpected error occurred.";
    }

    $_SESSION['status'] = $status ? true : false;

    if ($status)
        header("Location: /bookrack/admin/admin-signin");
    else
        header("Location: /bookrack/admin/admin-signup");

    exit();
}

// signin
if (isset($_POST['admin-signin-btn'])) {
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

            // check if the id belongs to the admin
            $adminObj = new Admin();

            if($adminObj->checkIfAdmin($uid)) {
                $status = 1;
                unset($_SESSION['temp-email']);
                
                $_SESSION['bookrack-admin-id'] = $uid;
                $_SESSION['idTokenString'] = $idTokenString;
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
        header("Location: /bookrack/admin/admin-dashboard");
    else
        header("Location: /bookrack/admin/admin-signin");
    exit();
}