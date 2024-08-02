<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_POST['csrf_token_signin'])) {
    echo "An error occured.";
    exit;
}

if ($_POST['csrf_token_signin'] != $_SESSION['csrf_token']) {
    echo "An error occured.";
    exit;
}

require_once __DIR__ . '/../../classes/admin.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;

global $auth;
$response = "";

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

        // check if the id belongs to the admin
        $adminObj = new Admin();
        if ($adminObj->checkIfAdmin($uid)) {
            $response = true;
            $_SESSION['bookrack-admin-id'] = $uid;
            $_SESSION['idTokenString'] = $idTokenString;
        } else {
            $response = 'Not registered asdsfsf an admin!';
        }
    } catch (Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
        $response = 'An error occured.';
    } catch (Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
        $response = 'Invalid password.';
    }
} catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
    $response = 'This email address has not been registered yet!';
} catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
    $response = "Invalid password.";
}

echo $response;
exit;