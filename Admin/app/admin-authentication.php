<?php
// starting session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../bookrack/app/connection.php';

require_once __DIR__ . '/../../../bookrack/admin/app/admin-class.php';

// signup table method
if (isset($_POST['admin-signup-btn'])) {
    // admin class
    $admin = new Admin();

    // getting email && password
    $admin->setEmail($_POST['admin-email']);
    $hashedPassword = password_hash($_POST['admin-password'], PASSWORD_BCRYPT);
    $admin->setPassword($hashedPassword);

    // for retaining form value after submission
    $_SESSION['temp-admin-email'] =  $_POST['admin-email'];
    $_SESSION['temp-admin-password'] =  $_POST['admin-password'];

    // checking the existence of provided email address
    $emailExists = $admin->checkEmailExistence();

    if ($emailExists) {
        $_SESSION['admin-status'] = false;
        $_SESSION['admin-status-message'] = "This email address is already in use.";
        header("Location: /bookrack/admin/signup");
    } else {
        $accountCreated = $admin->register();

        if ($accountCreated) {
            $_SESSION['admin-status'] = true;
            $_SESSION['admin-status-message'] = "Signup successfully.";
            
            // unset temporary data
            unset($_SESSION['temp-admin-email']);
            unset($_SESSION['temp-admin-password']);

            header("Location: /bookrack/admin/signin");
        } else {
            $_SESSION['admin-status'] = false;
            $_SESSION['admin-status-message'] = "Signin failed.";
            header("Location: /bookrack/admin/signup");
        }
    }

    exit();
}

// sign in table method
if (isset($_POST['admin-signin-btn'])) {
    // admin class
    $admin = new Admin();

    // form values
    $admin->setEmail($_POST['admin-email']);
    $admin->setPassword($_POST['admin-password']);

    // for retaining form values after submission
    $_SESSION['temp-admin-email'] =  $_POST['admin-email'];
    $_SESSION['temp-admin-password'] =  $_POST['admin-password'];

    // checking the existence of provided email address
    $emailExists = $admin->checkEmailExistence();

    if ($emailExists) {
        // verify password
        $passwordVerified = $admin->verifyPassword();

        if ($passwordVerified) {
            // setting admin key in the session
            $_SESSION['bookrack-admin-id'] = $admin->getId();
 
            unset($_SESSION['admin-status']);
            unset($_SESSION['admin-status-message']);
            
            // unsetting the form values 
            unset($_SESSION['temp-admin-email']);
            unset($_SESSION['temp-admin-password']);
            
            // redirect to homepage
            header("Location: /bookrack/admin/dashboard");
        } else {
            // password not verified
            $_SESSION['admin-status'] = false;
            $_SESSION['admin-status-message'] = "Invalid password!";
            header("Location: /bookrack/admin/signin");
        }
    } else {
        // email not found
        $_SESSION['admin-status'] = false;
        $_SESSION['admin-status-message'] = "This email address has not been registered yet.";
        header("Location: /bookrack/admin/signin");
    }

    exit();
}
