<?php
$url = "book-details";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");

require_once __DIR__ . '/../classes/admin.php';
require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../classes/book.php';
require_once __DIR__ . '/../functions/district-array.php';

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

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/book-detail.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <!-- main content -->
    <main class="main">
        <!-- heading & rating -->
        <section class="d-flex flex-column mt-4 heading-rating">
            <!-- heading -->
            <p class="page-heading"> <?= ucWords($bookObj->title) ?> </p>
        </section>

        <!-- book details -->
        <section class="d-flex flex-column flex-lg-row gap-4 section book-detail-container">
            <!-- book photos -->
            <?php $bookObj->setPhotoUrl() ?>
            <div class="d-flex flex-row flex-lg-column gap-2 book-photo-div">
                <div class="d-flex flex-row top">
                    <img src="<?= $bookObj->photoUrl ?>" alt="Cover page photo" loading="lazy">
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
                        <p class="f-reset fs-6"> <?= ucfirst($bookObj->edition) ?><sup><?php
                          if (is_int($bookObj->edition)) {
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
                          }
                          ?></sup> </p>
                    </div>

                    <!-- publisher -->
                    <div class="d-flex flex-row gap-3 align-items-center edition-div">
                        <p class="f-reset fw-bold fs-5"> Publisher </p>
                        <p class="f-reset fs-6"> <?= ucWords($bookObj->publisher) ?> </p>
                    </div>
                </div>

                <!-- book surface details -->
                <div class="d-flex flex-column book-surface-detail">
                    <!-- top section - book availability for rent -->
                    <div class="d-flex flex-row book-availability py-2">
                        <p class="f-reset text-light m-auto fs-6"> Available for Rent </p>
                    </div>

                    <div class="d-flex flex-column gap-3 px-3 pt-3 surface-bottom">
                        <!-- language -->
                        <div class="d-flex flex-row language">
                            <div class="left">
                                <p class="f-reset fw-bold"> Language </p>
                            </div>

                            <div class="right">
                                <p class="f-reset fw-bold"> <?= ucFirst($bookObj->language) ?> </p>
                            </div>
                        </div>

                        <!-- actual price -->
                        <div class="d-flex flex-row price">
                            <div class="left">
                                <p class="f-reset fw-bold"> Actual price </p>
                            </div>

                            <div class="right">
                                <p class="f-reset fw-bold text-success">
                                    <?= "NPR. " . number_format($bookObj->price['actual'], 2) ?>
                                </p>
                            </div>
                        </div>

                        <!-- offer price -->
                        <div class="d-flex flex-row price">
                            <div class="left">
                                <p class="f-reset fw-bold"> Offer price </p>
                            </div>

                            <div class="right">
                                <p class="f-reset fw-bold text-success">
                                    <?= "NPR. " . number_format($bookObj->price['offer'], 2) ?>
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
                                            <?= $userObj->getFullName() ?>
                                        </a>
                                    </p>
                                </abbr>
                            </div>
                        </div>
                    </div>

                    <!-- operation: edit/ remove -->
                    <div
                        class="d-none d-flex flex-row justify-content-between mt-2 gap-2 align-items-center book-operation">
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
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>
</body>

</html>