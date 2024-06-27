<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if(isset($_POST['update-password-btn'])){
    // check for same new password
    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];
    $newPasswordConfirmation = $_POST['new-password-confirmation'];

    // temporary password saving
    $_SESSION['temp-old-password'] = $_POST['old-password'];
    $_SESSION['temp-new-password'] = $_POST['new-password'];
    $_SESSION['temp-new-password-confirmation'] = $_POST['new-password-confirmation'];
    
    // check if the new password and password for confirmation are same
    if($newPassword != $newPasswordConfirmation){
        $_SESSION['status'] = false;
        $_SESSION['status-message'] = "New password and password for confirmation didn't match.";
    }else{
        // check if the old password is verified
        require_once __DIR__ . '/../../bookrack/app/user-class.php';

        $tempUser = new User();

        // fetch user details from database
        $tempUser->fetch($_SESSION['bookrack-user-id']);

        // check if old password matched the existing password
        $status = password_verify($oldPassword, $tempUser->getPassword());

        if($status){
            // old password matched
            // check if the new password is same as existing password
            $status =  password_verify($newPassword, $tempUser->getPassword());

            if($status){
                // new password is same as existing password
                $_SESSION['status'] = false;
                $_SESSION['status-message'] = "Please enter the new password different than existing password.";
            }else{
                // update the password

                $property = [
                    'password' => password_hash($newPassword,PASSWORD_BCRYPT)
                ];

                $status = $database->getReference("users/{$tempUser->getUserId()}")->update($property);

                if($status){
                    $_SESSION['status'] = true;
                    $_SESSION['status-message'] = "Password has been changed.";

                    // unset temporary session password
                    unset($_SESSION['temp-old-password']);
                    unset($_SESSION['temp-new-password']);
                    unset($_SESSION['temp-new-password-confirmation']);
                }else{
                    $_SESSION['status'] = false;
                    $_SESSION['status-message'] = "Error occured in changing password.";
                }
            }
        }else{
            $_SESSION['status'] = false;
            $_SESSION['status-message'] = "The old password is incorrect.";
        }
    }
    header("Location: /bookrack/profile/password-change");
    exit();
}
