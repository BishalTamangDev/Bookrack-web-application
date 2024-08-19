<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

$userId = $_SESSION['bookrack-user-id'];

$url = "my-books";

require_once __DIR__ . '/functions/genre-array.php';
require_once __DIR__ . '/functions/district-array.php';
require_once __DIR__ . '/classes/user.php';

$profileUser = new User();
$userExists = $profileUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/signout");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> My Books </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/book.css">
    <link rel="stylesheet" href="/bookrack/css/my-books.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <main class="d-flex flex-column gap-lg-3 pb-5 container main">
        <!-- heading -->
        <h2 class="m-0 fs-3 fw-semibold mb-3"> My Books </h2>

        <a href="/bookrack/add-book" class="btn btn-outline-brand fit-content mb-3"> <i class="fa fa-add"></i> Add New Book </a>

        <!-- my book filter -->
        <div class="d-flex flex-row flex-wrap gap-2 mb-3 book-status-container">
            <div class="book-status active-book-status" id="my-book-status-all">
                <p> All Books </p>
            </div>

            <div class="book-status inactive-book-status" id="my-book-status-active">
                <p> Active Books </p>
            </div>

            <div class="book-status inactive-book-status" id="my-book-status-inactive">
                <p> Inactive Books </p>
            </div>

            <div class="book-status inactive-book-status" id="my-book-status-sold-out">
                <p> Sold Out </p>
            </div>
        </div>

        <!-- skeleton container -->
        <div class="skeletion-book-main-container" id="skeletion-book-main-container">
            <!-- skeleton 1 -->
            <div class="skeleton-book-container">
                <div class="image"> </div>
                <div class="text">
                    <p class="skeletion-text"> </p>
                    <p class="skeletion-text"> </p>
                    <p class="skeletion-text"> </p>
                </div>
            </div>

            <!-- skeleton 2 -->
            <div class="skeleton-book-container">
                <div class="image"> </div>
                <div class="text">
                    <p class="skeletion-text"> </p>
                    <p class="skeletion-text"> </p>
                    <p class="skeletion-text"> </p>
                </div>
            </div>
        </div>

            <!-- my books container-->
            <div class="d-flex flex-row flex-wrap gap-3 my-book-container" id="my-book-container">

            </div>
    </main>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- script -->
    <script>
        $(document).ready(function () {
            $myBookStatus = "all";
            $('.book-container').show();

            // fetch my books
            function fetchMyBooks() {
                $.ajax({
                    url: '/bookrack/sections/fetch-my-books.php',
                    type: 'POST',
                    data: {userId : '<?=$userId?>'},
                    success : function(data) {
                        $('#my-book-container').html(data);
                        $('#skeletion-book-main-container').hide();
                    }
                });
            }

            fetchMyBooks();

            // my books - all
            $('#my-book-status-all').click(function () {
                $myBookStatus = "all";
                toggleMyBooks();
            });

            // my books - active
            $('#my-book-status-active').click(function () {
                $myBookStatus = "active";
                toggleMyBooks();
            });

            // my books - inactive
            $('#my-book-status-inactive').click(function () {
                $myBookStatus = "inactive";
                toggleMyBooks();
            });

            // my books - sold out
            $('#my-book-status-sold-out').click(function () {
                $myBookStatus = "sold-out";
                toggleMyBooks();
            });

            function toggleMyBooks() {
                $('.my-book').show();
                switch ($myBookStatus) {
                    case 'active':
                        $('.my-book-inactive').hide();
                        $('.my-book-sold-out').hide();
                        break;
                    case 'inactive':
                        $('.my-book-active').hide();
                        $('.my-book-sold-out').hide();
                        break;
                    case 'sold-out':
                        $('.my-book-active').hide();
                        $('.my-book-inactive').hide();
                        break;
                };
            };

            toggleMyBooks();
        });
    </script>
</body>

</html>