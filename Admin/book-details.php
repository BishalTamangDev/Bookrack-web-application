<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "book-details";
$adminId = $_SESSION['bookrack-admin-id'];

require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

// fetching the admin profile details
$profileAdmin = new Admin();
$adminExists = $profileAdmin->fetch($adminId);

if (!$adminExists)
    header("Location: /bookrack/admin/signin");

if ($profileAdmin->getAccountStatus() != "verified")
    header("Location: /bookrack/admin/admin-profile");

require_once __DIR__ . '/../../bookrack/app/functions.php';
require_once __DIR__ . '/../../bookrack/app/user-class.php';
require_once __DIR__ . '/../../bookrack/app/book-class.php';

// user object
$userObj = new User();

// book object
$bookObj = new Book();
$bookExists = $bookObj->fetch($bookId);

if (!$bookExists)
    header("Location: /bookrack/admin/admin-dashbaord");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> <?= ucWords($bookObj->title) ?> </title>

    <?php require_once __DIR__ . '/../../bookrack/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/book-detail.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php include 'nav.php'; ?>

    <!-- main content -->
    <main class="main">
        <!-- heading & rating -->
        <section class="d-flex flex-column heading-rating">
            <!-- heading -->
            <p class="page-heading"> <?= ucWords($bookObj->title) ?> </p>

            <!-- rating -->
            <div class="d-flex flex-row align-items-center gap-2 rating-count">
                <div class="d-flex flex-row align-items-center gap-1 rating">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon"
                        loading="lazy">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon"
                        loading="lazy">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon"
                        loading="lazy">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon"
                        loading="lazy">
                    <img src="/bookrack/assets/icons/half-rating.png" alt="rating-star" class="rating-icon"
                        loading="lazy">
                </div>

                <div class="count">
                    <p class="f-reset"> (<?= "0" ?>) </p>
                </div>
            </div>
        </section>

        <!-- book details -->
        <section class="d-flex flex-column flex-lg-row gap-4 section book-detail-container">
            <!-- book photos -->
            <div class="d-flex flex-row flex-lg-column gap-2 book-photo-div">
                <div class="d-flex flex-row top">
                    <img src="<?= $bookObj->photoUrl['cover']; ?>" alt="Cover page photo" loading="lazy">
                </div>

                <div class="d-flex flex-column flex-lg-row gap-2 bottom">
                    <img src="<?= $bookObj->photoUrl['cover']; ?>" alt="Cover page photo" loading="lazy">
                    <img src="<?= $bookObj->photoUrl['price']; ?>" alt="Price page photo" loading="lazy">
                    <img src="<?= $bookObj->photoUrl['isbn']; ?>" alt="Isbn page photo" loading="lazy">
                </div>
            </div>

            <!-- book all details -->
            <div class="d-flex flex-column flex-lg-row gap-3 book-detail">
                <!-- book details -->
                <div class="d-flex flex-column gap-4 book-core-detail">
                    <!-- description -->
                    <div class="d-flex flex-column desciption-div">
                        <p class="f-reset fw-bold fs-5"> Description </p>
                        <p class="f-reset fs-6"> <?= ucfirst($bookObj->description) ?> </p>
                    </div>

                    <!-- genre -->
                    <div class="d-flex flex-column gap-2 genre-div">
                        <p class="f-reset fw-bold fs-5"> Genre </p>

                        <div class="d-flex flex-row gap-2 genre-list">
                            <?php
                            foreach ($bookObj->genre as $genre) {
                                ?>
                                <div class="genre">
                                    <p class="m-0"> <?= $genre ?> </p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- author -->
                    <div class="d-flex flex-column gap-2 author-div">
                        <p class="f-reset fw-bold fs-5"> Author[s] </p>

                        <div class="d-flex flex-row gap-2 author-list">
                            <?php
                            foreach ($bookObj->author as $author) {
                                ?>
                                <div class="author">
                                    <p class="f-reset"> <?= ucwords($author) ?> </p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- edition -->
                    <div class="d-flex flex-row gap-3 align-items-center edition-div">
                        <p class="f-reset fw-bold fs-5"> Edition </p>
                        <p class="f-reset fs-6"> <?= $bookObj->edition ?><sup><?php
                          $remainder = $bookObj->edition % 10;

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
                          ?></sup> </p>
                    </div>

                    <!-- publisher -->
                    <div class="d-flex flex-row gap-3 align-items-center edition-div">
                        <p class="f-reset fw-bold fs-5"> Publisher </p>
                        <p class="f-reset fs-6"> <?= ucWords($bookObj->publisher) ?> </p>
                    </div>

                    <!-- publication -->
                    <div class="d-flex flex-row gap-3 edition-div">
                        <p class="f-reset fw-bold fs-5"> Publication </p>
                        <p class="f-reset fs-6"> <?= ucWords($bookObj->publication) ?> </p>
                    </div>
                </div>

                <!-- book surface details -->
                <div class="d-flex flex-column book-surface-detail">
                    <!-- top section - book availability for rent -->
                    <div class="d-flex flex-row book-availability py-2">
                        <p class="f-reset text-light m-auto fs-6"> Available for Rent </p>
                    </div>

                    <div class="d-flex flex-column gap-3 px-3 pt-3 surface-bottom">
                        <!-- book id -->
                        <p class="f-reset fw-bold"> <?= $bookObj->getId() ?> </p>

                        <!-- language -->
                        <div class="d-flex flex-row language">
                            <div class="left">
                                <p class="f-reset fw-bold"> Language </p>
                            </div>

                            <div class="right">
                                <p class="f-reset fw-bold"> <?= ucFirst($bookObj->language) ?> </p>
                            </div>
                        </div>

                        <!-- price -->
                        <div class="d-flex flex-row price">
                            <div class="left">
                                <p class="f-reset fw-bold"> Price </p>
                            </div>

                            <div class="right">
                                <p class="f-reset fw-bold text-success">
                                    <?= number_format($bookObj->price['actual'], 2) ?>
                                </p>
                            </div>
                        </div>

                        <!-- owner -->
                        <div class="d-flex flex-row owner">
                            <div class="left">
                                <p class="f-reset fw-bold"> Owner </p>
                            </div>

                            <?php $userObj->fetch($bookObj->getOwnerId()); ?>

                            <div class="right">
                                <abbr title="Show owner details">
                                    <p class="f-reset fw-bold pointer">
                                        <a href="/bookrack/admin/admin-user-details/<?= $userObj->getUserId() ?>"
                                            class="text-dark">
                                            <?= ucfirst($userObj->name['first']) . " " . ucfirst($userObj->name['last']) ?>
                                        </a>
                                    </p>
                                </abbr>
                            </div>
                        </div>
                    </div>

                    <!-- operation: edit/ remove -->
                    <div class="d-none d-flex flex-row justify-content-between mt-2 gap-2 align-items-center book-operation">
                        <!-- delete operation -->
                        <div class="d-flex flex-row gap-2 align-items-center delete">
                            <i class="fa fa-trash"></i>
                            <p class="f-reset fs-6"> Delete </p>
                        </div>

                        <!-- editi operation -->
                        <div class="d-flex flex-row gap-2 align-items-center edit">
                            <i class="fa fa-edit"></i>
                            <p class="f-reset fs-6"> Edit </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- book rent history -->
        <section class="section book-rent-history">
            <p class="f-reset fw-bold fs-5 text-danger"> Book Rent History </p>

            <table class="table mt-2 book-rent-history-table">
                <!-- header -->
                <thead>
                    <tr>
                        <th scope="col"> S.N. </th>
                        <th scope="col"> Rented By </th>
                        <th scope="col"> Issued Date </th>
                        <th scope="col"> Returned Date </th>
                        <th scope="col"> Fine </th>
                        <th scope="col"> Rent Status </th>
                    </tr>
                </thead>

                <!-- body -->
                <tbody>
                    <!-- dummy data -->
                    <tr class="book-row on-rent-row on-stock-row">
                        <td> 1. </td>
                        <td> Rupak Dangi </td>
                        <td> 2222-22-22 </td>
                        <td> 2222-22-33 </td>
                        <td> NRs. 120 </td>
                        <td> Completed </td>
                    </tr>

                    <tr class="book-row on-rent-row on-stock-row">
                        <td> 2. </td>
                        <td> Shristi Pradhan </td>
                        <td> 3333-33-33 </td>
                        <td> - </td>
                        <td> - </td>
                        <td> Active </td>
                    </tr>
                </tbody>

                <!-- footer -->
                <tfoot id="table-foot">
                    <tr>
                        <td colspan="9"> No book rent history found! </td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../../bookrack/app/script-include.php'; ?>
</body>

</html>