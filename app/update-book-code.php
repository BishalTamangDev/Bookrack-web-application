<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

require_once __DIR__ . '/../classes/book.php';

if (isset($_POST['edit-book-btn'])) {
    global $database;

    $book = new Book();
    $bookId = $_POST['book-id'];

    $book->fetch($bookId);

    $oldGenre = $book->genre;

    $newPlainGenre = $_POST['book-genre-label'];
    $newGenre = explode(',', $newPlainGenre);

    $properties = [
        'title' => $_POST['book-title'],
        'description' => $_POST['book-description'],
        'author' => $_POST['book-author'],
        'genre' => $newGenre,
        'isbn' => $_POST['book-isbn'],
        'purpose' => "buy/sell",
        'publisher' => $_POST['book-publisher'],
        'language' => $_POST['book-language'],
        'edition' => $_POST['book-edition'],
        'price_actual' => $_POST['book-actual-price'],
        'price_offer' => $_POST['book-offer-price'],
    ];
    $response = $database->getReference("books/{$bookId}")->update($properties);

    // update genre table
    if ($response) {
        require_once __DIR__ . '/../classes/genre.php';
        $genreObj = new Genre();

        // remove the old genre 
        $genreObj->genreArray = $oldGenre;
        $genreObj->removeBook();

        // add new genre
        $genreObj->genreArray = $newGenre;
        $genreObj->newBook();
    }

    header("Location: /bookrack/book-details/$bookId");
}

exit();