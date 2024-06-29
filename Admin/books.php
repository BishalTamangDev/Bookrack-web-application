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

if(!$adminExists)
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
        <!-- heading -->
        <p class="page-heading"> Books </p>

        <!-- cards -->
        <section class="section card-container">
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
                        ?>
                        <tr class="book-row on-rent-row on-stock-row">
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
                            <td> <?= $bookObj->getOwnerId() ?></td>
                            <td> <?= $bookObj->status ?></td>
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
</body>

</html>