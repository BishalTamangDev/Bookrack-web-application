<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_POST['update-password-btn'])) {
    $status = 0;
    $userId = $_SESSION['bookrack-user-id'];

    // form values
    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];
    $newPasswordConfirmation = $_POST['new-password-confirmation'];

    // temporary password saving
    $_SESSION['temp-old-password'] = $_POST['old-password'];
    $_SESSION['temp-new-password'] = $_POST['new-password'];
    $_SESSION['temp-new-password-confirmation'] = $_POST['new-password-confirmation'];

    // check if the new-old and new password are sam
    if ($oldPassword == $newPassword) {
        $_SESSION['status-message'] = "Make sure old password and new password are different.";
    } else {
        // check if the new password and password for confirmation are same
        if ($newPassword != $newPasswordConfirmation) {
            $_SESSION['status-message'] = "New password and password for confirmation didn't match.";
        } else {
            // check if the old password is verified
            require_once __DIR__ . '/../../bookrack/app/user-class.php';

            $tempUser = new User();

            $tempUser->fetch($userId);

            global $auth;

            // check if old password matched the existing password
            try {
                $signInResultOld = $auth->signInWithEmailAndPassword($tempUser->email, $oldPassword);

                $updatedUser = $auth->changeUserPassword($userId, trim($newPassword));

                $status = 1;
                unset($_SESSION['temp-old-password']);
                unset($_SESSION['temp-new-password']);
                unset($_SESSION['temp-new-password-confirmation']);

                $_SESSION['status-message'] = "Password changed successfully.";
            } catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
                $_SESSION['status-message'] = "Old password didn't match.";
            } catch (\Kreait\Firebase\Exception\AuthException $e) {
                $_SESSION['status-message'] = "Old password didn't match.";
            } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
                $_SESSION['status-message'] = "An unexpected error occured.";
            }
        }
    }

    $_SESSION['status'] = $status == 1 ? true : false;
    header("Location: /bookrack/profile/password-change");
    exit();
}
