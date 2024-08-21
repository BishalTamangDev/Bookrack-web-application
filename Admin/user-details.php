<?php
$url = "user-details";
$page = "user-detail";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");

// including user class
require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../classes/book.php';
require_once __DIR__ . '/../classes/admin.php';
require_once __DIR__ . '/../functions/district-array.php';

$selectedUser = new User();
$bookObj = new Book();

$userExists = $selectedUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/admin/admin-users");

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

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/user-details.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <!-- main content -->
    <main class="main mt-4">
        <!-- heading -->
        <p class="fw-bold page-heading mt-5"> User Information </p>

        <!-- user detail -->
        <section class="section d-flex flex-column gap-4 mt-3 user-detail-div">
            <!-- photo section -->
            <div class="photo-account-status">
                <div class="photo-div">
                    <?php
                    $selectedUser->setPhotoUrl();
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
                <div class="d-flex flex-row flex-wrap gap-4 all-details">
                    <!-- name -->
                    <div class="d-flex flex-column flex-md-row row-gap-4 w-100">
                        <div class="w-100 w-md-50">
                            <p class="m-0 mb-2"> First Name </p>
                            <p class="m-0 fw-semibold"> <?= ucfirst($selectedUser->name['first']) ?> </p>
                        </div>

                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Last Name </p>
                            <p class="m-0 fw-semibold"> <?= ucfirst($selectedUser->name['last']) ?> </p>
                        </div>
                    </div>

                    <!-- gender & dob -->
                    <div class="d-flex flex-column flex-md-row row-gap-4 w-100">
                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Gender </p>
                            <p class="m-0 fw-semibold"> <?= ucfirst($selectedUser->gender) ?> </p>
                        </div>

                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Date of Birth </p>
                            <p class="m-0 fw-semibold"> <?php
                            $dob = $selectedUser->getDob();
                            echo $dob != '' ? $dob : "-";
                            ?> </p>
                        </div>
                    </div>

                    <!-- email & phone number -->
                    <div class="d-flex flex-column flex-md-row row-gap-4 w-100">
                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Email Address </p>
                            <p class="m-0 fw-semibold"> <?= $selectedUser->email ?> </p>
                        </div>

                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Phone Number </p>
                            <p class="m-0 fw-semibold"> <?php $phoneNumber = $selectedUser->getPhoneNumber();
                            echo $phoneNumber != "" ? $phoneNumber : "-";
                            ?> </p>
                        </div>
                    </div>

                    <!-- district & municipality -->
                    <div class="d-flex flex-column flex-md-row row-gap-4 w-100">
                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> District </p>
                            <p class="m-0 fw-semibold"> <?= ucwords($selectedUser->getAddressDistrict()) ?> </p>
                        </div>

                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Municipality </p>
                            <p class="m-0 fw-semibold"> <?= ucwords($selectedUser->getAddressMunicipality()) ?> </p>
                        </div>
                    </div>

                    <!-- ward & tole/ village -->
                    <div class="d-flex flex-column flex-md-row row-gap-4 w-100">
                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Ward </p>
                            <p class="m-0 fw-semibold"> <?= $selectedUser->getAddressWard() ?> </p>
                        </div>

                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Tole/ Village </p>
                            <p class="m-0 fw-semibold"> <?= ucwords($selectedUser->getAddressToleVillage()) ?> </p>
                        </div>
                    </div>

                    <!-- account status -->
                    <div class="d-flex flex-column flex-md-row row-gap-4 w-100">
                        <div class="w-100 w-md-50">
                            <p class="m-0  mb-2"> Account Status </p>
                            <p class="m-0 fw-semibold"> <?= ucfirst($selectedUser->accountStatus) ?> </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- account action -->
        <div class="d-flex flex-row gap-2 flex-wrap action mt-4">
            <?php
            if ($selectedUser->accountStatus == 'on-hold') {
                ?>
                <button class="btn btn-success" id="verify-account-btn"> Verify Account </button>
                <button class="btn btn-outline-danger" id="unverify-account-btn"> Unverify Account </button>
                <?php
            } elseif ($selectedUser->accountStatus == 'verified') {
                ?>
                <button class="btn btn-outline-danger" id="unverify-account-btn"> Unverify Account </button>
                <?php
            } elseif ($selectedUser->accountStatus == 'unverified') {
                ?>
                <button class="btn btn-success" id="verify-account-btn"> Verify Account </button>
                <?php
            }
            ?>
        </div>

        <!-- contributed books -->
        <h3 class="section fw-bold mt-5 mb-4 brand-color"> Contributed Books </h3>

        <table class="table">
            <thead>
                <tr>
                    <th> # </th>
                    <th> Title </th>
                    <th> Price </th>
                    <th> Status </th>
                </tr>
            </thead>

            <tbody id="contribution-table-body">
                <tr>
                    <td colspan="4">
                        <div class="d-flex flex-row gap-2 table-loading-gif-container">
                            <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="" style="width: 20px;">
                            <p class="m-0 text-secondary"> Fetching books </p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <!-- popup alert -->
    <?php require_once __DIR__ . '/../sections/popup-alert.php'; ?>

    <!-- popup script -->
    <script type="text/javascript" src="/bookrack/js/popup-alert.js"> </script>

    <script>
        $(document).ready(function () {
            // fetch user's books
            function fetchBookOfUser() {
                $.ajax({
                    url: "/bookrack/admin/sections/fetch-book-of-user.php",
                    data: { userId: '<?= $userId ?>' },
                    type: "POST",
                    success: function (data) {
                        $('#contribution-table-body').html(data);
                    }
                });
            }

            fetchBookOfUser();

            // verify user
            $('#verify-account-btn').click(function () {
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/app/verify-user.php",
                    data: { userId: '<?= $userId ?>' },
                    beforeSend: function () {
                        $('#verify-account-btn').html("Verifying account...").prop('disabled', true);
                    },
                    success: function (response) {
                        if (response) {
                            showPopupAlert("Account verified.");
                            $('#unverify-account-btn').fadeOut();
                            $('#verify-account-btn').html("Account Verified").prop('disabled', true);
                        } else {
                            showPopupAlert("Account couldn't be verified.");
                            $('#verify-account-btn').html("Verify Account").prop('disabled', false);
                        }
                    }
                });
            });

            // unverify user
            $('#unverify-account-btn').click(function () {
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/app/unverify-user.php",
                    data: { userId: '<?= $userId ?>' },
                    beforeSend: function () {
                        $('#unverify-account-btn').html("Unverifying account...").prop('disabled', true);
                    },
                    success: function (response) {
                        if (response) {
                            showPopupAlert("Account unverified.");
                            $('#verify-account-btn').fadeOut();
                            $('#unverify-account-btn').html("Account unverified").prop('disabled', true);
                        } else {
                            showPopupAlert("Account couldn't be unverified.");
                            $('#unverify-account-btn').html("Unverify Account").prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>