<?php

// starting the session
if (session_status() == PHP_SESSION_NONE)
    session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// request & router
$request = $_SERVER['REQUEST_URI'];
$router = str_replace('/bookrack', '', $request);

// realtime database connection file
require_once __DIR__ . '/app/connection.php';

// echo "Index request: ".$request."<br/>";
// echo "Index router: ".$router."<br/>";

// Set the default timezone to Nepal
date_default_timezone_set("Asia/Kathmandu");

$profilePagePattern = '/profile\/(view-profile|edit-profile|password-change|kyc|update-kyc|my-books|wishlist|requested-books|earning)/';
$adminPagesPattern = '/admin\/(admin-profile|admin-book-details|admin-book-request-details|admin-book-requests|admin-books|admin-dashboard|admin-index|admin-nav|admin-notification|admin-rent|admin-signin|admin-signup|admin-user-details|admin-users)/i';
$tab = "";

// index || landing || header
if ($router == '/' || $router == '/landing' || $router == '/landing/' || $router == '/index' || $router == '/index/' || $router == '/header' || $router == '/header/' || preg_match('/\?i=1/', $router)) {
    if (preg_match('/\?i=1/', $router)) {
        header("Location: /landing");
    } else {
        include ('landing.php');
    }
}

// home
elseif ($router == '/home' || $router == '/home/' || preg_match('/home\?/i', $router)) {
    include 'home.php';
}


// add book || edit book
elseif ($router == '/add-book' || $router == '/add-book/' || preg_match('/add-book\/(add|edit)/i', $router)) {
    $task = "add";

    $arr = explode('/', $router);

    if (isset($arr[2])) {
        if ($arr[2] == "edit") {
            $task = "edit";
            if(isset($arr[3])){
                $bookId = $arr[3];
            }
        }
    }
    include 'add-book.php';
}


// book details
elseif ($router == '/book-details' || $router == '/book-details/' || preg_match('/^\/book-details\/-[A-Za-z0-9_-]+/', $router)) {
    $arr = explode('/', $router);

    if (isset($arr[2]) && $arr[2] != "") {
        $bookId = $arr[2];
        if (isset($_SESSION['bookrack-user-id'])) {
            include 'book-details.php';
        } else {
            include 'book-details-unsigned.php';
        }
    } else {
        include '404.php';
    }
}


// cart
elseif ($router == '/cart' || preg_match("/cart\/(current|pending|completed)/i", $router)) {
    $arr = explode('/', $router);
    $tab = isset($arr[2]) ? $arr[2] : "current";
    include 'cart.php';
}


// forgot password
elseif ($router == '/forgot-password' || $router == '/forgot-password/' || preg_match("/forgot-password\/(email|otp|reset-password)/i", $router)) {
    $arr = explode('/', $router);
    $tab = "email";

    if (isset($arr[2])) {
        $tab = $arr[2];
    }

    include 'forgot-password.php';
}


// profile
elseif ($router == '/profile' || $router == '/profile/' || preg_match($profilePagePattern, $router)) {
    $tab = "view-profile";
    if (preg_match($profilePagePattern, $router)) {
        // echo "Pattern matched<br/>";
        $arr = explode('/', $router);
        // print_r($arr);

        if (preg_match('/my-books/', $arr[2])) {
            // echo $arr[2]."<br/>";
            $arr = explode('?', $arr[2]);
            // echo "Arr 0 ".$arr[0]."<br/>";
            $tab = $arr[0];

            if (isset($arr[1])) {
                // echo "Arr 1 ".$arr[1]."<br/>";
                $bookStateArr = explode('=', $arr[1]);
                // print_r($bookStateArr);
                $bookState = $bookStateArr[1];
            } else {
                $bookState = "all";
            }
        } elseif (preg_match('/edit-profile/', $arr[2])) { // edit-profile
            $arr = explode('/', $arr[2]);
            $tab = $arr[0];
        } elseif (preg_match('/kyc/', $arr[2])) { // wishlist
            $arr = explode('/', $arr[2]);
            $tab = $arr[0];
        } elseif (preg_match('/wishlist/', $arr[2])) { // wishlist
            $arr = explode('/', $arr[2]);
            $tab = $arr[0];
        } elseif (preg_match('/requested-books/', $arr[2])) { // requested-books
            $arr = explode('/', $arr[2]);
            $tab = $arr[0];
        } elseif (preg_match('/earning/', $arr[2])) { // earning
            $arr = explode('/', $arr[2]);
            $tab = $arr[0];
        } elseif (preg_match('/password-change/', $arr[2])) { // password change
            $arr = explode('/', $arr[2]);
            $tab = $arr[0];
        }
    }
    include 'profile.php';
}


// signin
elseif ($router == '/signin' || $router == '/signin/' || preg_match("/signin\/(email|authentication)/i", $router)) {
    $arr = explode('/', $router);

    if (isset($arr[2]) && $arr[2] == "authentication") {
        include __DIR__ . '/../bookrack/app/authentication.php';
    } else {
        include 'signin.php';
    }
}


// signup
elseif ($router == '/signup' || $router == '/signup/' || preg_match("/signup\/(email|authentication|email-verification)/i", $router)) {
    $arr = explode('/', $router);

    $tab = "email";

    if (isset($arr[2])) {
        if ($arr[2] == "email" || $arr[2] == "email-verification") {
            if ($arr[2] == "email-verification") {
                $tab = $arr[2];
            }
            include 'signup.php';
        } else if ($arr[2] == "authentication") {
            include __DIR__ . '/../bookrack/app/authentication.php';
        }
    } else {
        include 'signup.php';
    }
}

// signout
elseif ($router == '/signout' || $router == '/signout/') {
    include 'signout.php';
}


// admin
elseif ($router == '/admin' || $router == '/admin/' || preg_match($adminPagesPattern, $router)) {
    if (preg_match($adminPagesPattern, $router)) {
        $arr = explode('/', $router);

        // dashboard
        if (preg_match('/admin-dashboard/', $arr[2])) {
            $nav = "dashboard";
            include 'admin/dashboard.php';
        } elseif (preg_match('/admin-profile$/', $arr[2])) {
            // profile
            $nav = "profile";
            $tab = "view";
            if (isset($arr[3])) {
                if ($arr[3] == "") {
                    include 'admin/profile.php';
                } elseif (in_array($arr[3], ["", "view", "edit", "document", "password"])) {
                    $tab = $arr[3] != "" ? $arr[3] : "view";
                    include 'admin/profile.php';
                } else {
                    include '404.php';
                }
            } else {
                include 'admin/profile.php';
            }
        } elseif ((preg_match('/admin-notification$/', $arr[2]))) {
            $nav = "notification";
            include 'admin/notification.php';
        } elseif ((preg_match('/admin-users/', $arr[2]))) {
            $nav = "users";
            include 'admin/users.php';
        } elseif (preg_match('/admin-user-details$/', $arr[2]) && isset($arr[3]) && $arr[3] != "") {
            $nav = "user-details";
            $userId = $arr[3];
            include 'admin/user-details.php';
        } elseif ((preg_match('/admin-books/', $arr[2]))) {
            $nav = "books";
            include 'admin/books.php';
        } elseif (preg_match('/admin-book-details$/', $arr[2]) && isset($arr[3]) && $arr[3] != "") {
            $nav = "book-details";
            $bookId = $arr[3];
            include 'admin/book-details.php';
        } elseif ((preg_match('/admin-book-requests/', $arr[2]))) {
            $nav = "requests";
            include 'admin/book-requests.php';
        } elseif ((preg_match('/admin-request-details$/', $arr[2]))) {
            $nav = "request-details";
            include 'admin/book-request-details.php';
        } elseif ((preg_match('/admin-rent-history/', $arr[2]))) {
            $nav = "rent-history";
            include 'admin/rent.php';
        } elseif ((preg_match('/admin-signin$/', $arr[2]))) {
            include 'admin/signin.php';
        } elseif ((preg_match('/admin-signup$/', $arr[2]))) {
            include 'admin/signup.php';
        } else {
            include '404.php';
        }
    } else {
        include 'admin/dashboard.php';
    }
}
// default page
else {
    include '404.php';
}