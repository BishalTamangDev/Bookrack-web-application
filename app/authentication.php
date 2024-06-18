<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../bookrack/app/connection.php';

require_once __DIR__ . '/../../bookrack/app/user-class.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Contract\Auth;

$adminEmail = "info.bookrack@gmail.com";

// signup table method
if (isset($_POST['signup-btn'])) {
    // user class
    $user = new User();

    // getting email && password
    $user->setEmail($_POST['email']);
    $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user->setPassword($hashedPassword);

    // for retaining form value after submission
    $_SESSION['temp-email'] =  $_POST['email'];
    $_SESSION['temp-password'] =  $_POST['password'];

    // checking the existence of provided email address
    $emailExists = $user->checkEmailExistence();

    if ($emailExists) {
        $_SESSION['status'] = false;
        $_SESSION['status-message'] = "This email address is already in use.";
        header("Location: /bookrack/signup");
    } else {
        $accountCreated = $user->registerUser();

        if ($accountCreated) {
            $_SESSION['status'] = true;
            $_SESSION['status-message'] = "Signup successfully.";
            
            // unset temporary data
            unset($_SESSION['temp-email']);
            unset($_SESSION['temp-password']);

            header("Location: /bookrack/signin");
        } else {
            $_SESSION['status'] = false;
            $_SESSION['status-message'] = "Signin failed.";
            header("Location: /bookrack/signup");
        }
    }

    exit();
}


// sign in table method
if (isset($_POST['signin-btn'])) {
    // user class
    $user = new User();

    // form values
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);

    // for retaining form values after submission
    $_SESSION['temp-email'] =  $_POST['email'];
    $_SESSION['temp-password'] =  $_POST['password'];

    // checking the existence of provided email address
    $emailExists = $user->checkEmailExistence();

    if ($emailExists) {
        // verify password
        $passwordVerified = $user->verifyPassword();

        if ($passwordVerified) {
            // setting user key in the session
            $_SESSION['bookrack-user-id'] = $user->getUserId();

            unset($_SESSION['status']);
            unset($_SESSION['status-message']);
            
            // unsetting the form values 
            unset($_SESSION['temp-email']);
            unset($_SESSION['temp-password']);
            
            // redirect to homepage
            header("Location: /bookrack/home");
        } else {
            // password not verified
            $_SESSION['status'] = false;
            $_SESSION['status-message'] = "Invalid password!";
            header("Location: /bookrack/signin");
        }
    } else {
        // email not found
        $_SESSION['status'] = false;
        $_SESSION['status-message'] = "This email address has not been registered yet.";
        header("Location: /bookrack/signin");
    }

    exit();
}








// signup auth method
if (isset($_POST['signup-auth-btn'])) {
    // getting email && password
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userProperties = [
        'email' => $email,
        'emailVerified' => false,
        'password' => $password,
    ];

    // create user
    try {
        $user = $auth->createUserWithEmailAndPassword($email, $password);
        $_SESSION['status'] = "Signup successfully.";
        header("Location: /bookrack/signin/");
    } catch (EmailExists $e) {
        $_SESSION['status'] = "This email address is already in use.";
        header("Location: /bookrack/signup/");
    } catch (InvalidPassword $e) {
        $_SESSION['status'] = "The password is not valid.";
        header("Location: /bookrack/signup/");
    } catch (AuthException $e) {
        $_SESSION['status'] = "An authentication error occurred.";
        header("Location: /bookrack/signup/");
    } catch (\Exception $e) {
        $_SESSION['status'] = "An unexpected error occurred.";
        header("Location: /bookrack/signup/");
    }
    exit();
}

// signin auth method
if (isset($_POST['signin-auth-btn'])) {
    $email = $_POST['email'] . "<br/>";
    $password = $_POST['password'] . "<br/>";

    echo $email . "<br.>";
    echo $password . "<br.>";

    try {
        $user = $auth->signInWithEmailAndPassword($email, $password);
        $_SESSION['status'] = "User signed in successfully.";
        header("Location: /bookrack/home");
    } catch (EmailNotFound $e) {
        $_SESSION['status'] = "There is no user record corresponding to this email.";
        header("Location: /bookrack/signin");
    } catch (InvalidPassword $e) {
        $_SESSION['status'] = "The password is invalid for the given email.";
        header("Location: /bookrack/signin");
    } catch (AuthException $e) {
        $_SESSION['status'] = "An authentication error occurred.";
        header("Location: /bookrack/signin");
    } catch (\Exception $e) {
        echo ($e);
        // $_SESSION['status'] = "An unexpected error occurred.";
        // header("Location: /bookrack/signin");
    }

    exit();
}