<?php
require_once __DIR__ . '/functions/genre-array.php';
require_once __DIR__ . '/classes/wishlist.php';

$url = "profile";
$wishlistObj = new Wishlist();

$userExists = $profileUser->fetch($profileId);

require_once __DIR__ . '/classes/book.php';
$bookObj = new Book();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Wishlist </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/book.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <main class="d-flex flex-column gap-lg-3 pb-5 container main">
        <!-- heading -->
        <h2 class="m-0 fs-3 fw-semibold mb-4"> My Wishlist </h2>

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
        <div class="d-flex flex-column gap-4 my-book-content wishlist-content" id="wishlist-container"> </div>

        <div class="empty-div" id="empty-wishlist-div">
            <img src="/bookrack/assets/icons/empty.svg" alt="" loading="lazy">
            <p class="empty-message"> Your wishlist is empty! </p>
        </div>

        <div class="d-flex flex-row flex-wrap gap-3 all-book-container" id="wishlist-container">

        </div>
    </main>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- script -->
    <script>
        $(document).ready(function () {
            $('#empty-wishlist-div').hide();

            function fetchWishlist() {

                // load wishlist
                $.ajax({
                    url: '/bookrack/sections/fetch-wishlist.php',
                    type: "POST",
                    data: { userId: '<?= $profileId ?>' },
                    success: function (data) {
                        $('#skeletion-book-main-container').remove();
                        $('#wishlist-container').replaceWith(data);
                    }
                });
            }

            fetchWishlist();

            // wishlist toggle
            $(document).on('click', '.wishlist-toggle-icon', function () {
                let wishlist_book_id = $(this).data("book-id");
                let wishlist_task = $(this).data("task");

                // $(this).closest("a").html(nextWishlistState);
                const targetA = $(this).closest("a");

                const targetContainer = $(this).closest(".book-container");

                $.ajax({
                    url: '/bookrack/app/toggle-wishlist-home.php',
                    type: "POST",
                    data: { bookId: wishlist_book_id, page: 1 },
                    beforeSend: function () {
                        let nextWishlistState = "";
                        if (wishlist_task == "add") {
                            nextWishlistState = "<i class='fa-solid fa-bookmark wishlist-toggle-icon' data-book-id=" + wishlist_book_id + " data-task='remove'></i>";
                        } else {
                            nextWishlistState = "<i class='fa-regular fa-bookmark wishlist-toggle-icon' data-book-id=" + wishlist_book_id + " data-task='add'></i>";
                        }
                        targetA.html(nextWishlistState);
                        targetContainer.remove();
                        checkPageWishlist();
                    },
                    success: function (response) {
                    }
                });
            });

            // show empty wishlist message
            function checkPageWishlist() {
                const bookCount = $('.book-container:visible').length;

                if (bookCount == 0) {
                    $('#empty-wishlist-div').show();
                }
            }
        });
    </script>
</body>

</html>