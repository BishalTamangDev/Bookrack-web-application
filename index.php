<?php
$request = $_SERVER['REQUEST_URI'];
$router = str_replace('/bookrack', '', $request);

// echo "Request: ".$request."<br/>";
// echo "Router: ".$router."<br/>";

if ($router == '/' || $router == '/landing' || $router == '/index') {
    include ('landing.php');
} elseif ($router == '/home') {
    include 'home.php';
} elseif ($router == '/add-book') {
    include 'add-book.php';
} elseif ($router == '/book-details') {
    include 'book-details.php';
} elseif ($router == '/cart' || preg_match("/cart\/[a-z][A-Z]/i", $router)) {
    $arr = explode('/', $router);
    $tab = isset($arr[2]) ? $arr[2] : "current";
    include 'cart.php';
} elseif ($router == '/forgot-password' || preg_match("/forgot-password\/[a-z][A-Z]/i", $router)) {
    $arr = explode('/', $router);

    if (isset($arr[2])) {
        $tab = $arr[2];
    }

    include 'forgot-password.php';
} elseif ($router == '/profile' || preg_match("/profile\/[a-z][A-Z]/i", $router)) {
    $arr = explode('/', $router);
    $tab = isset($arr[2]) ? $arr[2] : "view-profile";
    include 'profile.php';
} elseif ($router == '/signin') {
    include 'signin.php';
} elseif ($router == '/signup' || preg_match("/signup\/[a-z][A-Z]/i", $router)) {
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
} elseif ($router == '/admin') {
    include '/dashboard.php';
} else {
    include '404.php';
}