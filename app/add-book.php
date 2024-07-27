<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

// Validate CSRF token
if ($_POST['csrf_token_add_book'] !== $_SESSION['csrf_token']) {
    echo 'Invalid CSRF token.';
    exit;
}

$status = false;
$message = "";

require_once __DIR__ . '/../classes/book.php';

$userId = $_SESSION['bookrack-user-id'];

$book = new Book();

// genre
$plainGenreArray = $_POST['book-genre-label'];
$genreArray = explode(',', $plainGenreArray);

// author
$authorArray = $_POST['book-author'];

// photo
$photo = $_FILES['book-photo'];

// set values
$book->setOwnerId($userId);
$book->title = strtolower($_POST['book-title']);
$book->description = strtolower($_POST['book-description']);
$book->language = $_POST['book-language'];
$book->genre = $genreArray;
$book->author = $authorArray;
$book->isbn = $_POST['book-isbn'];
$book->purpose = "buy/sell";
$book->publisher = strtolower($_POST['book-publisher']);
$book->edition = $_POST['book-edition'];
$book->setActualPrice($_POST['book-actual-price']);

if (isset($_POST['book-offer-price'])) {
    $defaultValuation = 0.40;
    if ($_POST['book-offer-price'] == '' || $_POST['book-offer-price'] == 0) {
        $_POST['book-offer-price'] = $_POST['book-actual-price'] * $defaultValuation;
    }
    $book->setOfferPrice($_POST['book-offer-price']);
} else {
    $_POST['book-offer-price'] = $_POST['book-actual-price'] * $defaultValuation;
}

// photo
$fileTmpPath = $_FILES['book-photo']['tmp_name'];
$fileName = $_FILES['book-photo']['name'];
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
$filePath = "books/$newFileName";

// set filenames
$book->setPhoto($newFileName);

// upload photos first
try {
    //  page photo
    $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);
    $status = true;
    if ($status) {
        $immediateKey = $book->register();
        $status = $immediateKey != 0 ? true : false;

        if($status) {
            // add new genre to the genre list
            require_once __DIR__ . '/../classes/genre.php';
            $genreObj = new Genre();
            $genreObj->genreArray = $genreArray;
            $genreObj->newBook();
            
            // notification for admin
            require_once __DIR__ . '/../classes/notification.php';
            $notificationObj = new Notification();
            $notificationObj->newBook($immediateKey, $userId);
            
            $message = "true";
        }
    }
} catch (Exception $e) {
    $message = "Error in book photo.";
}

echo $message;

exit;