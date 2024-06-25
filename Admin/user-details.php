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

// including user class
require_once __DIR__ . '/../../bookrack/app/user-class.php';
require_once __DIR__ . '/../../bookrack/app/book-class.php';

$selectedUser = new User();
$userObj = new User();
$bookObj = new Book();

$status = $selectedUser->fetch($userId);
if (!$status) {
    header("Location: /bookrack/admin/admin-users");
}
$selectedUser->setUserId($userId);

$bookList = $bookObj->fetchBookByUserId($userId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> User Detail : <?= getFormattedName($selectedUser->getFirstName(), $selectedUser->getLastName()) ?> </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
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
    <link rel="stylesheet" href="/bookrack/assets/css/admin/user-details.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php
    include 'nav.php';
    ?>

    <!-- main content -->
    <main class="main">
        <!-- heading -->
        <p class="fw-bold page-heading">
            <?= getFormattedName($selectedUser->getFirstName(), $selectedUser->getLastName()) ?> </p>

        <!-- user detail -->
        <section class="section d-flex flex-column flex-lg-row gap-5 user-detail-div">
            <!-- photo section -->
            <div class="photo-account-status">
                <div class="photo-div">
                    <img src="<?= $selectedUser->getProfilePictureImageUrl(); ?>" alt="profile picture">
                </div>
            </div>

            <!-- details -->
            <div class="d-flex flex-column pt-2 gap-1 details">
                <!-- all details -->
                <div class="d-flex flex-row flex-wrap gap-5 all-details">
                    <!-- email, gender, dob -->
                    <div class="d-flex flex-column gap-2 email-gender-dob">
                        <p class="f-reset"> <?= $selectedUser->getEmail() ?> </p>
                        <p class="f-reset"> <?php
                        if ($selectedUser->getGender() == 0)
                            echo "Male";
                        elseif ($selectedUser->getGender() == 1)
                            echo "Female";
                        else
                            echo "Others";
                        ?>
                        </p>
                        <p class="f-reset"> <?= $selectedUser->getDob() ?> </p>
                    </div>

                    <!-- contact, address -->
                    <div class="d-flex flex-column gap-2 contact-address">
                        <!-- contact -->
                        <div class="d-flex flex-row contact">
                            <div class="title">
                                <p class="f-reset"> Contact </p>
                            </div>

                            <div class="data">
                                <p class="f-reset"> 9874521459 </p>
                            </div>
                        </div>

                        <!-- address -->
                        <div class="d-flex flex-row address">
                            <div class="title">
                                <p class="f-reset"> Address </p>
                            </div>

                            <div class="data">
                                <p class="f-reset">
                                    <?= getPascalCaseString($selectedUser->getAddressLocation()) . ", " . $districtArray[$selectedUser->getAddressDistrict()] ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- rent-contribution -->
                    <div class="d-flex flex-row gap-3 rent-contribution">
                        <!-- rent -->
                        <div class="d-flex flex-column align-items-center border px-4 py-2 rounded rent">
                            <div class="data">
                                <p class="f-reset fw-bold fs-3"> <?= "-" ?> </p>
                            </div>
                            <div class="title">
                                <p class="f-reset fs-6"> Books Rented </p>
                            </div>
                        </div>

                        <!-- contribution -->
                        <div class="d-flex flex-column align-items-center border px-4 py-2 rounded contribution">
                            <div class="data">
                                <p class="f-reset fw-bold fs-3"> <?= count($bookList) ?> </p>
                            </div>
                            <div class="title">
                                <p class="f-reset fs-6"> Book Contribution </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- contributed books -->
        <h6 class="section fw-bold text-danger"> Contributed Books </h6>

        <section class="d-flex flex-row flex-wrap contributed-book-container">
            <?php
            foreach ($bookList as $key => $book) {
                $bookObj->setCoverPhoto($book['photo']['cover']);
                ?>
                <div class="contributed-book">
                    <div class="image-div">
                        <img src="<?= $bookObj->getCoverPhotoUrl() ?>" alt="">
                    </div>

                    <div class="detail-div">
                        <p class="title"> <?= $book['title'] ?> </p>
                        <div class="d-flex flex-row flex-wrap genre">
                            <?php
                            foreach ($book['genre'] as $genre) {
                                ?>
                                <p class="genre"> <?= $genre ?></p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </section>

        <h6 class="section fw-bold text-danger"> Rent History </h6>

        <!-- book table -->
        <table class="table table-striped user-table">
            <!-- header -->
            <thead>
                <tr>
                    <th scope="col"> S.N. </th>
                    <th scope="col"> Title </th>
                    <th scope="col"> Genre </th>
                    <th scope="col"> Issued Date </th>
                    <th scope="col"> Returned Date </th>
                    <th scope="col"> Rent Status </th>
                    <th scope="col"> Action </th>
                </tr>
            </thead>

            <!-- body -->
            <tbody>
                <!-- dummy data -->
                <tr class="book-row on-rent-row on-stock-row">
                    <th scope="row"> 1 </th>
                    <td>
                        <abbr title="Show book details"> To Kill a Mockingbird </abbr>
                    </td>
                    </td>
                    <td> Fiction, Classic, Historical </td>
                    <td> 0000-00-00 </td>
                    <td> 0000-00-00 </td>
                    <td> Completed </td>
                    <td>
                        <abbr title="Show full details">
                            <i class="fa fa-eye"></i>
                        </abbr>
                    </td>
                </tr>

                <tr class="book-row on-rent-row on-stock-row">
                    <th scope="row"> 2 </th>
                    <td>
                        <abbr title="Show book details"> 1984 </abbr>
                    </td>
                    <td> Dystopian, Science Fiction, Political Fiction </td>
                    <td> 0000-00-00 </td>
                    <td> - </td>
                    <td> On rent </td>
                    <td>
                        <abbr title="Show full details">
                            <i class="fa fa-eye"></i>
                        </abbr>
                    </td>
                </tr>
            </tbody>

            <!-- footer -->
            <tfoot id="table-foot">
                <tr>
                    <td colspan="10"> No book rent history found! </td>
                </tr>
            </tfoot>
        </table>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../../bookrack/app/jquery-js-bootstrap-include.php';?>
</body>

</html>