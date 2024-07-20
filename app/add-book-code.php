<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

require_once __DIR__ . '/book-class.php';

if (isset($_POST['add-book-btn'])) {
    $userId = $_SESSION['bookrack-user-id'];

    $book = new Book();
    $status = 0;

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
        if ($_POST['book-offer-price'] == '' || $_POST['book-offer-price'] == 0) {
            $_POST['book-offer-price'] = $_POST['book-actual-price'] * 0.35;
        }
        $book->setOfferPrice($_POST['book-offer-price']);
    } else {
        $_POST['book-offer-price'] = $_POST['book-actual-price'] * 0.35;
    }

    // photo
    $fileTmpPath = $_FILES['book-photo']['tmp_name'];
    $fileName = $_FILES['book-photo']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $filePath = 'books/' . $newFileName;

    // set filenames
    $book->setPhoto($newFileName);

    // upload photos first
    try {
        //  page photo
        $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);
        $status = 1;
    } catch (Exception $e) {
        $_SESSION['status-message'] = "Error in book photo.";
    }

    if ($status) {
        $immediateKey = $book->register();
        $status = $immediateKey != 0 ? true : false;

        // add new genre to the genre list
        require_once 'genre-class.php';
        $genreObj = new Genre();
        $genreObj->genreArray = $genreArray;
        $genreObj->newBook();
    }

    $_SESSION['status'] = $status;

    if ($status)
        header("Location: /bookrack/book-details/$immediateKey");
    else {
        $_SESSION['status-message'] = "Book couldn't be added";
        header("Location: /bookrack/add-book");
    }
}

exit();