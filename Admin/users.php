<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['bookrack-admin-id'])){
    header("Location: /bookrack/admin/signin");
}

// fetching the admin profile details
require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

$profileAdmin = new Admin();

$profileAdmin->setId($_SESSION['bookrack-admin-id']);
$profileAdmin->fetch($profileAdmin->getId());

if($profileAdmin->getAccountStatus() != "verified"){
    header("Location: /bookrack/admin/profile");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Users </title>

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
    <link rel="stylesheet" href="/bookrack/assets/css/admin/users.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php
    include 'nav.php';
    ?>

    <!-- main content -->
    <main class="main">
        <!-- heading -->
        <p class="fw-bold page-heading"> Users </p>

        <!-- cards -->
        <section class="section card-container">
            <!-- number of users -->
            <div class="card-v1">
                <p class="card-v1-title"> Number of Users </p>
                <p class="card-v1-detail"> 1245 </p>
            </div>

            <!-- number of contributors -->
            <div class="card-v1">
                <p class="card-v1-title"> Contributors </p>
                <p class="card-v1-detail"> 207 </p>
            </div>
        </section>

        <!-- table to section -->
        <section class="section d-flex flex-column flex-lg-row justify-content-between gap-2 table-top-section">
            <div class="filter-div width-fit-content">
                <i class="fa fa-filter" id="filter-icon"></i>

                <!-- account state -->
                <select class="form-select" aria-label="Default select example">
                    <option value="0" selected hidden> Account State </option>
                    <option value="1"> All </option>
                    <option value="2"> Active </option>
                    <option value="3"> Inactive </option>
                </select>

                <!-- contribution -->
                <select class="form-select" aria-label="Default select example">
                    <option value="0" selected hidden> Contribution </option>
                    <option value="1"> All </option>
                    <option value="2"> Contributor </option>
                    <option value="3"> Not-contributor </option>
                </select>

                <!-- clear filter -->
                <div class="clear-filter-div" id="clear-filter">
                    <p class="f-reset"> Clear </p>
                    <i class="fa fa-multiply"></i>
                </div>
            </div>

            <!-- search & clear section -->
            <div class="search-clear width-fit-content">
                <!-- clear search -->
                <div class="clear-search-div" id="clear-search">
                    <p class="f-reset"> Clear Search </p>
                    <i class="fa fa-multiply"></i>
                </div>

                <div class="search-container">
                    <input type="text" placeholder="search user">
                    <div class="search-icon-div">
                        <i class="fa fa-search"> </i>
                    </div>
                </div>
            </div>
        </section>

        <!-- user table -->
        <table class="table user-table">
            <!-- table heading -->
            <thead>
                <tr>
                    <th scope="col"> S.N. </th>
                    <th scope="col"> User ID </th>
                    <th scope="col"> Name </th>
                    <th scope="col"> Date of Birth </th>
                    <th scope="col"> Gender </th>
                    <th scope="col"> Email Address </th>
                    <th scope="col"> Contact </th>
                    <th scope="col"> Address </th>
                    <th scope="col"> Account State </th>
                    <th scope="col"> Action </th>
                </tr>
            </thead>

            <!-- table data -->
            <tbody>
                <!-- dummy data -->
                <tr class="active-user-row contributor-row">
                    <th scope="row"> 1 </th>
                    <td> 124745 </td>
                    <td> Bishal Tamang </td>
                    <td> 2001-01-01 </td>
                    <td> Male </td>
                    <td> bishal@gmail.com </td>
                    <td> 9845678415 </td>
                    <td> Kathmandu </td>
                    <td> Active </td>
                    <td>
                        <abbr title="Show full details">
                            <a href="/bookrack/admin/user-details">
                                <i class="fa fa-eye"></i>
                            </a>
                        </abbr>
                    </td>
                </tr>

                <tr class="active-user-row not-contributor-row">
                    <th scope="row"> 2 </th>
                    <td> 456789 </td>
                    <td> Rupak Dangi </td>
                    <td> 2002-02-02 </td>
                    <td> Male </td>
                    <td> rupak@gmail.com </td>
                    <td> 9852645899 </td>
                    <td> Bhaktapur </td>
                    <td> Inactive </td>
                    <td>
                        <abbr title="Show full details">
                            <a href="/bookrack/admin/user-details">
                                <i class="fa fa-eye"></i>
                            </a>
                        </abbr>
                    </td>
                </tr>

                <tr class="inactive-user-row contributor-row">
                    <th scope="row"> 3 </th>
                    <td> 159482 </td>
                    <td> Shristi Pradhan </td>
                    <td> 2003-03-03 </td>
                    <td> Female </td>
                    <td> shristi@gmail.com </td>
                    <td> 9858574859 </td>
                    <td> Lalitpur </td>
                    <td> Active </td>
                    <td>
                        <abbr title="Show full details">
                            <a href="/bookrack/admin/user-details">
                                <i class="fa fa-eye"></i>
                            </a>
                        </abbr>
                    </td>
                </tr>
            </tbody>

            <!-- table footer -->
            <tfoot id="table-foot">
                <tr>
                    <td colspan="10"> No users found! </td>
                </tr>
            </tfoot>
        </table>
    </main>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"> </script>

    <!-- bootstrap js :: cdn-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.js"> </script>
</body>

</html>