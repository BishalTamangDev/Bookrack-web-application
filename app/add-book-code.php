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

    $coverPhoto = $_FILES['book-cover-photo'];
    $pricePhoto = $_FILES['book-price-photo'];
    $isbnPhoto = $_FILES['book-isbn-photo'];

    // set values
    $book->setOwnerId($userId);
    $book->setTitle(strtolower($_POST['book-title']));
    $book->setDescription(strtolower($_POST['book-description']));
    $book->setLanguage($_POST['book-language']);
    $book->setGenre($genreArray);
    $book->setAuthor($authorArray);
    $book->setIsbn($_POST['book-isbn']);
    $book->setPurpose($_POST['book-purpose']);
    $book->setPublisher(strtolower($_POST['book-publisher']));
    $book->setPublication(strtolower($_POST['book-publication']));
    $book->setEdition($_POST['book-edition']);
    $book->setActualPrice($_POST['book-actual-price']);
    $book->setOfferPrice($_POST['book-offer-price']);

    // cover photo
    $fileTmpPathCover = $_FILES['book-cover-photo']['tmp_name'];
    $fileNameCover = $_FILES['book-cover-photo']['name'];
    $fileExtensionCover = pathinfo($fileNameCover, PATHINFO_EXTENSION);
    $newFileNameCover = md5(time() . $fileNameCover) . '.' . $fileExtensionCover;
    $filePathCover = 'books/' . $newFileNameCover;

    // price photo
    $fileTmpPathPrice = $_FILES['book-price-photo']['tmp_name'];
    $fileNamePrice = $_FILES['book-price-photo']['name'];
    $fileExtensionPrice = pathinfo($fileNamePrice, PATHINFO_EXTENSION);
    $newFileNamePrice = md5(time() . $fileNamePrice) . '.' . $fileExtensionPrice;
    $filePathPrice = 'books/' . $newFileNamePrice;

    // isbn photo
    $fileTmpPathIsbn = $_FILES['book-isbn-photo']['tmp_name'];
    $fileNameIsbn = $_FILES['book-isbn-photo']['name'];
    $fileExtensionIsbn = pathinfo($fileNameIsbn, PATHINFO_EXTENSION);
    $newFileNameIsbn = md5(time() . $fileNameIsbn) . '.' . $fileExtensionIsbn;
    $filePathIsbn = 'books/' . $newFileNameIsbn;

    // set filenames
    $book->setCoverPhoto($newFileNameCover);
    $book->setPricePhoto($newFileNamePrice);
    $book->setIsbnPhoto($newFileNameIsbn);

    // upload photos first
    try {
        // cover page photo
        $bucket->upload(fopen($fileTmpPathCover, 'r'), ['name' => $filePathCover]);
        try {
            $bucket->upload(fopen($fileTmpPathPrice, 'r'), ['name' => $filePathPrice]);
            try {
                $bucket->upload(fopen($fileTmpPathIsbn, 'r'), ['name' => $filePathIsbn]);
                $status = 1;
            } catch (Exception $e) {
                $_SESSION['status-message'] = "Error in isbn page photo.";
            }
        } catch (Exception $e) {
            $_SESSION['status-message'] = "Error in price page photo.";
        }
    } catch (Exception $e) {
        $_SESSION['status-message'] = "Error in cover page photo.";
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
    $_SESSION['status-message'] = $status ? "Book added successfully" : "Book counldn't be added";

    if ($status)
        header("Location: /bookrack/book-details/$immediateKey");
    else
        header("Location: /bookrack/add-book");
}

exit();