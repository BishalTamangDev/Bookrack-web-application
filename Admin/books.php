<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-admin-id'])) {
    header("Location: /bookrack/admin/admin-signin");
}

// fetching the admin profile details
require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';

// profile admin object
$profileAdmin = new Admin();

$profileAdmin->setId($_SESSION['bookrack-admin-id']);
$profileAdmin->fetch($profileAdmin->getId());

if ($profileAdmin->getAccountStatus() != "verified") {
    header("Location: /bookrack/admin/admin-profile");
}

require_once __DIR__ . '/../../bookrack/app/functions.php';
require_once __DIR__ . '/../../bookrack/app/user-class.php';
require_once __DIR__ . '/../../bookrack/app/book-class.php';

// user object
$userObj = new User();

// book object
$bookObj = new Book();

// fetching all books
$bookList = $bookObj->fetchAllBooks();

// book count
$allBookCount = count($bookObj->fetchAllBooks());
$rentBookCount = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Books </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/nav.css">
</head>

<body>
    <?php
    include 'nav.php';
    ?>

    <!-- main content -->
    <main class="main">
        <!-- heading -->
        <p class="page-heading"> Books </p>

        <!-- cards -->
        <section class="section card-container">
            <!-- number of books -->
            <div class="card-v1">
                <p class="card-v1-title"> Number of Books </p>
                <p class="card-v1-detail"> <?= $allBookCount ?> </p>
            </div>

            <!-- number of books on rent -->
            <div class="card-v1">
                <p class="card-v1-title"> Books on Rent </p>
                <p class="card-v1-detail"> <?= $rentBookCount ?> </p>
            </div>
        </section>

        <!-- table to section -->
        <div class="section table-top-section">
            <!-- filter -->
            <div class="filter-div">
                <i class="fa fa-filter" id="filter-icon"></i>

                <!-- rent / stock -->
                <select class="form-select" aria-label="select">
                    <option value="0" selected hidden> Book status </option>
                    <option value="1"> All </option>
                    <option value="2"> On Rent </option>
                    <option value="3"> On Stock </option>
                </select>

                <!-- genre -->
                <select class="form-select" aria-label="select">
                    <option value="0" selected hidden> All genre </option>
                    <?php
                    foreach ($genreArray as $genre) {
                        ?>
                        <option value="<?= $genre ?>"> <?= $genre ?> </option>
                        <?php
                    }
                    ?>
                </select>

                <!-- language -->
                <select class="form-select" aria-label="select">
                    <option value="0" selected hidden> All languages </option>
                    <option value="chinese"> Chinese </option>
                    <option value="english"> English </option>
                    <option value="hindi"> Hindi </option>
                    <option value="nepali"> Nepali </option>
                </select>

                <!-- clear filter -->
                <div class="clear-filter-div" id="clear-filter">
                    <p class="f-reset"> Clear </p>
                    <i class="fa fa-multiply"></i>
                </div>
            </div>

            <!-- search & clear section -->
            <div class="search-clear">
                <!-- clear search -->
                <div class="clear-search-div" id="clear-search">
                    <p class="f-reset"> Clear Search </p>
                    <i class="fa fa-multiply"></i>
                </div>

                <!-- search section -->
                <div class="search-container">
                    <input type="text" placeholder="search book">
                    <div class="search-icon-div">
                        <i class="fa fa-search"> </i>
                    </div>
                </div>
            </div>
        </div>

        <!-- book table -->
        <table class="table table-striped user-table">
            <!-- header -->
            <thead>
                <tr>
                    <th scope="col"> SN </th>
                    <th scope="col"> Book ID </th>
                    <th scope="col"> ISBN </th>
                    <th scope="col"> Title </th>
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
            if ($allBookCount > 0) {
                ?>
                <tbody>
                    <?php
                    $serial = 1;
                    foreach($bookList as $key => $book){
                        ?>
                        <tr class="book-row on-rent-row on-stock-row">
                            <th scope="row"> <?=$serial++?> </th>
                            <td> <?=$key?> </td>
                            <td> <?=$book['isbn']?> </td>
                            <td> <?=$book['title']?> </td>
                            <td>
                                <?php
                                $count = 0;
                                foreach($book['genre'] as $genre){
                                    $count++;
                                    echo $count != count($book['genre']) ? $genre.', ' : $genre;
                                }
                                ?>
                            </td>
                            <td> Harper Lee</td>
                            <td> <?=$book['language']?></td>
                            <td> <?=$book['owner_id']?></td>
                            <td> <?="-"?></td>
                            <td>
                                <abbr title="Show full details">
                                    <a href="/bookrack/admin/admin-book-details/<?=$key?>">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </abbr>
                            </td>
                        </tr>
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

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"> </script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>
</body>

</html>