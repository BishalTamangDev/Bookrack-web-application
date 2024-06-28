<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "dashboard";
$adminId = $_SESSION['bookrack-admin-id'];

// fetching the admin profile details
require_once __DIR__ . '/app/admin-class.php';

$profileAdmin = new Admin();
$profileAdmin->fetch($adminId);

if ($profileAdmin->getAccountStatus() != "verified")
    header("Location: /bookrack/admin/admin-profile");

require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/user-class.php';
require_once __DIR__ . '/../app/book-class.php';

$userObj = new User();
$bookObj = new Book();

$userList = $userObj->fetchAllUsers();
$bookList = $bookObj->fetchAllBooks();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Dashboard </title>

    <?php require_once __DIR__ . '/../app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/dashboard.css">
</head>

<body>
    <?php include 'nav.php'; ?>

    <main class="main">
        <p class="page-heading"> Dashboard </p>

        <!-- card & new users -->
        <section
            class="d-flex section flex-column flex-lg-row mt-3 gap-3 section justify-content-between count-card-new-users">
            <div class="count-card">
                <!-- cards -->
                <div class="card-container">
                    <!-- number of users -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Users </p>
                        <p class="card-v1-detail"> <?= sizeof($userList); ?> </p>
                    </div>

                    <!-- number of books -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Books </p>
                        <p class="card-v1-detail"> <?= sizeof($bookList) ?> </p>
                    </div>

                    <!-- number of books on rent -->
                    <div class="card-v1">
                        <p class="card-v1-title"> Books on Rent </p>
                        <p class="card-v1-detail"> <?= "-" ?> </p>
                    </div>
                </div>
            </div>

            <!-- new users joined -->
            <div class="d-flex flex-row align-items-center p-3 gap-2 rounded new-user-div">
                <div class="d-flex flex-row profile-container">
                    <!-- user 1 -->
                    <div class="profile-div">
                        <img src="/bookrack/assets/images/user-1.png" alt="" loading="lazy">
                    </div>

                    <!-- user 2 -->
                    <div class="profile-div">
                        <img src="/bookrack/assets/images/user-2.jpg" alt="" loading="lazy">
                    </div>

                    <!-- user 3 -->
                    <div class="profile-div">
                        <img src="/bookrack/assets/images/user-3.jpg" alt="" loading="lazy">
                    </div>
                </div>

                <!-- user joined label -->
                <div class="label">
                    <p class="f-reset"> 3 users joined today. </p>
                </div>
            </div>
        </section>

        <!-- recently added books -->
        <section class="section d-flex flex-column gap-4 recently-arrived-books-container">
            <!-- heading -->
            <p class="f-reset fs-5 fw-bold"> Recently arrived books </p>

            <!-- empty arrived book -->
            <p class="f-reset text-danger"> No books found! </p>

            <div class="d-flex flex-row flex-wrap gap-3 recently-arrived-books-div">
                <?php
                foreach ($bookList as $book) {
                    ?>
                    <div class="recently-arrived-book"
                        onclick="window.location.href='/bookrack/admin/admin-book-details/<?= $book->getId() ?>'">
                        <div class="image-div">
                            <img src="<?= $book->photoUrl['cover'] ?>" alt="book cover photo" loading="laxy">
                        </div>

                        <div class="detail">
                            <div class="title">
                                <p> <?= ucWords($book->title) ?> </p>
                            </div>

                            <div class="genre">
                                <?php
                                $count = 0;
                                foreach ($book->genre as $genre) {
                                    $count++;
                                    ?>
                                    <p>
                                        <?php
                                        echo ($count != count($book->genre)) ? $genre . ", " : $genre; ?>
                                    </p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <a href="/bookrack/admin/admin-books" class="btn btn-outline-warning m-auto" id="show-all-recently-added">
                Show all </a>
        </section>

        <!-- offer and request -->
        <section class="section d-flex flex-column flex-lg-row gap-3 justify-content-between offer-request">
            <!-- request -->
            <div class="request-container w-50 w-md-50">
                <!-- heading -->
                <p class="f-reset fs-5 fw-bold"> New Requests </p>

                <div class="mt-3 request-div">
                    <!-- request table -->
                    <table class="table table-striped request-table">
                        <!-- table heading -->
                        <thead>
                            <tr>
                                <th scope="col"> Title </th>
                                <th scope="col"> Reader </th>
                                <th scope="col"> Rent days </th>
                                <th scope="col"> Price </th>
                            </tr>
                        </thead>

                        <!-- table data -->
                        <tbody>
                            <!-- dummy data -->
                            <tr>
                                <td> Intuition </td>
                                <td> Rupak Dangi </td>
                                <td> 1 week </td>
                                <td> NRs. 120.00 </td>
                            </tr>
                        </tbody>

                        <!-- table footer -->
                        <tfoot id="table-foot">
                            <tr class="empty-request-tr">
                                <td colspan="4"> No new request! </td>
                            </tr>
                            <tr>
                                <td colspan="9">
                                    <a href="/bookrack/admin/admin-book-requests"> Show all requests </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>

        <!-- rent ending -->
        <section class="d-flex flex-column section gap-4 rent-ending-container">
            <div class="heading">
                <p class="f-reset fs-4 fw-bold"> Some books' returning date is arriving soon </p>
            </div>

            <p class="f-reset text-danger"> No books has been issued yet!</p>

            <div class="d-flex flex-row flex-wrap gap-3 rent-ending-div">
                <!-- dummy data 1 -->
                <div class="rent-ending">
                    <!-- image -->
                    <div class="image-div">
                        <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy">
                    </div>

                    <!-- detail -->
                    <div class="detail-div">
                        <!-- title -->
                        <div class="title-div">
                            <a href="/bookrack/admin/admin-book-details">
                                The Black Universe
                            </a>
                        </div>

                        <!-- days remaining -->
                        <div class="remaining-days-div">
                            <p class="remaining-days"> 3 days <span> remaining </span> </p>
                        </div>


                        <!-- reader -->
                        <div class="reader-div">
                            <p> Shristi Pradhan </p>
                        </div>

                        <!-- contact -->
                        <div class="contact-div">
                            <a href="/bookrack/admin/admin-user-details" class="btn">Contact Reader</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#" class="btn m-auto" id="show-all-rent-ending"> Show all </a>
        </section>

        <!-- book reviews -->
        <section class="d-flex flex-column section gap-3 book-review-container">
            <!-- heading -->
            <p class="f-reset fs-4 fw-bold"> Book Reviews </p>

            <p class="f-reset text-danger"> No book reviews has been submitted yet! </p>

            <!-- book reviews -->
            <div class="book-review-div">
                <!-- review 1 -->
                <div class="book-review">
                    <div class="image-div">
                        <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy">
                    </div>

                    <div class="detail-div">
                        <div class="reader-div">
                            <p> Shristi Pradhan </p>
                        </div>

                        <div class="review-div">
                            <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum harum nulla
                                architecto laborum ratione deserunt quae obcaecati rem commodi assumenda, error
                                fugiat aperiam unde vero doloremque a quas ducimus dicta dignissimos voluptatem
                                consectetur perferendis minus nihil alias. Sequi, vel aperiam, dolor quidem eum
                                excepturi voluptatum deserunt rerum sed rem modi! </p>
                        </div>

                        <div class="rating-div">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/half-rating.png" alt="" loading="lazy">
                        </div>
                    </div>
                </div>

                <!-- review 2 -->
                <div class="book-review">
                    <div class="image-div">
                        <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy">
                    </div>

                    <div class="detail-div">
                        <div class="reader-div">
                            <p> Rupak Dangi </p>
                        </div>

                        <div class="review-div">
                            <p> A quas ducimus dicta dignissimos voluptatem
                                consectetur perferendis minus nihil alias. Sequi, vel aperiam, dolor quidem eum
                                excepturi voluptatum deserunt rerum sed rem modi! </p>
                        </div>

                        <div class="rating-div">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        </div>
                    </div>
                </div>

                <!-- review 3 -->
                <div class="book-review">
                    <div class="image-div">
                        <img src="/bookrack/assets/images/cover-3.jpg" alt="" loading="lazy">
                    </div>

                    <div class="detail-div">
                        <div class="reader-div">
                            <p> Bishal Tamang </p>
                        </div>

                        <div class="review-div">
                            <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum harum nulla
                                architecto laborum ratione deserunt quae obcaecati rem commodi assumenda, error
                                fugiat aperiam unde vero doloremque a quas ducimus dicta dignissimos voluptatem
                                consectetur perferendis minus nihil alias. Sequi, vel aperiam, dolor quidem eum
                                excepturi voluptatum deserunt rerum sed rem modi! </p>
                        </div>

                        <div class="rating-div">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                            <img src="/bookrack/assets/icons/half-rating.png" alt="" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <a href="#" class="btn m-auto" id="show-all-reviews"> Show all </a>
        </section>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../app/script-include.php'; ?>
</body>

</html>