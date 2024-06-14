<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'connection.php';

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Contract\Auth;

$adminEmail = "info.bookrack@gmail.com";

// signup table method
if (isset($_POST['signup-btn'])) {
    // getting email && password
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $_SESSION['temp-email'] =  $email;
    $_SESSION['temp-password'] =  $password;

    $ref_table = "users";

    $response = $database->getReference($ref_table)->getSnapshot()->getValue();
    print_r($response);

    // checking for the existence of email address
    $emailExists = false;
    foreach ($response as $key => $row) {
        if (isset($row['email']) && $row['email'] == $email) {
            $emailExists = true;
        }
    }

    if ($emailExists) {
        $_SESSION['status'] = "This email address is already in use.";
        header("Location: /bookrack/signup");
    } else {
        $postData = [
            'email' => $email,
            'password' => $hashedPassword,
        ];
        $postRef = $database->getReference($ref_table)->push($postData);

        if ($postRef) {
            $_SESSION['status'] = "Signup successfully.";
            
            // unset temporary data
            unset($_SESSION['temp-email']);
            unset($_SESSION['temp-password']);

            header("Location: /bookrack/signin");
        } else {
            $_SESSION['status'] = "Signin failed.";
            header("Location: /bookrack/signup");
        }
    }

    exit();
}


// sign in table method
if (isset($_POST['signin-btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $_SESSION['temp-email'] =  $email;
    $_SESSION['temp-password'] =  $password;

    $ref_table = "users";
    $rowKey = "";

    $response = $database->getReference($ref_table)->getSnapshot()->getValue();

    // checking for the existence of email address
    $emailExists = false;
    foreach ($response as $key => $row) {
        if ($row['email'] == $email) {
            $emailExists = true;
            $rowKey = $key;
        }
    }

    if (!$emailExists) {
        $_SESSION['status'] = "This email address has not been registered yet.";
        header("Location: /bookrack/signin");
    } else {
        // checking for password

        $response = $database->getReference($ref_table)->getChild($rowKey)->getSnapshot()->getValue();

        if (password_verify($password, $response['password'])) {
            $_SESSION['bookrack-user-id'] = $rowKey;
            $_SESSION['status'] = "Signin successful.";
            
            unset($_SESSION['temp-email']);
            unset($_SESSION['temp-password']);
            
            header("Location: /bookrack/home");
        } else {
            $_SESSION['status'] = "Invalid password!";
            header("Location: /bookrack/signin");
        }
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