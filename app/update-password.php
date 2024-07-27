<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

// Validate CSRF token
if ($_POST['csrf_token_password'] !== $_SESSION['csrf_token']) {
    echo 'Invalid CSRF token.';
    exit;
}

$userId = $_SESSION['bookrack-user-id'];

// form values
$oldPassword = $_POST['old-password'];
$newPassword = $_POST['new-password'];
$newPasswordConfirmation = $_POST['new-password-confirmation'];

// check if the old password is verified
global $auth;
require_once __DIR__ . '/../classes/user.php';

$tempUser = new User();

$tempUser->fetch($userId);

// check if old password matched the existing password
try {
    $signInResultOld = $auth->signInWithEmailAndPassword($tempUser->email, $oldPassword);

    $updatedUser = $auth->changeUserPassword($userId, trim($newPassword));

    $message = "Password changed successfully.";
} catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
    $message = "Old password didn't match.";
} catch (\Kreait\Firebase\Exception\AuthException $e) {
    $message = "Old password didn't match.";
} catch (\Kreait\Firebase\Exception\FirebaseException $e) {
    $message = "An unexpected error occured.";
}
echo $message;
exit;