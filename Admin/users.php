<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-admin-id'])) {
    header("Location: /bookrack/admin/signin");
}

// fetching the admin profile details
require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

$profileAdmin = new Admin();

$profileAdmin->setId($_SESSION['bookrack-admin-id']);
$profileAdmin->fetch($profileAdmin->getId());

if ($profileAdmin->getAccountStatus() != "verified") {
    header("Location: /bookrack/admin/profile");
}

// including user class
require_once __DIR__ . '/../../bookrack/app/user-class.php';
$userObj = new User();

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
        <!-- fetch all users -->
        <?php
        $userList = $userObj->fetchAllUsers();
        ?>

        <!-- heading -->
        <p class="fw-bold page-heading"> Users </p>

        <!-- cards -->
        <section class="section card-container">
            <!-- number of users -->
            <div class="card-v1">
                <p class="card-v1-title"> Number of Users </p>
                <p class="card-v1-detail"> <?= sizeof($userList) ?> </p>
            </div>

            <!-- number of contributors -->
            <div class="card-v1">
                <p class="card-v1-title"> Contributors </p>
                <p class="card-v1-detail"> - </p>
            </div>
        </section>

        <!-- table to section -->
        <section class="section d-flex flex-column flex-lg-row justify-content-between gap-2 table-top-section">
            <div class="filter-div width-fit-content">
                <i class="fa fa-filter" id="filter-icon"></i>

                <!-- account state -->
                <select class="form-select" id="user-account-state-select" aria-label="Default select example">
                    <option value="all" selected> All account state </option>
                    <option value="verified"> Verified </option>
                    <option value="unverified"> Unverified </option>
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
                <form action="/bookrack/admin/users" method="POST">
                    <input type="search" name="search-content" class="form-control" placeholder="search user">
                </form>
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
            <?php
            if (sizeof($userList) > 0) {
                ?>
                <tbody>
                    <?php
                    $serial = 1;
                    foreach ($userList as $key => $user) {
                        ?>
                        <tr
                            class="user-tr <?= ($user['account_status'] == "verified") ? "verified-user-tr" : "unverified-user-tr" ?>">
                            <th scope="row"> <?= $serial++ ?> </th>
                            <td> <?= $key ?> </td>
                            <td> <?= getPascalCaseString($user['name']['first']) . " " . getPascalCaseString($user['name']['last']) ?>
                            </td>
                            <td> <?= $user['dob'] ?> </td>
                            <td>
                                <?php
                                if ($user['gender'] == 0)
                                    echo "Male";
                                elseif ($user['gender'] == 1)
                                    echo "Female";
                                else
                                    echo "Others";
                                ?>
                            </td>
                            <td> <?= $user['email'] ?> </td>
                            <td> <?= $user['contact'] ?> </td>
                            <td> <?= getPascalCaseString($user['address']['location']) . ", " . $districtArray[$user['address']['district']] ?>
                            </td>
                            <td> <?= getPascalCaseString($user['account_status']) ?> </td>
                            <td>
                                <abbr title="Show full details">
                                    <a href="/bookrack/admin/user-details/<?= $key ?>">
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

            <!-- table footer -->
            <tfoot id="table-foot">
                <?php
                if (sizeof($userList) == 0) {
                    ?>
                    <tr>
                        <td colspan="10"> No users found! </td>
                    </tr>
                    <?php
                }
                ?>
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

    <!-- current file script -->
    <script>
        var accountStateSelect = $("#user-account-state-select");
        const clearFilter = $("#clear-filter");

        clearFilter.hide();

        clearFilter.click(function () {
            accountStateSelect.val("all");
            filter();
        });

        accountStateSelect.change(function () {
            var accountType = accountStateSelect.val();
            filter();
        });

        filter = () => {
            $('.user-tr').show();
            if (accountStateSelect.val() == "verified") {
                $('.unverified-user-tr').hide();
            } else if (accountStateSelect.val() == "unverified") {
                $('.verified-user-tr').hide();
            }

            if (accountStateSelect.val() != "all") {
                clearFilter.show();
            }else{
                clearFilter.hide();
            }
        }
    </script>
</body>

</html>