<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

require_once __DIR__ . '/book-class.php';

if (isset($_POST['edit-book-btn'])) {
    global $database;

    $book = new Book();
    $bookId = $_POST['book-id'];

    $book->fetch($bookId);

    echo "<br/> Old genre : ";
    $oldGenre = $book->genre;
    print_r($oldGenre);

    echo "<br/> New genre : ";
    $newPlainGenre = $_POST['book-genre-label'];
    $newGenre = explode(',', $newPlainGenre);
    print_r($newGenre);

    echo "<br/>";

    $properties = [
        'title' => $_POST['book-title'],
        'description' => $_POST['book-description'],
        'author' => $_POST['book-author'],
        'genre' => $newGenre,
        'isbn' => $_POST['book-isbn'],
        'purpose' => $_POST['book-purpose'],
        'publisher' => $_POST['book-publisher'],
        'publication' => $_POST['book-publication'],
        'language' => $_POST['book-language'],
        'edition' => $_POST['book-edition'],
        'price' => [
            'actual' => $_POST['book-actual-price'],
            'offer' => $_POST['book-offer-price'],
        ],
    ];
    $response = $database->getReference("books/{$bookId}")->update($properties);

    // update genre table
    if ($response) {
        require_once __DIR__ . '/genre-class.php';
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