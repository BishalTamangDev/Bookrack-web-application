<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "user-details";
$adminId = $_SESSION['bookrack-admin-id'];

// fetching the admin profile details
require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

$profileAdmin = new Admin();
$adminExists = $profileAdmin->fetch($adminId);

if (!$adminExists)
    header("Location: /bookrack/admin/signin");

if ($profileAdmin->getAccountStatus() != "verified")
    header("Location: /bookrack/admin/admin-profile");

// including user class
require_once __DIR__ . '/../../bookrack/app/user-class.php';
require_once __DIR__ . '/../../bookrack/app/book-class.php';

$selectedUser = new User();
$bookObj = new Book();

$userExists = $selectedUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/admin/admin-users");

$selectedUser->setUserId($userId);

$bookList = $bookObj->fetchBookByUserId($userId);

$fullName = $selectedUser->getFullName();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> User Detail :
        <?php echo $fullName != " " ? $fullName : "Unverifed User"; ?>
    </title>

    <?php require_once __DIR__ . '/../../bookrack/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/user-details.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php include 'nav.php'; ?>

    <!-- main content -->
    <main class="main">
        <!-- heading -->
        <p class="fw-bold page-heading">
            <?php echo $fullName != " " ? $fullName : "Unverifed User"; ?>
        </p>

        <!-- user detail -->
        <section class="section d-flex flex-column flex-lg-row gap-5 user-detail-div">
            <!-- photo section -->
            <div class="photo-account-status">
                <div class="photo-div">
                    <?php
                    $photoUrl = $selectedUser->photoUrl;

                    if ($photoUrl != "") {
                        ?>
                        <img src="<?= $photoUrl ?>" alt="profile picture">
                        <?php
                    } else {
                        ?>
                        <img src="/bookrack/assets/images/blank-user.jpg" alt="">
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- details -->
            <div class="d-flex flex-column pt-2 gap-1 details">
                <!-- all details -->
                <div class="d-flex flex-row flex-wrap gap-5 all-details">
                    <!-- email, gender, dob -->
                    <div class="d-flex flex-column gap-2 email-gender-dob">
                        <p class="m-0"> Email: <?= $selectedUser->email ?> </p>
                        <p class="m-0"> Gender : <?php
                        if ($selectedUser->gender == 0)
                            echo "Male";
                        elseif ($selectedUser->gender == 1)
                            echo "Female";
                        elseif ($selectedUser->gender == 2)
                            echo "Others";
                        else
                            echo "-";
                        ?>
                        </p>
                        <p class="m-0"> Date of Birth :
                            <?php
                            $dob = $selectedUser->getDob();
                            echo $dob != '' ? $dob : "-";
                            ?>
                        </p>
                    </div>

                    <!-- contact, address -->
                    <div class="d-flex flex-column gap-2 contact-address">
                        <!-- contact -->
                        <div class="d-flex flex-row contact">
                            <div class="title">
                                <p class="m-0"> Phone Number </p>
                            </div>

                            <div class="data">
                                <p class="m-0"> <?php $phoneNumber = $selectedUser->getPhoneNumber();
                                echo $phoneNumber != "" ? $phoneNumber : "-";
                                ?> </p>
                            </div>
                        </div>

                        <!-- address -->
                        <div class="d-flex flex-row address">
                            <div class="title">
                                <p class="m-0"> Address </p>
                            </div>

                            <div class="data">
                                <p class="m-0">
                                    <?= $selectedUser->getFullAddress() ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- rent-contribution -->
                    <div class="d-flex flex-row gap-3 rent-contribution">
                        <!-- rent -->
                        <div class="d-flex flex-column align-items-center border px-4 py-2 rounded rent">
                            <div class="data">
                                <p class="m-0 fw-bold fs-3"> <?= "0" ?> </p>
                            </div>
                            <div class="title">
                                <p class="m-0 fs-6"> Books Rented </p>
                            </div>
                        </div>

                        <!-- contribution -->
                        <div class="d-flex flex-column align-items-center border px-4 py-2 rounded contribution">
                            <div class="data">
                                <p class="m-0 fw-bold fs-3"> <?= sizeof($bookList) ?> </p>
                            </div>
                            <div class="title">
                                <p class="m-0 fs-6"> Book Contribution </p>
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
            if (sizeof($bookList) > 0) {
                foreach ($bookList as $book) {
                    ?>
                    <div class="contributed-book">
                        <div class="image-div">
                            <img src="<?= $book->photoUrl['cover'] ?>" alt="" loading="lazy">
                        </div>

                        <div class="detail-div">
                            <p class="title fw-bold"> <?= ucWords($book->title) ?> </p>
                            <div class="d-flex flex-row flex-wrap genre">
                                <?php
                                foreach ($book->genre as $genre) {
                                    ?>
                                    <p class="genre"> <?= $genre . ', ' ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <p class="m-0 text-secondary"> No contribution yet! </p>
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
    <?php require_once __DIR__ . '/../../bookrack/app/script-include.php'; ?>
</body>

</html>