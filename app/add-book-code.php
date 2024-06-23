<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/home");
}

require_once __DIR__ . '/book-class.php';

if (isset($_POST['add-book-btn'])) {
    $status = true;

    // getting form details
    $title = $_POST['book-title'];
    $description = $_POST['book-description'];

    // genre
    $plainGenreArray = $_POST['book-genre-label'];
    $genreArray = explode(',', $plainGenreArray);

    // print_r($genreArray);
    // Encode genre array to JSON
    $jsonGenreArray = json_encode($genreArray);

    // arthor
    $authorArray = $_POST['book-author'];
    $jsonAuthorArray = json_encode($authorArray);

    $isbn = $_POST['book-isbn'];
    $purpose = $_POST['book-purpose'];
    $publisher = $_POST['book-publisher'];
    $publication = $_POST['book-publication'];
    $language = $_POST['book-language'];
    $edition = $_POST['book-edition'];
    $actualPrice = $_POST['book-actual-price'];
    $offerPrice = $_POST['book-offer-price'];
    $coverPhoto = $_FILES['book-cover-photo'];
    $pricePhoto = $_FILES['book-price-photo'];
    $isbnPhoto = $_FILES['book-isbn-photo'];

    // print form data
    // echo "<br/><br/> Title       : ".$title;
    // echo "<br/><br/> Description : ".$description;
    // echo "<br/><br/> Genre       : ".$jsonGenreArray;
    // echo "<br/><br/> Author      : ".$jsonAuthorArray;
    // echo "<br/><br/> ISBN        : ".$isbn;
    // echo "<br/><br/> Purpose     : ".$purpose;
    // echo "<br/><br/> Publisher   : ".$publisher;
    // echo "<br/><br/> Publication : ".$publication;
    // echo "<br/><br/> Language    : ".$language;
    // echo "<br/><br/> Edition     : ".$edition;
    // echo "<br/><br/> ActualPrice : ".$actualPrice;
    // echo "<br/><br/> OfferPrice  : ".$offerPrice;
    // echo "<br/><br/> Cover page  : ".$coverPhoto['name'];
    // echo "<br/><br/> Price page  : ".$pricePhoto['name'];
    // echo "<br/><br/> ISBN Page   : ".$isbnPhoto['name'];

    if ($status) {
        $book = new Book();

        // set values
        $book->setOwnerId($_SESSION['bookrack-user-id']);
        $book->setTitle($title);
        $book->setDescription($description);
        $book->setLanguage($language);
        $book->setGenre($genreArray);
        $book->setAuthor($authorArray);
        $book->setIsbn($isbn);
        $book->setPurpose($purpose);
        $book->setPublisher($publisher);
        $book->setPublication($publication);
        $book->setEdition($edition);
        $book->setActualPrice($actualPrice);
        $book->setOfferPrice($offerPrice);

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

        // print details
        // print form data
        // echo "<br/><br/> Owner ID         : " . $book->getOwnerId();
        // echo "<br/><br/> Title            : " . $book->getTitle();
        // echo "<br/><br/> Description      : " . $book->getDescription();
        // echo "<br/><br/> Language         : " . $book->getLanguage();

        // echo "<br/><br/> Genre            : ";
        // print_r($book->getGenre());

        // echo "<br/><br/> Author           : ";
        // print_r($book->getAuthor());

        // echo "<br/><br/> ISBN             : " . $book->getIsbn();
        // echo "<br/><br/> Purpose          : " . $book->getPurpose();
        // echo "<br/><br/> Publisher        : " . $book->getPublisher();
        // echo "<br/><br/> Publication      : " . $book->getPublication();
        // echo "<br/><br/> Edition          : " . $book->getEdition();
        // echo "<br/><br/> ActualPrice      : " . $book->getActualPrice();
        // echo "<br/><br/> OfferPrice       : " . $book->getOfferPrice();
        // echo "<br/><br/> Cover page photo : " . $book->getCoverPhoto();
        // echo "<br/><br/> Price page photo : " . $book->getPricePhoto();
        // echo "<br/><br/> ISBN Page  photo : " . $book->getIsbnPhoto();

        // upload photos first
        try {
            // cover page photo
            $bucket->upload(fopen($fileTmpPathCover, 'r'), ['name' => $filePathCover]);
            try {
                $bucket->upload(fopen($fileTmpPathPrice, 'r'), ['name' => $filePathPrice]);
                try {
                    $bucket->upload(fopen($fileTmpPathIsbn, 'r'), ['name' => $filePathIsbn]);
                    $staus = true;
                } catch (Exception $e) {
                    $_SESSION['status-message'] = "Error in isbn page photo.";
                }
            } catch (Exception $e) {
                $_SESSION['status-message'] = "Error in price page photo.";
            }
            $staus = true;
        } catch (Exception $e) {
            $_SESSION['status-message'] = "Error in cover page photo.";
        }

        if ($status) {
            $immediateKey = $book->register();
            $status = $immediateKey != 0 ? true : false;
        }
    }

    $_SESSION['status'] = $status ? true : false;
    $_SESSION['status-message'] = $status ? "Book added successfully" : "Book counldn't be added";

    if ($status) {
        header("Location: /bookrack/book-details/$immediateKey");
    } else {
        header("Location: /bookrack/add-book");
    }
}

exit();