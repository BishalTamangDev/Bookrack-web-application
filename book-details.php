<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

if (!isset($bookId) || $bookId == "")
    header("Location: /bookrack/home");

$url = "book-details";
$userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/functions/genre-array.php';
require_once __DIR__ . '/classes/user.php';

$profileUser = new User();
$userExists = $profileUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/signin");

require_once __DIR__ . '/classes/book.php';
$selectedBook = new Book();
$bookExists = $selectedBook->fetch($bookId);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> <?= $bookExists ? ucWords($selectedBook->title) : "Book details" ?> </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/book-details.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <!-- main -->
    <?php
    if ($bookExists) {
        ?>
        <main class="d-flex flex-column gap-3 pb-5 container main">
            <!-- title, rating, count -->
            <section class="d-flex flex-column title-rating-count-container">
                <!-- book title -->
                <p class="f-reset fw-bold fs-3"> <?= ucWords($selectedBook->title) ?> </p>

                <!-- rating & count-->
                <div class="d-flex flex-row gap-2 align-items-center rating-count-container">
                    <!-- rating -->
                    <div class="d-flex flex-row align-items-center rating-container">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/half-rating.png" alt="" loading="lazy">
                    </div>

                    <!-- count -->
                    <p class="f-reset count-container"> (<?= "-" ?>) </p>
                </div>
            </section>

            <section class="d-flex flex-column flex-lg-row gap-4 book-detail-container">
                <!-- images -->
                <div class="d-flex flex-column flex-md-row flex-lg-column gap-2 book-image-container">
                    <!-- top image -->
                    <div class="d-flex flex-row top">
                        <div class="book-image">
                            <?php $selectedBook->setPhotoUrl() ?>
                            <img src="<?= $selectedBook->photoUrl ?>" alt="" loading="lazy">
                        </div>
                    </div>
                </div>

                <!-- details -->
                <div class="d-flex flex-column gap-4 mt-2 mt-lg-0 all-detail-container">
                    <!-- description & availability status -->
                    <div class="d-flex flex-column justify-content-between gap-2 description-availability">
                        <!-- description heading & availability -->
                        <div class="d-flex flex-row justify-content-between description-deading-availability">
                            <p class="p f-reset fw-bold fs-4 text-secondary"> Description </p>

                            <div class="d-flex flex-row align-items-center bg-success px-3 availability-div">
                                <?php
                                $purpose = $selectedBook->purpose;
                                if ($purpose == "renting") {
                                    ?>
                                    <p class="f-reset text-light"> Available for Rent </p>
                                    <?php
                                } elseif ($purpose == "buy/sell") {
                                    ?>
                                    <p class="f-reset text-light"> Available for Buy/Sell </p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                        <!-- description container -->
                        <div class="description-container">
                            <!-- description -->
                            <p class="f-reset"> <?= ucfirst($selectedBook->description) ?> </p>
                        </div>
                    </div>

                    <!-- genre container -->
                    <div class="d-flex flex-column gap-2 genre-container">
                        <!-- genre heading -->
                        <p class="p f-reset fw-bold fs-4 text-secondary"> Genre </p>

                        <!-- genre list -->
                        <div class="d-flex flex-row gap-2 align-items-cente flex-wrap genre-list">
                            <?php
                            $genreArray = $selectedBook->genre;

                            foreach ($genreArray as $genre) {
                                ?>
                                <div class="bg-dark genre">
                                    <p class="m-0 text-white"> <?= $genre ?> </p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- author container -->
                    <div class="d-flex flex-column gap-2 author-container">
                        <!-- authors heading -->
                        <p class="p f-reset fw-bold fs-4 text-secondary"> Author[s] </p>

                        <!-- author list -->
                        <div class="d-flex flex-row gap-2 align-items-center flex-wrap author-list">
                            <?php
                            foreach ($selectedBook->author as $author) {
                                ?>
                                <div class="author">
                                    <p class="m-0"> <?= ucWords($author) ?> </p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- remaining details -->
                    <div class="d-flex flex-column flex-md-row flex-wrap misc-container">
                        <!-- edition -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Edition </p>
                            </div>
                            <div class="data">
                                <p class="m-0"><?= ucfirst($selectedBook->edition) ?><sup>

                                        <?php
                                        if (!is_string($selectedBook->edition)) {
                                            $remainder = $selectedBook->edition % 10;
                                            switch ($remainder) {
                                                case 1;
                                                    echo "st";
                                                    break;
                                                case 2;
                                                    echo "nd";
                                                    break;
                                                case 3:
                                                    echo "rd";
                                                    break;
                                                default:
                                                    echo "th";
                                            }
                                        }
                                        ?></sup>
                                </p>
                            </div>
                        </div>

                        <!-- publisher -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Publisher </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= ucWords($selectedBook->publisher) ?> </p>
                            </div>
                        </div>

                        <!-- price -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Price </p>
                            </div>
                            <div class="data">
                                <p class="m-0 text-success fw-bold">
                                    <?php
                                    if ($selectedBook->purpose == "renting") {
                                        $rent = 0.20 * $selectedBook->price['actual'];
                                        echo "NPR." . number_format($rent, 2) . "/week";
                                    } else {
                                        $price = $selectedBook->price['offer'];
                                        echo "NPR." . number_format($price, 2);
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <!-- ISBN -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> ISBN </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= $selectedBook->isbn ?> </p>
                            </div>
                        </div>

                        <!-- language -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Language </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= ucfirst($selectedBook->language) ?> </p>
                            </div>
                        </div>
                    </div>

                    <!-- action -->
                    <?php
                    if ($selectedBook->getOwnerId() != $userId) {
                        ?>
                        <div class="d-flex flex-wrap flex-md-row operation-container">
                            <!-- wishlist -->
                            <div id="wishlist-btn-container">
                                <!-- <a class="btn" id="wishlist-btn" data-task="add || remove">
                                    <i class="fa-regular fa-bookmark || fa-solid fa-bookmark"></i> Add to wishlist || Remove from wishlist
                                </a> -->
                            </div>


                            <!-- cart section -->
                            <?php
                            require_once __DIR__ . '/classes/cart.php';
                            $cart = new Cart();

                            if ($selectedBook->flag == 'on-hold') {
                                ?>
                                <a class="btn btn-danger"><i class="fa fa-shopping-cart"></i> Not available for now to add to cart
                                    :/ </a>
                                <?php
                            } else {
                                ?>

                                <div id="cart-btn-container">
                                    <!-- <a class="btn" id="cart-btn" data-task="add || remove"><i class="fa fa-shopping-cart"></i>Remove from cart || Add to cart </a> -->
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="d-flex flex-row gap-2">
                            <!-- edit book details -->
                            <a href="/bookrack/add-book/edit/<?= $bookId ?>"
                                class="btn btn-outline-success d-flex flex-row gap-2 align-items-center"
                                style="width:fit-content;">
                                <i class="fa-solid fa-edit"></i>
                                <p class="m-0"> Edit </p>
                            </a>

                            <!-- delete book -->
                            <?php
                            $flag = $selectedBook->flag;
                            if ($flag == '') {
                                ?>
                                <a href="/bookrack/app/delete-book-code.php?bookId=<?= $bookId ?>"
                                    class="btn btn-danger d-flex flex-row gap-2 align-items-center" style="width:fit-content;">
                                    <i class="fa-solid fa-trash"></i>
                                    <p class="m-0"> Delete </p>
                                </a>
                                <?php
                            } elseif ($flag == 'deleted') {
                                ?>
                                <a class="btn btn-danger d-flex flex-row gap-2 align-items-center" style="width:fit-content;">
                                    <i class="fa-solid fa-trash"></i>
                                    <p class="m-0"> Deleted </p>
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>
        </main>
        <?php
    } else {
        ?>
        <main class="d-flex flex-column gap-3 pb-5 container main">
            <!-- title, rating, count -->
            <section class="d-flex flex-column title-rating-count-container">
                <h1> Book Not Found! </h1>
            </section>
        </main>
        <?php
    }
    ?>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- script -->
    <script>
        $(document).ready(function () {
            // load wishlist btn
            book_id = "<?= $bookId ?>";
            user_id = "<?= $userId ?>";

            // check wishlist
            function checkWishlist() {
                $.ajax({
                    url: '/bookrack/app/load-wishlist-btn-book-detail.php',
                    type: "POST",
                    data: { bookId: book_id, userId: user_id },
                    success: function (response) {
                        $('#wishlist-btn-container').html(response);
                    }
                });
            }

            // check cart
            function checkCart() {
                $.ajax({
                    url: '/bookrack/app/load-cart-btn-book-detail.php',
                    type: "POST",
                    data: { userId: user_id, bookId: book_id },
                    success: function (response) {
                        $('#cart-btn-container').html(response);
                    }
                });
            }

            checkWishlist();
            checkCart();

            // toggle wishlist
            $(document).on('click', '#wishlist-btn', function () {
                wishlist_task = $('#wishlist-btn').data("task");
                $.ajax({
                    url: '/bookrack/app/toggle-wishlist-book-detail.php',
                    type: "POST",
                    data: { bookId: book_id },
                    beforeSend: function () {
                        if (wishlist_task == "add") {
                            wishlist_future = "<a class='btn' id='wishlist-btn' data-task='remove'> <i class='fa-solid fa-bookmark'></i> Remove from wishlist </a>";
                            $('#wishlist-btn-container').html(wishlist_future);
                        }
                        else {
                            wishlist_future = "<a class='btn' id='wishlist-btn' data-task='add'> <i class='fa-regular fa-bookmark'></i> Add to wishlist </a>";
                            $('#wishlist-btn-container').html(wishlist_future);
                        }
                    },
                });
            });

            // toggle cart
            $(document).on('click', '#cart-btn', function () {
                cart_task = $('#cart-btn').data("task");
                $.ajax({
                    url: '/bookrack/app/toggle-cart-book-detail.php',
                    type: "POST",
                    data: { bookId: book_id, task: cart_task },
                    beforeSend: function () {
                        if (cart_task == "add") {
                            cart_future = "<a class='btn' id='cart-btn' data-task='remove'><i class='fa fa-shopping-cart'></i> Remove from cart </a>";
                            $('#cart-btn-container').html(cart_future);
                        }
                        else {
                            cart_future = "<a class='btn' id='cart-btn' data-task='add'><i class='fa fa-shopping-cart'></i> Add to cart </a>";
                            $('#cart-btn-container').html(cart_future);
                        }
                    },
                    success: function (response) {
                        // checkCart();
                    },
                });
            });
        });
    </script>
</body>

</html>