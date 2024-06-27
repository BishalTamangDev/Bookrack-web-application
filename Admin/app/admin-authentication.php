<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../../../bookrack/app/connection.php';
require_once __DIR__ . '/../../../bookrack/admin/app/admin-class.php';

// signup table method
if (isset($_POST['admin-signup-btn'])) {
    // admin class
    $admin = new Admin();

    // getting email && password
    $admin->setEmail($_POST['email']);
    $admin->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));

    // for retaining form value after submission
    $_SESSION['temp-email'] = $_POST['email'];
    $_SESSION['temp-password'] = $_POST['password'];

    // checking the existence of provided email address
    $emailExists = $admin->checkEmailExistence();

    if ($emailExists) {
        $_SESSION['status'] = false;
        $_SESSION['status-message'] = "This email address is already in use.";
        header("Location: /bookrack/admin/admin-signup");
    } else {
        $accountCreated = $admin->register();

        if ($accountCreated) {
            $_SESSION['status'] = true;
            $_SESSION['status-message'] = "Signed up successfully.";

            // unset temporary data
            unset($_SESSION['temp-email']);
            unset($_SESSION['temp-password']);

            header("Location: /bookrack/admin/admin-signin");
        } else {
            $_SESSION['status'] = false;
            $_SESSION['status-message'] = "Signup failed.";
            header("Location: /bookrack/admin/admin-signup");
        }
    }
    exit();
}

// sign in table method
if (isset($_POST['admin-signin-btn'])) {
    // admin class
    $admin = new Admin();

    // form values
    $admin->setEmail($_POST['email']);
    $admin->setPassword($_POST['password']);

    // for retaining form values after submission
    $_SESSION['temp-email'] = $_POST['email'];
    $_SESSION['temp-password'] = $_POST['password'];

    // checking the existence of provided email address
    $emailExists = $admin->checkEmailExistence();

    if ($emailExists) {
        // verify password
        $passwordVerified = $admin->verifyPassword();

        if ($passwordVerified) {
            // setting admin key in the session
            $_SESSION['bookrack-admin-id'] = $admin->getId();

            unset($_SESSION['status']);
            unset($_SESSION['status-message']);

            // unsetting the form values 
            unset($_SESSION['temp-email']);
            unset($_SESSION['temp-password']);

            // redirect to homepage
            header("Location: /bookrack/admin/admin-dashboard");
        } else {
            // password not verified
            $_SESSION['status'] = false;
            $_SESSION['status-message'] = "Invalid password!";
            header("Location: /bookrack/admin/admin-signin");
        }
    } else {
        // email not found
        $_SESSION['status'] = false;
        $_SESSION['status-message'] = "This email address has not been registered yet.";
        header("Location: /bookrack/admin/admin-signin");
    }
    exit();
}
