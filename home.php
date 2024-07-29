<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

$userId = $_SESSION['bookrack-user-id'];
$url = "home";

require_once __DIR__ . '/functions/genre-array.php';
require_once __DIR__ . '/classes/user.php';
require_once __DIR__ . '/classes/genre.php';

// get user details
$profileUser = new User();
$userExists = $profileUser->checkUserExistenceById($userId);

if (!$userExists)
    header("Location: /bookrack/app/signout.php");

$profileUser->setUserId($userId);

// book obj 
require_once __DIR__ . '/classes/book.php';
$bookObj = new Book();

// search
$searchState = isset($_GET['search-content']) && $_GET['search-content'] != '' ? 1 : 0;

$searchContent = $searchState ? strtolower($_GET['search-content']) : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title>
        <?= $searchState ? "Search results : $searchContent" : "Home"; ?>
    </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/book.css">
    <link rel="stylesheet" href="/bookrack/css/home.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <!-- main -->
    <main class="d-flex flex-row gap-lg-3 pb-5 container main">
        <!-- aside :: filter & advertisement  -->
        <aside class="aside gap-3" id="aside">
            <!-- filter -->
            <section class="filter-section">
                <!-- heading -->
                <section class="d-flex flex-row justify-content-between align-items-center row-heading">
                    <p class="m-0 fw-bold text-secondary"> Filters </p>

                    <div class="d-flex flex-row align-items-center gap-2 icon-container">
                        <i class="fa fa-filter text-secondary filter-icon"></i>
                        <i class="fa fa-multiply fs-3 text-secondary pointer" id="filter-hide-trigger"></i>
                    </div>
                </section>
                <hr>

                <!-- filter parameters -->
                <section class="mt-3 filter-parameter-section">
                    <form method="GET" class="d-flex flex-column gap-3" id="filter-form">
                        <!-- price -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="min-price" class="form-label"> Price </label>
                                <abbr title="Reset">
                                    <a id="form-reset">
                                        <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                                    </a>
                                </abbr>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <!-- min price -->
                                <input type="number" name="min-price" class="form-control" id="min-price" min="0"
                                    aria-describedby="min price" placeholder="Min">

                                <p class="m-0 fw-bold"> - </p>

                                <!-- max price -->
                                <input type="number" name="max-price" class="form-control" id="max-price" min="0"
                                    aria-describedby="max price" placeholder="Max">
                            </div>
                        </div>

                        <!-- book purpose -->
                        <div class="d-none filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="category" class="form-label"> Purpose </label>
                            </div>

                            <select class="form-select form-select-md" name="purpose" id="purpose"
                                aria-label="Small select example">
                                <option value="all" selected> All </option>
                                <option value="renting"> Renting </option>
                                <option value="buy/sell"> Buy/Sell </option>
                                <option value="giveaway"> Giveaway </option>
                            </select>
                        </div>

                        <!-- book genre -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="genre" class="form-label"> Genre </label>
                            </div>

                            <select class="form-select form-select-md" name="genre" id="genre"
                                aria-label="Small select example">
                                <option value="all" selected> All genre </option>
                                <?php
                                foreach ($genreArray as $genre) {
                                    ?>
                                    <option value="<?= $genre ?>"> <?= $genre ?> </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <!-- filter button -->
                        <button type="submit" class="filter-btn" id="filter-btn"> Filter </button>
                    </form>
                </section>
            </section>

            <!-- advertisement -->
            <section class="advertisement-section">
                <img src="/bookrack/assets/images/ad-1.jpg" alt="advertisement" loading="lazy">
            </section>
        </aside>

        <!-- article -->
        <article class="article bg-md-success bg-sm-danger">
            <!-- top genre  -->
            <section class="d-flex flex-row gap-4 flex-wrap mt-2 align-items-center top-genre-section">
                <p class="m-0 fs-5"> Top Genre </p>
                <div class="d-flex flex-row flex-wrap gap-2 genre-container" id="trending-genre-container">
                    <div class="genre">
                        <p class="m-0 text-secondary"> Loading... </p>
                    </div>
                </div>
            </section>

            <div class="d-flex justify-content-between align-items-center mt-4 heading">
                <p class="m-0 fw-bold heading text-secondary fs-4">
                    <?= $searchState ? "Search results" : "All Books" ?>
                </p>
                <i class="fa fa-filter pointer" id="filter-show-trigger-2"></i>
            </div>

            <?php
            if ($searchState) {
                ?>
                <div class="d-flex flex-column gap-2 mt-3">
                    <a href="/bookrack/home" class="btn btn-danger" style="width:fit-content;"> Clear search </a>
                </div>
                <?php
            }
            ?>

            <!-- skeleton container -->
            <div class="skeletion-book-main-container mt-4" id="skeletion-book-main-container">
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

                <!-- skeleton 3 -->
                <div class="skeleton-book-container">
                    <div class="image"> </div>
                    <div class="text">
                        <p class="skeletion-text"> </p>
                        <p class="skeletion-text"> </p>
                        <p class="skeletion-text"> </p>
                    </div>
                </div>

                <!-- skeleton 4 -->
                <div class="skeleton-book-container">
                    <div class="image"> </div>
                    <div class="text">
                        <p class="skeletion-text"> </p>
                        <p class="skeletion-text"> </p>
                        <p class="skeletion-text"> </p>
                    </div>
                </div>
            </div>

            <!-- all books section -->
            <section class="d-flex flex-column gap-4 mt-4 all-books-section">
                <div class="d-flex flex-row flex-wrap gap-3 all-book-container" id="all-book-container">
                </div>

                <!-- pagination -->
                <button class="invisible load-more-btn" id="load-more-btn" data-offset="0"> Load More </button>
            </section>

            <!-- empty context -->
            <section class="d-none flex-column mt-3 gap-3 align-items-center empty-context-container"
                id="empty-context-container">
                <img src="assets/icons/empty.svg" alt="empty icon">
                <p class="m-0 text-danger"> Empty! </p>
            </section>
        </article>
    </main>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <script>
        // filter
        $(document).ready(function () {
            let page_count = 0;
            let offset_data = 0;
            let search_state = <?= $searchState ?>;
            let search_content = (search_state == 1) ? "<?= $searchContent ?>" : "";
            let book_min_price = 0;
            let book_max_price = Infinity;
            let book_genre = "all";

            // load all books
            function loadMoreBooks(search_content, book_genre, book_min_price, book_max_price) {
                page_count++;
                offset_data = $('#load-more-btn').data("offset");

                $.ajax({
                    url: '/bookrack/sections/all-books.php',
                    method: "POST",
                    data: {
                        page: page_count,
                        offset: offset_data,
                        searchContent: search_content,
                        genre: book_genre,
                        minPrice: book_min_price,
                        maxPrice: book_max_price,
                    },
                    beforeSend: function () {
                        $('#load-more-btn').html("Loading...").prop('disabled', true);
                    },
                    success: function (data) {
                        $('#skeletion-book-main-container').remove();
                        $('#load-more-btn').remove();
                        $('#load-more-btn-container').remove();
                        $('#all-book-container').append(data);
                        $('#load-more-btn').html("Load More").prop('disabled', false);
                    },
                    error: function () {
                        console.log("Error in fetching the books..");
                        $('#load-more-btn').html("Loading...").prop('disabled', false);
                    },
                });
            }

            // load more
            $(document).on('click', '#load-more-btn', function () {
                loadMoreBooks(search_content, book_genre, book_min_price, book_max_price);
            });

            loadMoreBooks(search_content, book_genre, book_min_price, book_max_price);

            // show more button clicked
            $(document).on('click', '.show-more-btn', function () {
                let book_id = $(this).data("book-id");
                let is_owner = $(this).data("is-owner");

                let link = "/bookrack/book-details/" + book_id;

                if (is_owner === false) {
                    $.ajax({
                        url: '/bookrack/app/click.php',
                        type: "POST",
                        data: { bookId: book_id },
                        success: function (response) {
                        }
                    });
                }

                window.location.href = link;
            });

            // load trending genre
            $.get('/bookrack/sections/trending-genre.php', function (data) {
                $('#trending-genre-container').html(data);
            });

            // filter form submission
            $('#filter-form').submit(function (e) {
                e.preventDefault();
                book_min_price = $('#min-price').val() != '' ? parseFloat($('#min-price').val()) : 0;
                book_max_price = $('#max-price').val() != '' && $('#max-price').val() != 0 ? parseFloat($('#max-price').val()) : Infinity;
                book_genre = $('#genre').val();
                filterContent();
            });

            // reset filter form
            $('#form-reset').click(function () {
                book_min_price = 0;
                $('#min-price').val(book_min_price);

                book_max_price = Infinity;
                $('#max-price').val(0);

                book_genre = "all";
                $('#genre').val(book_genre);

                filterContent();
            });

            // filter contents
            function filterContent() {
                $('.book-element').hide();
                console.clear();
                console.log("Filter");
                // genre
                if (book_genre == "all") {
                    $('.book-element').show();
                } else {
                    genre_to_show = book_genre.replace(/ /g, "-").replace(/'/g, "").replace(/\(/g, '').replace('\)', '') + '-element';
                    let finalClass = '.' + genre_to_show;
                    $(finalClass).show();
                }

                // //  price filtering
                $('.book-element:visible').each(function () {
                    var price = parseFloat($(this).data('price'));
                    if (price >= book_min_price && price <= book_max_price) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            var filterTriggerState = false;

            // filter
            $('#filter-show-trigger-2').on('click', function () {
                $('#aside').css({
                    'display': 'block'
                });
                filterTriggerState = !filterTriggerState;
            });

            $('#filter-hide-trigger').on('click', function () {
                $('#aside').css({
                    'display': 'none'
                });
                filterTriggerState = !filterTriggerState;
            });

            // empty context
            if ($('.book-container').length == 0) {
                $('#empty-context-container').hide();
            } else {
                $('#empty-context-container').hide();
            }

            // wishlist toggle
            $(document).on('click', '.wishlist-toggle-icon', function () {
                let wishlist_book_id = $(this).data("book-id");
                let wishlist_task = $(this).data("task");

                // $(this).closest("a").html(nextWishlistState);
                const targetA = $(this).closest("a");

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
                    },
                    success: function (response) {
                    }
                });
            });
        });
    </script>
</body>

</html>