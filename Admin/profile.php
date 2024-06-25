<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-admin-id'])) {
    header("Location: /bookrack/admin/admin-signin");
}

require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

$profileAdmin = new Admin();

$profileAdmin->setId($_SESSION['bookrack-admin-id']);
$profileAdmin->fetch($_SESSION['bookrack-admin-id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> My Profile </title>

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
    <link rel="stylesheet" href="/bookrack/assets/css/admin/dashboard.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/profile.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php
    include 'nav.php';
    ?>

    <main class="d-flex flex-column gap-3 main">
        <!-- heading -->
        <div class="d-flex flex-row align-items-center justify-content-between heading">
            <h4 class="fw-bold"> My Profile </h4>

            <?php
            if ($tab == "edit" || $tab == "password" || $tab == "document") {
                ?>
                <div class="d-flex flex-row gap-3 align-items-center action">
                    <!-- reset -->
                    <abbr title="Reset form">
                        <a href="/bookrack/admin/admin-profile/<?= $tab ?>" class="text-secondary">
                            <i class="fa fa-undo fs-5"></i>
                        </a>
                    </abbr>

                    <!-- cancel -->
                    <abbr title="Cancel">
                        <a href="/bookrack/admin/admin-profile" class="text-secondary">
                            <i class="fa fa-multiply fs-3"></i>
                        </a>
                    </abbr>
                </div>
                <?php
            }
            ?>
        </div>

        <!-- alert for account verification -->
        <?php
        if ($profileAdmin->getAccountStatus() != "verified") {
            ?>
            <div class="alert alert-danger mb-0" role="alert">
                Your account is not verified yet. Please wait sometime.
            </div>
            <?php
        }
        ?>

        <!-- actions :: edit || change password -->
        <?php
        if ($tab == "view") {
            ?>
            <div class="d-flex flex-row gap-2 action">
                <a href="/bookrack/admin/admin-profile/edit"
                    class="d-flex flex-row gap-1 btn btn-outline-secondary align-items-center">
                    <i class="fa fa-edit"></i>
                    <p class="m-0"> Edit profile </p>
                </a>

                <?php

                if ($profileAdmin->getAccountStatus() != "verified") {
                    ?>
                    <a href="/bookrack/admin/admin-profile/document"
                        class="d-flex flex-row gap-1 btn btn-outline-secondary align-items-center">
                        <i class="fa fa-upload"></i>
                        <p class="m-0"> Upload KYC </p>
                    </a>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>


        <!-- profile details -->
        <div class="d-flex flex-column flex-lg-row gap-4 article">
            <div class="<?php if ($tab != "view" && $tab != "edit" && $tab != "password")
                echo "d-none"; ?> profile w-100">
                <!-- my profile form -->
                <form action="/bookrack/admin/app/admin-update-profile.php" method="POST" class="<?php if ($tab != "view" && $tab != "edit")
                    echo "d-none"; ?> d-flex flex-column gap-3 my-profile" enctype="multipart/form-data">

                    <!-- status message section -->
                    <?php
                    if (isset($_SESSION['status'])) {
                        ?>
                        <p class="m-0 <?= $_SESSION['status'] ? 'text-success' : 'text-danger' ?>">
                            <?= $_SESSION['status-message'] ?>
                        </p>
                        <?php
                    }
                    ?>

                    <!-- profile picture -->
                    <?php
                    if ($tab == "edit") {
                        ?>
                        <div class="d-flex flex-column gap-3">
                            <label for="profile-picture"> Change profile picture </label>
                            <input type="file" name="profile-picture" class="form-control" accept="image/*"
                                id="profile-picture">
                        </div>
                        <?php
                    }
                    ?>

                    <!-- name -->
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <!-- first name -->
                        <div class="d-flex flex-column gap-2 w-100 first-name">
                            <label for="first-name"> First name </label>
                            <input type="text" name="first-name" class="form-control" id="first-name" value="<?php if ($profileAdmin->getFirstName() != '')
                                echo getPascalCaseString($profileAdmin->getFirstName()); ?>" <?php if ($tab != "edit")
                                      echo "disabled"; ?> required>
                        </div>

                        <!-- last name -->
                        <div class="d-flex flex-column gap-2 w-100 last-name">
                            <label for="last-name"> Last name </label>
                            <input type="text" name="last-name" class="form-control" id="last-name" value="<?php if ($profileAdmin->getLastName() != '')
                                echo getPascalCaseString($profileAdmin->getLastName()); ?>" <?php if ($tab != "edit")
                                      echo "disabled"; ?> required>
                        </div>
                    </div>

                    <!-- dob && gender-->
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <!-- dob -->
                        <div class="d-flex flex-column gap-2 w-100 first-name">
                            <label for="dob"> Date of birth </label>
                            <input type="date" name="dob" class="form-control" id="dob" value="<?php if ($profileAdmin->getDob() != '')
                                echo $profileAdmin->getDob(); ?>" <?php if ($tab != "edit")
                                      echo "disabled"; ?> required>
                        </div>

                        <!-- gender -->
                        <div class="d-flex flex-column gap-2 w-100 last-name">
                            <label for="gender"> Gender </label>
                            <select name="gender" class="form-select" id="gender" <?php if ($tab != "edit")
                                echo "disabled"; ?> required>
                                <?php
                                if ($profileAdmin->getGender() != "") {
                                    ?>
                                    <option value="<?= $profileAdmin->getGender() ?>" selected hidden>
                                        <?php
                                        if ($profileAdmin->getGender() == 0)
                                            echo "Male";
                                        elseif ($profileAdmin->getGender() == 1)
                                            echo "Female";
                                        else
                                            echo "Others";
                                        ?>
                                    </option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="" selected hidden> Select gender </option>
                                    <?php
                                }
                                ?>
                                <option value="0"> Male </option>
                                <option value="1"> Female </option>
                                <option value="2"> Others </option>
                            </select>
                        </div>
                    </div>

                    <!-- email && contact -->
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <!-- email -->
                        <div class="d-flex flex-column gap-2 w-100 first-name">
                            <label for="email"> Email </label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="<?= $profileAdmin->getEmail() ?>" disabled>
                        </div>

                        <!-- contact -->
                        <div class="d-flex flex-column gap-2 w-100 last-name">
                            <label for="contact"> Contact </label>
                            <input type="text" name="contact" class="form-control" id="contact" value="<?php if ($profileAdmin->getContact() != '')
                                echo $profileAdmin->getContact(); ?>" <?php if ($tab != "edit")
                                      echo "disabled"; ?> minlength="10" maxlength="10" required>
                        </div>
                    </div>

                    <?php
                    if ($tab == "edit") {
                        ?>
                        <div class="action mt-2">
                            <button type="submit" name="admin-update-profile-btn" class="btn btn-warning  px-5"> Update
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                </form>

                <!-- password form -->
                <form action="" class="<?php if ($tab != "password")
                    echo "d-none"; ?> d-flex flex-column gap-3 form mt-4" id="password-form">
                    <!-- heading -->
                    <h5 class="m-0 fw-bold mb-3 text-secondary"> Change Password </h5>

                    <div class="form-floating">
                        <input type="password" name="old-password" class="form-control" id="old-password"
                            placeholder="*********" required>
                        <label for="old-password"> Old password </label>
                    </div>

                    <div class="form-floating">
                        <input type="password" name="new-password" class="form-control" id="new-password"
                            placeholder="*********" required>
                        <label for="new-password"> New password </label>
                    </div>

                    <div class="form-floating">
                        <input type="password" name="new-password-confirmation" class="form-control"
                            id="new-password-confirmation" placeholder="*********" required>
                        <label for="new-password-confirmation"> Enter new password again </label>
                    </div>

                    <div class="d-flex flex-row gap-2 align-items-center pointer" id="password-toggler">
                        <i class="fa fa-eye"></i>
                        <p class="m-0"> Show password </p>
                    </div>

                    <div class="action">
                        <button type="submit" class="btn btn-warning px-5"> Submit </button>
                    </div>
                </form>
            </div>

            <hr class="d-lg-none">

            <!-- documents -->
            <div class="<?php if ($tab != "document" && $tab != "view")
                echo "d-none"; ?> d-flex flex-column kyc w-100">
                <!-- heading -->
                <h5 class="m-0 fw-bold mb-3 text-secondary"> Documents </h5>

                <!-- alert for uploading documents -->
                <?php
                if ($profileAdmin->getKycFront() == "" || $profileAdmin->getKycBack() == "") {
                    ?>
                    <div class="border-top border-bottom py-2 mb-3">
                        <p class="m-0 text-danger"> You haven't submitted your document. </p>
                    </div>
                    <?php

                }
                ?>

                <!-- alert to say admin to wait sometime to to get their kyc verified -->
                <?php
                if ($profileAdmin->getKycFront() != "" && $profileAdmin->getKycBack() != "" && $profileAdmin->getAccountStatus() != "verified") {
                    ?>
                    <div class="border-top border-bottom py-2 mb-3">
                        <p class="m-0 text-secondary"> Not: Your documents are being verified. Please wait sometime. </p>
                    </div>
                    <?php
                }
                ?>

                <!-- uploaded documents -->
                <?php
                if ($profileAdmin->getKycFront() != "" && $profileAdmin->getKycBack() != "") {
                    ?>
                    <div class="d-flex flex-column flex-lg-row gap-3 w-100 mb-3 kyc-container">
                        <div class="d-flex flex-column gap-2 front-side">
                            <label for=""> Front side </label>
                            <img src="<?= $profileAdmin->getKycFrontUrl() ?>" class="pointer" alt="" loading="lazy">
                        </div>

                        <div class="d-flex flex-column gap-2 back-side">
                            <label for=""> Back side </label>
                            <img src="<?= $profileAdmin->getKycBackUrl() ?>" class="pointer" alt="" loading="lazy">
                        </div>
                    </div>
                    <?php
                }
                ?>

                <!-- kyc form -->
                <?php
                if ($tab == "document") {
                    ?>
                    <form action="/bookrack/admin/app/admin-kyc.php" method="POST"
                        class="d-flex flex-column gap-3 form kyc-form" enctype="multipart/form-data">
                        <!-- error message -->
                        <?php
                        if (isset($_SESSION['status'])) {
                            ?>
                            <p class="m-0 <?= $_SESSION['status'] ? "text-success" : "text-danger" ?>"> KYC has been submitted.
                            </p>
                            <?php
                        }
                        ?>

                        <div class="d-flex flex-column gap-2 w-100">
                            <label for="kyc-front"> Front side </label>
                            <input type="file" name="kyc-front" class="form-control" id="kyc-front" accept="image/*"
                                required>
                        </div>

                        <div class="d-flex flex-column gap-2 w-100">
                            <label for="kyc-back">Back side</label>
                            <input type="file" name="kyc-back" class="form-control" id="kyc-back" accept="image/*" required>
                        </div>

                        <div class="action">
                            <button type="submit" name="admin-upload-kyc-btn" class="btn btn-warning px-5"> Sumbit </button>
                        </div>
                    </form>
                    <?php
                }
                ?>

            </div>
        </div>
    </main>

    <!-- unset session status & message -->
    <?php
    unset($_SESSION['status']);
    unset($_SESSION['status-message']);
    ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../../bookrack/app/jquery-js-bootstrap-include.php'; ?>

    <!-- edit profile script -->
    <script>
        // first name
        $('#first-name').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });

        // last name
        $('#last-name').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });

        // contact name
        $('#contact').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>