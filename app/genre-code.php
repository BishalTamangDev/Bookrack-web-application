<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

if (isset($_GET['task']) && isset($_GET['plainGenreArray'])) {
    require_once 'genre-class.php';
    $genreObj = new Genre();

    $task = $_GET['task'];
    $plainGenreArray = $_GET['plainGenreArray'];
    $genreArray = explode(',', $plainGenreArray);

    $genreObj->genreArray = $genreArray;

    if ($task == "add") {
        $genreObj->newBook();
        exit();
    } elseif ($task == "remove") {
        $genreObj->removeBook();
    }
}