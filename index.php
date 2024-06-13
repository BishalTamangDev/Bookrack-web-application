<?php
$request = $_SERVER['REQUEST_URI'];
$router = str_replace('/bookrack', '', $request);

// echo "Index request: ".$request."<br/>";
// echo "Index router: ".$router."<br/>";

$profilePagePattern = '/profile\/(view-profile|edit-profile|password-change|my-books|wishlist|requested-books|earning)/';

$adminPagesPattern = '/admin\/(book-details|book-offer-details|book-offers|book-request-details|book-requests|books|dashboard|index|nav|notification|rent|signin|signup|user-details|users)/i';

$tab = "";

if ($router == '/' || $router == '/landing' || $router == '/landing/' || $router == '/index' || $router == '/index/' || $router == '/header' || $router == '/header/' || preg_match('/\?i=1/', $router)) {
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
} elseif ($router == '/admin' || $router == '/admin/' || preg_match($adminPagesPattern, $router)) {
    // print_r($router);
    $arr = explode('/', $router);
    // print_r($arr);

    switch ($arr[2]) {
        case "book-details":
            include 'admin/book-details.php';
            break;
        case "book-offer-details":
            include 'admin/book-offer-details.php';
            break;
        case "book-offers":
            include 'admin/book-offers.php';
            break;
        case "book-request-details":
            include 'admin/book-request-details.php';
            break;
        case "book-requests":
            include 'admin/book-requests.php';
            break;
        case "books":
            include 'admin/books.php';
            break;
        case "dashboard":
            include 'admin/dashboard.php';
            break;
        case "nav":
            include 'admin/nav.php';
            break;
        case "notification":
            include 'admin/notification.php';
            break;
        case "rent":
            include 'admin/rent.php';
            break;
        case "signin":
            include 'admin/signin.php';
            break;
        case "signup":
            include 'admin/signup.php';
            break;
        case "user-details":
            include 'admin/user-details.php';
            break;
        case "users":
            include 'admin/users.php';
            break;
        default:
            include 'admin/index.php';
            break;
    }
} else {
    include '404.php';
}