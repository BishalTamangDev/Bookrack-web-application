<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "books";
$adminId = $_SESSION['bookrack-admin-id'];

// fetching the admin profile details
require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';

// profile admin object
$profileAdmin = new Admin();
$adminExists = $profileAdmin->checkAdminExistenceById($adminId);

if (!$adminExists)
    header("Location: /bookrack/admin/app/admin-signout.php");

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");

require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/user-class.php';
require_once __DIR__ . '/../app/book-class.php';

// user object
$userObj = new User();

// book object
$bookObj = new Book();

// fetching all books
$bookIdList = $bookObj->fetchAllBookId();

// book count
$rentBookCount = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Books </title>

    <?php require_once __DIR__ . '/../app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
</head>

<body>
    <?php include 'nav.php'; ?>

    <!-- main content -->
    <main class="main">
        <!-- cards -->
        <?php
        if (!$search) {
            ?>
            <section class="section mt-5 pt-3 card-container">
                <!-- number of books -->
                <div class="card-v1">
                    <p class="card-v1-title"> Number of Books </p>
                    <p class="card-v1-detail"> <?= sizeof($bookIdList) ?> </p>
                </div>

                <!-- number of books on rent -->
                <div class="card-v1">
                    <p class="card-v1-title"> Books on Rent </p>
                    <p class="card-v1-detail"> <?= $rentBookCount ?> </p>
                </div>
            </section>
            <?php
        } else {
            // chear search
            ?>
            <div class="mt-5 pt-3 d-flex flex-row">
                <a href="/bookrack/admin/admin-books" class="btn btn-danger d-flex flex-row align-items-center gap-2">
                    <p class="m-0"> Clear Search </p>
                    <i class="fa fa-multiply text-white fs-5"></i>
                </a>
            </div>
            <?php
        }
        ?>

        <!-- table to section -->
        <div class="section table-top-section">
            <!-- filter -->
            <div class="filter-div">
                <i class="fa fa-filter" id="filter-icon"></i>

                <!-- rent / stock -->
                <select class="form-select" aria-label="select" id="flag-select">
                    <option value="all" selected> Book status: all </option>
                    <option value="available"> Book status: available </option>
                    <option value="on-stock"> Book status: on stock </option>
                    <option value="sold-out"> Book status: sold out </option>
                </select>

                <!-- genre -->
                <select class="d-none form-select" aria-label="select" id="genre-select">
                    <option value="all" selected> All genre </option>
                    <?php
                    foreach ($genreArray as $genre) {
                        ?>
                        <option value="<?= $genre ?>"> <?= $genre ?> </option>
                        <?php
                    }
                    ?>
                </select>

                <!-- language -->
                <select class="form-select" aria-label="select" id="language-select">
                    <option value="all" selected> Language: all </option>
                    <option value="chinese"> Language: chinese </option>
                    <option value="english"> Language: english </option>
                    <option value="hindi"> Language: hindi </option>
                    <option value="nepali"> Language: nepali </option>
                </select>

                <!-- clear filter -->
                <div class="clear-filter-div" id="clear-sort">
                    <p class="f-reset"> Clear </p>
                    <i class="fa fa-multiply"></i>
                </div>
            </div>
        </div>

        <!-- book table -->
        <table class="table table-striped user-table">
            <!-- header -->
            <thead>
                <tr>
                    <th scope="col"> SN </th>
                    <th scope="col"> Title </th>
                    <th scope="col"> ISBN </th>
                    <th scope="col"> Genre </th>
                    <th scope="col"> Author[s] </th>
                    <th scope="col"> Language </th>
                    <th scope="col"> Owner </th>
                    <th scope="col"> Book State </th>
                    <th scope="col"> Action </th>
                </tr>
            </thead>

            <!-- body -->
            <?php
            if (sizeof($bookIdList) > 0) {
                ?>
                <tbody>
                    <?php
                    $serial = 1;
                    foreach ($bookIdList as $bookId) {
                        $bookObj->fetch($bookId);
                        $show = true;
                        $ownerName = $userObj->fetchUserName($bookObj->getOwnerId());

                        if ($search)
                            $show = (strpos(strtolower($bookObj->title), $searchContent) !== false || strpos($ownerName, $searchContent) !== false || strpos($bookObj->isbn, $searchContent) !== false) ? true : false;

                        if ($show) {
                            ?>
                            <tr class="book-tr <?php
                            if ($bookObj->flag == 'verified')
                                echo 'available-tr';
                            elseif ($bookObj->flag == 'on-stock')
                                echo 'on-stock-tr';
                            elseif ($bookObj->flag == 'sold-out')
                                echo 'sold-out-tr';
                            ?> <?= "{$bookObj->language}-tr" ?>">
                                <th scope="row"> <?= $serial++ ?> </th>
                                <td> <?= ucWords($bookObj->title) ?> </td>
                                <td> <?= $bookObj->isbn ?> </td>
                                <td>
                                    <?php
                                    $count = 0;
                                    foreach ($bookObj->genre as $genre) {
                                        $count++;
                                        echo $count != count($bookObj->genre) ? $genre . ', ' : $genre;
                                    }
                                    ?>
                                </td>
                                <td> <?php foreach ($bookObj->author as $author)
                                    echo ucWords($author) . ', '; ?> </td>
                                <td> <?= ucfirst($bookObj->language) ?></td>
                                <td>
                                    <a href="/bookrack/admin/admin-user-details/<?= $bookObj->getOwnerId() ?>">
                                        <?= ucwords($ownerName) ?>
                                </td>
                                </a>
                                <td> <?= $bookObj->flag ?></td>
                                <td>
                                    <abbr title="Show full details">
                                        <a href="/bookrack/admin/admin-book-details/<?= $bookId ?>">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </abbr>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </tbody>
                <?php
            }
            ?>
            <!-- footer -->
            <tfoot id="table-foot">
                <tr>
                    <td colspan="10"> No book found! </td>
                </tr>
            </tfoot>

        </table>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../app/script-include.php'; ?>

    <script>
        // filtering
        var sort = false;

        const clearSort = $('#clear-sort');

        const flagSelect = $('#flag-select');
        const flagAvailable = $('.available-tr');
        const flagOnStock = $('.on-stock-tr');
        const flagSoldOut = $('.sold-out-tr');

        const languageSelect = $('#language-select');
        const languageChinese = $('.chinese-tr');
        const languageEnglish = $('.english-tr');
        const languageHindi = $('.hindi-tr');
        const languageNepali = $('.nepali-tr');

        var flag = "all";
        var language = "all";

        // flag select
        flagSelect.on('change', function () {
            flag = flagSelect.val();
            filterBook();
        });

        // language select
        languageSelect.on('change', function () {
            language = languageSelect.val();
            filterBook();
        });

        // clear sort
        clearSort.on('click', function () {
            sort = false;
            flag = "all";
            language = "all";
            flagSelect.val("all");
            languageSelect.val('all');
            filterBook();
        });


        // show empty row
        toggleEmptyRow = () => {
            if ($('.book-tr').is(':visible'))
                $('#table-foot').hide();
            else
                $('#table-foot').show();
        }

        filterBook = () => {
            $('.book-tr').show();

            sort = false;
            // flag
            if (flag != "all") {
                sort = true;
                if (flag == 'available-tr') {
                    flagOnStock.hide();
                    flagSoldOut.hide();
                } else if (flag == 'on-stock') {
                    flagAvailable.hide();
                    flagSoldOut.hide();
                } else if (flag == 'sold-out') {
                    flagAvailable.hide();
                    flagSoldOut.hide();
                }
            }

            // language
            if (language != "all") {
                sort = true;
                if (language == "chinese") {
                    languageEnglish.hide();
                    languageHindi.hide();
                    languageNepali.hide();
                } else if (language == "english") {
                    languageChinese.hide();
                    languageHindi.hide();
                    languageNepali.hide();
                } else if (language == "hindi") {
                    languageChinese.hide();
                    languageEnglish.hide();
                    languageNepali.hide();
                } else if (language == "nepali") {
                    languageChinese.hide();
                    languageEnglish.hide();
                    languageHindi.hide();
                }
            }

            toggleClearSort();
            toggleEmptyRow();
        }

        // clear filtering
        toggleClearSort = () => {
            if (sort == true)
                clearSort.show();
            else
                clearSort.hide();
        }

        filterBook();
        toggleEmptyRow();
    </script>
</body>

</html>