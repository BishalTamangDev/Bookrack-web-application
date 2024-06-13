<?php
$request = $_SERVER['REQUEST_URI'];
$router = str_replace('/bookrack', '', $request);

// echo "Index request: ".$request."<br/>";
// echo "Index router: ".$router."<br/>";

$profilePagePattern = '/profile\/(view-profile|edit-profile|password-change|my-books|wishlist|requested-books|earning)/';

$tab = "";

if ($router == '/' || $router == '/landing' || $router == '/landing/' || $router == '/index' || $router == '/index/' || $router == '/header' || $router == '/header/') {
    include ('landing.php');
} elseif ($router == '/home' || $router == '/home/' || preg_match('/home\?/i', $router)) {
    include 'home.php';
} elseif ($router == '/add-book' || $router == '/add-book/') {
    include 'add-book.php';
} elseif ($router == '/book-details' || $router == '/book-details/') {
    include 'book-details.php';
} elseif ($router == '/cart' || preg_match("/cart\/[a-z][A-Z]/i", $router)) {
    $arr = explode('/', $router);
    $tab = isset($arr[2]) ? $arr[2] : "current";
    include 'cart.php';
} elseif ($router == '/forgot-password' || $router == '/forgot-password/' || preg_match("/forgot-password\/[a-z][A-Z]/i", $router)) {
    $arr = explode('/', $router);

    if (isset($arr[2])) {
        $tab = $arr[2];
    }

    include 'forgot-password.php';
} elseif ($router == '/profile' || $router == '/profile/' || preg_match($profilePagePattern, $router)) {
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
} elseif ($router == '/signin' || $router == '/signin/') {
    include 'signin.php';
} elseif ($router == '/signup' || $router == '/signup/' || preg_match("/signup\/[a-z][A-Z]/i", $router)) {
    $arr = explode('/', $router);

    if (isset($arr[2])) {
        if ($arr[2] == "email-verification" || $arr[2] == "") {
            if ($arr[2] == "email-verification") {
                $tab = $arr[2];
            } else {
                $tab = "email";
            }
        }
    }

    include 'signup.php';
} elseif ($router == '/admin' || $router == '/admin/') {
    include '/dashboard.php';
} else {
    include '404.php';
}