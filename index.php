<?php

// starting the session
if (session_status() == PHP_SESSION_NONE)
    session_start();

// reset book offset date
if (isset($_SESSION['book-id-list']))
    unset($_SESSION['book-id-list']);

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
$currentDate = date('Y-m-d h:i:s');

$profilePagePattern = '/profile\/(view|password-change|document|update-document)/';
$adminPagesPattern = '/admin\/(admin-profile|admin-book-details|admin-books|admin-dashboard|admin-index|admin-nav|admin-signin|admin-signup|admin-user-details|admin-users|admin-order|admin-order-summary|admin-arrivals)/i';
$tab = "";

$redirected = false;

// unsigned pages
if (!isset($_SESSION['bookrack-admin-id']) && !isset($_SESSION['bookrack-user-id'])) {
    // index || landing || header
    if ($router == '/' || $router == '/landing' || $router == '/landing/' || $router == '/index' || $router == '/index/' || $router == '/header' || $router == '/header/' || preg_match('/\?i=1/', $router)) {
        if (!isset($_SESSION['bookrack-admin-id']) && !isset($_SESSION['bookrack-user-id'])) {
            if (preg_match('/\?i=1/', $router)) {
                header("Location: /landing");
            } else {
                include 'landing.php';
            }
        }
        $redirected = true;
    } elseif ($router == '/signin' || $router == '/signin/' || preg_match("/signin\/(email)/i", $router)) {
        // sign in
        include 'signin.php';
        $redirected = true;
    } elseif ($router == '/signup' || $router == '/signup/' || preg_match("/signup\/(email|email-verification)/i", $router)) {
        // signup
        $arr = explode('/', $router);

        $tab = "email";

        if (isset($arr[2])) {
            if ($arr[2] == "email" || $arr[2] == "email-verification") {
                if ($arr[2] == "email-verification") {
                    $tab = $arr[2];
                }
                include 'signup.php';
            }
        } else {
            include 'signup.php';
        }
        $redirected = true;
    } elseif ($router == '/book-details' || $router == '/book-details/' || preg_match('/^\/book-details\/-[A-Za-z0-9_-]+/', $router)) {
        // book details
        $arr = explode('/', $router);

        if (isset($arr[2]) && $arr[2] != "") {
            $bookId = $arr[2];
            include 'book-details-unsigned.php';
            $redirected = true;
        }
    } elseif ($router == '/forgot-password' || $router == '/forgot-password/' || preg_match("/forgot-password\/(email|otp|reset-password)/i", $router)) {
        // forgot password
        $arr = explode('/', $router);
        $tab = "email";

        if (isset($arr[2])) {
            $tab = $arr[2];
        }

        include 'forgot-password.php';
        $redirected = true;
    } elseif ($router == '/admin/admin-signin' || $router == '/admin/admin-signin/' || preg_match("/admin-signin\/(email)/i", $router)) {
        // admin sign in
        include 'admin/signin.php';
        $redirected = true;
    } elseif ($router == '/admin/admin-signup' || $router == 'admin/admin-signup/' || preg_match("/admin-signup\/(email|email-verification)/i", $router)) {
        // admin signup
        $arr = explode('/', $router);

        $tab = "email";

        if (isset($arr[2])) {
            if ($arr[2] == "email" || $arr[2] == "email-verification") {
                if ($arr[2] == "email-verification") {
                    $tab = $arr[2];
                }
            }
        }

        include 'admin/signup.php';
        $redirected = true;
    } else {
        include 'home.php';
        $redirected = true;
    }
}

// user signed in
if (isset($_SESSION['bookrack-user-id'])) {
    require_once __DIR__ . '/classes/user.php';
    $profileId = $_SESSION['bookrack-user-id'];

    $profileUser = new User();
    $userExists = $profileUser->checkUserExistenceById($profileId);

    // get user details
    if (!$userExists)
        header("Location: /bookrack/app/signout.php");

    $profileUser->setUserId($profileId);

    // home
    if ($router == '/home' || $router == '/home/' || preg_match('/home\?/i', $router)) {
        include 'home.php';
        $redirected = true;
    }

    // add book || edit book
    elseif ($router == '/add-book' || $router == '/add-book/' || preg_match('/add-book\/(add|edit)/i', $router)) {
        $task = "add";

        $arr = explode('/', $router);

        if (isset($arr[2])) {
            if ($arr[2] == "edit") {
                $task = "edit";
                if (isset($arr[3])) {
                    $bookId = $arr[3];
                }
            }
        }
        include 'add-book.php';
        $redirected = true;
    }


    // book details
    elseif ($router == '/book-details' || $router == '/book-details/' || preg_match('/^\/book-details\/-[A-Za-z0-9_-]+/', $router)) {
        $arr = explode('/', $router);

        if (isset($arr[2]) && $arr[2] != "") {
            $bookId = $arr[2];
            if (isset($_SESSION['bookrack-user-id'])) {
                include 'book-details.php';
                $redirected = true;
            } else {
                include 'book-details-unsigned.php';
            }
        }
    }


    // cart
    elseif ($router == '/cart' || preg_match("/cart\/(current|pending|completed|checkout)/i", $router)) {
        $arr = explode('/', $router);
        $tab = "current";
        if (isset($arr[2])) {
            if ($arr[2] == 'checkout') {
                include 'checkout.php';
            } else {
                $tab = $arr[2];
                include 'cart.php';
            }
        } else {
            include 'cart.php';
        }
        $redirected = true;
    }

    // wishlist
    elseif ($router == '/wishlist') {
        include 'wishlist.php';
        $redirected = true;
    }


    // my books
    elseif ($router == '/my-books') {
        include 'my-books.php';
        $redirected = true;
    }


    // book requests
    elseif ($router == '/requests') {
        include 'requests.php';
        $redirected = true;
    }


    // profile
    elseif ($router == '/profile' || $router == '/profile/' || preg_match($profilePagePattern, $router)) {
        $tab = "view";
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
            } elseif (preg_match('/document/', $arr[2])) { // wishlist
                $arr = explode('/', $arr[2]);
                $tab = $arr[0];
            } elseif (preg_match('/requested-books/', $arr[2])) { // requested-books
                $arr = explode('/', $arr[2]);
                $tab = $arr[0];
            } elseif (preg_match('/password-change/', $arr[2])) { // password change
                $arr = explode('/', $arr[2]);
                $tab = $arr[0];
            } else {
                header("Location: /bookrack/profile");
            }
        }
        include 'profile.php';
        $redirected = true;
    }

    // signout
    elseif ($router == '/signout' || $router == '/signout/') {
        include 'signout.php';
        $redirected = true;
    } else {
        header("Location: /bookrack/home");
    }
}

// admin
if (isset($_SESSION['bookrack-admin-id'])) {
    require_once __DIR__ . '/classes/admin.php';
    $adminId = $_SESSION['bookrack-admin-id'];

    $profileAdmin = new Admin();
    $adminExists = $profileAdmin->checkAdminExistenceById($adminId);

    if (!$adminExists)
        header("Location: /bookrack/admin/app/admin-signout.php");

    if ($router == '/admin' || $router == '/admin/' || preg_match($adminPagesPattern, $router)) {
        if (preg_match($adminPagesPattern, $router)) {
            $arr = explode('/', $router);

            // dashboard
            if (preg_match('/admin-dashboard$/', $arr[2])) {
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
            } elseif ((preg_match('/admin-users$/', $arr[2]))) {
                $nav = "users";
                include 'admin/users.php';
            } elseif (preg_match('/admin-user-details$/', $arr[2]) && isset($arr[3]) && $arr[3] != "") {
                $nav = "user-details";
                $userId = $arr[3];
                include 'admin/user-details.php';
            } elseif ((preg_match('/admin-books$/', $arr[2]))) {
                $nav = "books";
                include 'admin/books.php';
            } elseif (preg_match('/admin-book-details$/', $arr[2]) && isset($arr[3]) && $arr[3] != "") {
                $nav = "book-details";
                $page = "book-details";
                $bookId = $arr[3];
                include 'admin/book-details.php';
            } elseif ((preg_match('/admin-orders$/', $arr[2]))) {
                $nav = "orders";
                include 'admin/order.php';
            } elseif ((preg_match('/admin-order-summary$/', $arr[2])) && isset($arr[3]) && $arr[3] != "") {
                $nav = "order-summary";
                $cartId = $arr[3];
                include 'admin/order-summary.php';
            } elseif ((preg_match('/admin-arrivals$/', $arr[2]))) {
                $nav = "arrivals";
                include 'admin/arrivals.php';
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
    $redirected = true;
}

// default page
if (!$redirected) {
    include '404.php';
}