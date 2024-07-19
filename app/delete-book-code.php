<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

require_once __DIR__ . '/book-class.php';

if (isset($_GET['bookId'])) {
    global $database;
    $bookExists = false;
    $isOwner = false;

    $bookId = $_GET['bookId'];


    // check for the presence of book
    $response = $database->getReference("books/{$bookId}")->getSnapshot()->getValue();
    if ($response) {
        $bookExists = true;
        if ($response['owner_id'] == $_SESSION['bookrack-user-id'])
            $isOwner = true;
    }

    if ($bookExists && $isOwner) {
        $postData = [
            'flag' => 'deleted'
        ];

        $postRef = $database->getReference("books/{$bookId}")->update($postData);
    }

    header("Location: /bookrack/book-details/{$bookId}");

    // check if the user is the owner
} else {
    header("Location: /bookrack/home");
}

exit();