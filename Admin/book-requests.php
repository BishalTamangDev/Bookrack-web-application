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
require_once __DIR__ . '/../../bookrack/app/functions.php';

$profileAdmin = new Admin();

$profileAdmin->setId($_SESSION['bookrack-admin-id']);
$profileAdmin->fetch($profileAdmin->getId());

if ($profileAdmin->getAccountStatus() != "verified") {
    header("Location: /bookrack/admin/admin-profile");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Book Requests </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
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
    <!-- aside :: nav -->
    <?php
    include 'nav.php';
    ?>

    <!-- main content -->
    <main class="main">
        <!-- heading -->
        <p class="page-heading"> Book Requests </p>

        <!-- cards -->
        <section class="section card-container">
            <!-- number of new offers -->
            <div class="card-v1">
                <p class="card-v1-title"> Number of Requests </p>
                <p class="card-v1-detail"> 1245 </p>
            </div>

            <!-- number of contributors -->
            <div class="card-v1">
                <p class="card-v1-title"> Accepted </p>
                <p class="card-v1-detail"> 207 </p>
            </div>

            <!-- number of contributors -->
            <div class="card-v1">
                <p class="card-v1-title"> Rejected </p>
                <p class="card-v1-detail"> 79 </p>
            </div>
        </section>

        <!-- table to section -->
        <section class="section table-top-section">
            <!-- filter div -->
            <div class="filter-div">
                <!-- filter icon -->
                <i class="fa fa-filter" id="filter-icon"></i>

                <!-- book status -->
                <select class="form-select" aria-label="select">
                    <option value="0" selected hidden> Book status </option>
                    <option value="1"> All </option>
                    <option value="2"> Accepted </option>
                    <option value="3"> Rejected </option>
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
        </section>

        <!-- book table -->
        <table class="table table-striped user-table">
            <!-- heading -->
            <thead>
                <tr>
                    <th scope="col"> Book ID </th>
                    <th scope="col"> Requested By </th>
                    <th scope="col"> ISBN </th>
                    <th scope="col"> Title </th>
                    <th scope="col"> Genre </th>
                    <th scope="col"> Author[s] </th>
                    <th scope="col"> Book Owner </th>
                    <th scope="col"> Book State </th>
                </tr>
            </thead>

            <!-- body -->
            <tbody>
                <tr class="request-row accepted-row rejected-row ">
                    <th scope="row">
                        <abbr title="Show book details">
                            <a href="/bookrack/admin/admin-book-details"> 124745 </a>
                        </abbr>
                    </th>
                    <td>
                        <abbr title="Show user details">
                            <a href="/bookrack/admin/admin-user-details"> Bishal Tamang </a>
                        </abbr>
                    </td>
                    <td> 978-1-84356-028-9 </td>
                    <td> To Kill a Mockingbird </td>
                    <td> Fiction, Classic, Historical </td>
                    <td> Harper Lee </td>
                    <td> Bishal Tamang </td>
                    <td> Accepted </td>
                </tr>

                <tr class="request-row accepted-row rejected-row ">
                    <th scope="row">
                        <abbr title="Show book details">
                            <a href="/bookrack/admin/admin-book-details"> 124745 </a>
                        </abbr>
                    </th>
                    <td>
                        <abbr title="Show user details">
                            <a href="/bookrack/admin/admin-user-details"> Rupak Dangi </a>
                        </abbr>
                    </td>
                    <td> 978-0-596-52068-7 </td>
                    <td> 1984 </td>
                    <td> Dystopian, Science Fiction, Political Fiction </td>
                    <td> George Orwell </td>
                    <td> Rupak Dangi </td>
                    <td> Rejected </td>
                </tr>

                <tr class="request-row accepted-row rejected-row ">
                    <th scope="row">
                        <abbr title="Show book details">
                            <a href="/bookrack/admin/admin-book-details"> 124745 </a>
                        </abbr>
                    </th>
                    <td>
                        <abbr title="Show user details">
                            <a href="/bookrack/admin/admin-user-details"> Shristi Pradhan </a>
                        </abbr>
                    </td>
                    <td> 978-3-16-148410-0 </td>
                    <td> The Great Gatsby </td>
                    <td> Fiction, Classic, Tragedy </td>
                    <td> F. Scott Fitzgerald </td>
                    <td> Shristi Pradhan </td>
                    <td> Accepted </td>
                </tr>
            </tbody>

            <!-- footer -->
            <tfoot id="table-foot">
                <tr>
                    <td colspan="9"> No book request yet! </td>
                </tr>
            </tfoot>
        </table>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../../bookrack/app/jquery-js-bootstrap-include.php'; ?>
</body>

</html>