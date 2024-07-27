<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

// Validate CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo 'Invalid CSRF token.';
    exit;
}

require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../functions/district-array.php';
require_once __DIR__ . '/../functions/delete-cloud-file.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Contract\Auth;

$status = false;
$message = "";

// Retrieve form data
if (!isset($_POST['email']) || !isset($_POST['password'])) {

} else {
    global $auth;

    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = $_POST['email'];
    $password = $_POST['password'];

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

            if ($isUser) {
                $status = true;
                $message = "Signin successfully";
                $_SESSION['bookrack-user-id'] = $uid;
                $_SESSION['idTokenString'] = $idTokenString;
            } else {
                $message = 'This email address has not been registered yet!';
            }
        } catch (Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
            $message = 'Unexpected error occured.';
        } catch (Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
            $message = 'Invalid password.';
        }
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        $message = 'This email address has not been registered yet!';
    } catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
        $message = "Invalid password.";
    }
}

$className = $status ? "text-success success" : "text-danger";

$output = "<p class='m-0 $className' id='message'> $message </p>";

echo $output;

exit;