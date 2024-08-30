<?php
$url = "dashboard";
$page = "dashboard";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");

require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../classes/book.php';

$userObj = new User();
$bookObj = new Book();

$userIdList = $userObj->fetchAllUserId();
$bookIdList = $bookObj->fetchAllBookId();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Dashboard </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/dashboard.css">
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>

    <main class="main">
        <!-- card & new users -->
        <section
            class="d-flex section flex-column flex-lg-row mt-5 pt-3 gap-3 section justify-content-between count-card-new-users">
            <div class="count-card">
                <!-- cards -->
                <div class="card-container">
                    <!-- number of users -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Users </p>
                        <p class="card-v1-detail" id="user-count"> - </p>
                    </div>

                    <!-- number of books -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Books </p>
                        <p class="card-v1-detail" id="book-count"> - </p>
                    </div>

                    <!-- number of sold out books -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Sold out Books </p>
                        <p class="card-v1-detail" id="sold-out-book-count"> - </p>
                    </div>

                    <!-- pending -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Pending Orders </p>
                        <p class="card-v1-detail" id="pending-orders"> - </p>
                    </div>

                    <!-- completed -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Completed Orders </p>
                        <p class="card-v1-detail" id="completed-orders"> - </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- search -->
        <section class="d-flex section flex-column gap-3">
            <!-- heading -->
            <p class="f-reset fs-5 fw-bold"> Searches </p>

            <div class="d-flex flex-row flex-wrap gap-2 search-container" id="search-container">
                <div class="d-flex flex-row border rounded p-2 px-3 align-items-center">
                    <p class="m-0"> Loading... </p>
                </div>
            </div>
        </section>

        <!-- recently added books -->
        <p class="f-reset fs-5 fw-bold mt-5 mb-4"> Recently added books </p>

        <div class="d-flex flex-row flex-wrap gap-3 recently-arrived-books-div" id="recently-arrived-books-div">
            <div class="recently-arrived-book recently-arrived-book-skeleton">
                <div class="image-div">
                </div>

                <div class="detail">
                    <div class="title">
                    </div>

                    <div class="genre">
                    </div>
                </div>
            </div>

            <div class="recently-arrived-book recently-arrived-book-skeleton">
                <div class="image-div">
                </div>

                <div class="detail">
                    <div class="title">
                    </div>

                    <div class="genre">
                    </div>
                </div>
            </div>

            <div class="recently-arrived-book recently-arrived-book-skeleton">
                <div class="image-div">
                </div>

                <div class="detail">
                    <div class="title">
                    </div>

                    <div class="genre">
                    </div>
                </div>
            </div>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <!-- js -->
    <script>
        $(document).ready(function () {
            // counting function
            function countTotalBooks() {
                // total users
                $.ajax({
                    url: "/bookrack/admin/app/count-total-users.php",
                    success: function (data) {
                        $('#user-count').html(data);
                    }
                });

                // total books
                $.ajax({
                    url: "/bookrack/admin/app/count-total-books.php",
                    success: function (data) {
                        $('#book-count').html(data);
                    }
                });

                // total sold out books
                $.ajax({
                    url: "/bookrack/admin/app/count-sold-out-books.php",
                    success: function (data) {
                        $('#sold-out-book-count').html(data);
                    }
                });

                // pending cart
                $.ajax({
                    url: "/bookrack/admin/app/count-pending-carts.php",
                    success: function (data) {
                        $('#pending-orders').html(data);
                    }
                });

                // completed cart
                $.ajax({
                    url: "/bookrack/admin/app/count-completed-carts.php",
                    success: function (data) {
                        $('#completed-orders').html(data);
                    }
                });
            }

            function fetchSearches() {
                $.ajax({
                    url: "/bookrack/admin/sections/fetch-search-histories.php",
                    success: function (data) {
                        $('#search-container').html(data);
                    }
                });
            }

            function fetchRecentlyAddedBooks() {
                $.ajax({
                    url: "/bookrack/admin/sections/fetch-recently-arrived-books.php",
                    success: function (data) {
                        $('#recently-arrived-books-div').html(data);
                    }
                });
            }

            countTotalBooks();
            fetchSearches();
            fetchRecentlyAddedBooks();
        });
    </script>
</body>

</html>