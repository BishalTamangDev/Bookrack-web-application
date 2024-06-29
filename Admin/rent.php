<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "rent";
$adminId = $_SESSION['bookrack-admin-id'];

// fetching the admin profile details
require_once __DIR__ . '/../admin/app/admin-class.php';
require_once __DIR__ . '/../app/functions.php';

$profileAdmin = new Admin();
$adminExists = $profileAdmin->checkAdminExistenceById($adminId);

if(!$adminExists)
    header("Location: /bookrack/admin/app/admin-signout.php");

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Rent </title>

    <?php require_once __DIR__ . '/../app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/nav.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php include 'nav.php'; ?>

    <!-- main content -->
    <main class="main">
        <!-- heading -->
        <p class="page-heading"> Rent History </p>

        <!-- cards -->
        <section class="section card-container">
            <!-- active rent -->
            <div class="card-v1">
                <p class="card-v1-title"> Active </p>
                <p class="card-v1-detail"> 207 </p>
            </div>

            <!-- completed rent -->
            <div class="card-v1">
                <p class="card-v1-title"> Completed </p>
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
                    <option value="0" selected hidden> Rent status </option>
                    <option value="1"> All </option>
                    <option value="2"> Active </option>
                    <option value="3"> Completed </option>
                </select>

                <!-- fine -->
                <select class="form-select" aria-label="select">
                    <option value="0" selected hidden> Fine Status </option>
                    <option value="1"> All </option>
                    <option value="2"> Fine </option>
                    <option value="3"> No fine </option>
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
                    <input type="text" placeholder="search user">
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
                    <th scope="col"> S.N. </th>
                    <th scope="col"> ISBN </th>
                    <th scope="col"> Title </th>
                    <th scope="col"> Rented By </th>
                    <th scope="col"> Issued Date </th>
                    <th scope="col"> Returned Date </th>
                    <th scope="col"> Fine </th>
                    <th scope="col"> Rent Status </th>
                    <th scope="col"> Action </th>
                </tr>
            </thead>

            <!-- body -->
            <tbody>
                <!-- dummy data -->
                <tr class="rent-row completed-row nofine-row">
                    <th scope="row"> 1 </th>
                    <td> 978-1-84356-028-9 </td>
                    <td>
                        <abbr title="Show book details">
                            <a href="/bookrack/admin/admin-book-details"> To Kill a Mockingbird </a>
                        </abbr>
                    </td>
                    <td>
                        <abbr title="Show user details">
                            <a href="/bookrack/admin/admin-user-details"> Bishal Tamang </a>
                        </abbr>
                    </td>
                    <td> 0000-00-00 </td>
                    <td> 1111-11-11 </td>
                    <td> - </td>
                    <td> Completed </td>
                    <td>
                        <abbr title="Show full details">
                            <a href="">
                                <i class="fa fa-eye"></i>
                            </a>
                        </abbr>
                    </td>
                </tr>

                <tr class="rent-row active-row nofine-row">
                    <th scope="row"> 2 </th>
                    <td> 978-0-596-52068-7 </td>
                    <td>
                        <abbr title="Show book details">
                            <a href="/bookrack/admin/admin-book-details"> 1984 </a>
                        </abbr>
                    </td>
                    <td>
                        <abbr title="Show user details">
                            <a href="/bookrack/admin/admin-user-details"> Rupak Dangi </a>
                        </abbr>
                    </td>
                    <td> 2222-22-22 </td>
                    <td> - </td>
                    <td> - </td>
                    <td> Active </td>
                    <td>
                        <abbr title="Show full details">
                            <a href="">
                                <i class="fa fa-eye"></i>
                            </a>
                        </abbr>
                    </td>
                </tr>

                <tr class="rent-row  completed-row fine-row">
                    <th scope="row"> 3 </th>
                    <td> 978-3-16-148410-0 </td>
                    <td>
                        <abbr title="Show book details">
                            <a href="/bookrack/admin/admin-book-details"> The Great Gatsby </a>
                        </abbr>
                    </td>
                    <td>
                        <abbr title="Show user details">
                            <a href="/bookrack/admin/admin-user-details"> Shristi Pradhan </a>
                        </abbr>
                    </td>
                    <td> 3333-33-33 </td>
                    <td> 4444-44-44 </td>
                    <td> Nrs. 175.00 </td>
                    <td> Completed </td>
                    <td>
                        <abbr title="Show full details">
                            <a href="">
                                <i class="fa fa-eye"></i>
                            </a>
                        </abbr>
                    </td>
                </tr>
            </tbody>

            <!-- footer -->
            <tfoot id="table-foot">
                <tr>
                    <td colspan="9"> No book rent history found! </td>
                </tr>
            </tfoot>
        </table>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../app/script-include.php'; ?>
</body>

</html>