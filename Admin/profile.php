<?php
$url = "profile";
$page = "profile";

$profileAdmin->fetch($adminId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> My Profile </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/profile.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <main class="d-flex flex-column mt-5 gap-3 main">
        <!-- heading -->
        <div class="d-flex flex-row align-items-center justify-content-between heading">
            <h4 class="fw-bold mt-4"> My Profile </h4>

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
        if ($profileAdmin->accountStatus != "verified") {
            ?>
            <div class="alert alert-danger mb-0" role="alert">
                Your account is not verified yet.
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
                if ($profileAdmin->accountStatus != "verified") {
                    ?>
                    <a href="/bookrack/admin/admin-profile/document"
                        class="d-flex flex-row gap-1 btn btn-outline-secondary align-items-center">
                        <i class="fa fa-upload"></i>
                        <p class="m-0"> Upload KYC </p>
                    </a>
                    <?php
                }
                ?>

                <!-- hide this after account has been verified -->
                <?php
                if ($profileAdmin->checkAccountVerificationEligibility()) {
                    ?>
                    <button class="btn btn-outline-primary" id="account-verification-btn">
                        Apply for Account Verification
                    </button>
                    <?php
                } ?>
            </div>
            <?php
        }
        ?>

        <!-- profile details -->
        <div class="d-flex flex-column flex-lg-row gap-4 article">
            <div class="<?php if ($tab != "view" && $tab != "edit" && $tab != "password")
                echo "d-none"; ?> profile w-100">
                <!-- my profile form -->
                <form action="/bookrack/admin/app/admin-update-profile.php" id="edit-profile-form" method="POST" class="<?php if ($tab != "view" && $tab != "edit")
                    echo "d-none"; ?> d-flex flex-column gap-3 my-profile" enctype="multipart/form-data">

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

                    <!-- admin id -->
                    <input type="hidden" name="edit-admin-id" id="edit-admin-id" value="<?=$adminId?>" class="form-control mb-3">

                    <!-- name -->
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <!-- first name -->
                        <div class="d-flex flex-column gap-2 w-100 first-name">
                            <label for="first-name"> First name </label>
                            <input type="text" name="first-name" class="form-control" id="first-name" value="<?php if ($profileAdmin->nameFirst != '')
                                echo ucfirst($profileAdmin->nameFirst); ?>" <?php if ($tab != "edit")
                                      echo "disabled"; ?>>
                        </div>

                        <!-- last name -->
                        <div class="d-flex flex-column gap-2 w-100 last-name">
                            <label for="last-name"> Last name </label>
                            <input type="text" name="last-name" class="form-control" id="last-name" value="<?php if ($profileAdmin->nameLast != '')
                                echo ucfirst($profileAdmin->nameLast); ?>" <?php if ($tab != "edit")
                                      echo "disabled"; ?>>
                        </div>
                    </div>

                    <!-- dob && gender-->
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <!-- dob -->
                        <div class="d-flex flex-column gap-2 w-100 first-name">
                            <label for="dob"> Date of birth </label>
                            <input type="date" name="dob" class="form-control" id="dob" value="<?php if ($profileAdmin->getDob() != '')
                                echo $profileAdmin->getDob(); ?>" <?php if ($tab != "edit")
                                      echo "disabled"; ?>>
                        </div>

                        <!-- gender -->
                        <div class="d-flex flex-column gap-2 w-100 last-name">
                            <label for="gender"> Gender </label>
                            <select name="gender" class="form-select" id="gender" <?php if ($tab != "edit")
                                echo "disabled"; ?>>
                                <?php
                                if ($profileAdmin->gender != "") {
                                    ?>
                                    <option value="<?= $profileAdmin->gender ?>" selected hidden>
                                        <?= ucfirst($profileAdmin->gender) ?>
                                    </option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="" selected hidden> Select gender </option>
                                    <?php
                                }
                                ?>
                                <option value="male"> Male </option>
                                <option value="female"> Female </option>
                                <option value="others"> Others </option>
                            </select>
                        </div>
                    </div>

                    <!-- email && phone number -->
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <!-- email -->
                        <div class="d-flex flex-column gap-2 w-100 first-name">
                            <label for="email"> Email </label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="<?= $profileAdmin->email ?>" disabled>
                        </div>

                        <!-- phone number -->
                        <div class="d-flex flex-column gap-2 w-100 last-name">
                            <label for="phone-number"> Phone Number </label>
                            <input type="text" name="phone-number" class="form-control" id="phone-number" value="<?php if ($profileAdmin->getPhoneNumber() != '')
                                echo str_replace('+977', '', $profileAdmin->getPhoneNumber()); ?>" <?php if ($tab != "edit")
                                        echo "disabled"; ?> minlength="10" maxlength="10">
                        </div>
                    </div>

                    <?php
                    if ($tab == "edit") {
                        ?>
                        <div class="action mt-2">
                            <button type="submit" name="admin-update-profile-btn" id="admin-update-profile-btn"
                                class="btn btn-brand px-5"> Update
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
                if ($profileAdmin->getDocumentFront() == "" || $profileAdmin->getDocumentBack() == "") {
                    ?>
                    <div class="border-top border-bottom py-2 mb-3">
                        <p class="m-0 text-danger"> You haven't submitted your document. </p>
                    </div>
                    <?php

                } else {
                    $profileAdmin->setDocumentUrl();
                }
                ?>

                <!-- alert to say admin to wait sometime to to get their kyc verified -->
                <?php
                if ($profileAdmin->getDocumentFront() != "" && $profileAdmin->getDocumentBack() != "" && $profileAdmin->accountStatus != "verified") {
                    ?>
                    <div class="border-top border-bottom py-2 mb-3">
                        <p class="m-0 text-secondary"> Note: Your documents are being verified. Please wait sometime.
                        </p>
                    </div>
                    <?php
                }
                ?>

                <!-- uploaded documents -->
                <?php
                if ($profileAdmin->getDocumentFront() != "" && $profileAdmin->getDocumentBack() != "") {
                    ?>
                    <div class="d-flex flex-column flex-lg-row gap-3 w-100 mb-3 kyc-container">
                        <div class="d-flex flex-column gap-2 front-side">
                            <label for=""> Front side </label>
                            <img src="<?= $profileAdmin->documentUrlFront ?>" class="pointer" alt="" loading="lazy">
                        </div>

                        <div class="d-flex flex-column gap-2 back-side">
                            <label for=""> Back side </label>
                            <img src="<?= $profileAdmin->documentUrlBack ?>" class="pointer" alt="" loading="lazy">
                        </div>
                    </div>
                    <?php
                }
                ?>

                <!-- kyc form -->
                <?php
                if ($tab == "document") {
                    ?>
                    <form action="/bookrack/admin/app/admin-kyc.php" id="document-form" method="POST"
                        class="d-flex flex-column gap-3 form kyc-form" enctype="multipart/form-data">
                        <!-- error message -->
                        <?php
                        if (isset($_SESSION['status'])) {
                            ?>
                            <p class="m-0 <?= $_SESSION['status'] ? "text-success" : "text-danger" ?>"> KYC has been
                                submitted.
                            </p>
                            <?php
                        }
                        ?>
                        <input type="hidden" name="document-admin-id" id="document-admin-id" value="<?=$adminId?>" class="form-control mb-3">

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
                            <button type="submit" name="admin-upload-kyc-btn" id="admin-upload-kyc-btn" class="btn btn-brand px-5"> Upload KYC </button>
                        </div>
                    </form>
                    <?php
                }
                ?>

            </div>
        </div>
    </main>

    <!-- popup alert -->
    <?php require_once __DIR__ . '/../sections/popup-alert.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <!-- popup script -->
    <script type="text/javascript" src="/bookrack/js/popup-alert.js"> </script>

    <!-- edit profile script -->
    <script>
        $(document).ready(function () {
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
            $('#phone-number').keydown(function () {
                var asciiValue = event.keyCode || event.which;
                if (asciiValue == 32) {
                    event.preventDefault();
                }
            });

            // account verification
            $('#account-verification-btn').click(function () {
                $.ajax({
                    url: '/bookrack/admin/app/account-verification.php',
                    type: "POST",
                    data : {adminId : '<?=$adminId?>'},
                    beforeSend: function () {
                        $('#account-verification-btn').html("Verifying...").prop('disabled', true);
                    }, success: function (response) {
                        if (response) {
                            $('#account-verification-btn').html("Account Verified").prop('disabled', true);
                            showPopupAlert("Account verified.");
                            location.reload();
                        } else {
                            // show popup alert
                            showPopupAlert("Your account couldn't be verified. Please try again.");
                            $('#account-verification-btn').html("Apply for Account Verification").prop('disabled', false);
                        }
                    },
                    error: function () {
                        showPopupAlert("Your account couldn't be verified due to an error. Please try again.");
                        $('#account-verification-btn').html("Apply for Account Verification").prop('disabled', false);
                    }
                });
            });

            // update user profile
            $('#edit-profile-form').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($('#edit-profile-form')[0]);
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/app/admin-update-profile.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#admin-update-profile-btn').html('Updating details...').prop('disabled', true);
                    },
                    success: function (response) {
                        msg = response ? "Account updated." : "Account couldn't be updated.";
                        showPopupAlert(msg);

                        if (response) {
                            setTimeout(function () {
                                window.location.href = '/bookrack/admin/admin-profile';
                            }, 2000);
                            $('#admin-update-profile-btn').html('Account updated').prop('disabled', true);
                        } else {
                            $('#admin-update-profile-btn').html('Update').prop('disabled', false);
                        }
                    }
                });
            });

            // document submission
            $('#document-form').submit(function (e) {
                e.preventDefault();
                var formData = new FormData($('#document-form')[0]);
                $.ajax({
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    url: "/bookrack/admin/app/admin-kyc.php",
                    beforeSend: function () {
                        $('#admin-upload-kyc-btn').html('Uploading KYC...').prop('disabled', true);
                    },
                    success: function (response) {
                        msg = response ? "KYC Uploaded." : "KYC couldn't be uploaded.";
                        showPopupAlert(msg);

                        if(response) {
                            $('#admin-upload-kyc-btn').html('KYC Uploaded').prop('disabled', true);
                            setTimeout(function () {
                                window.location.href = '/bookrack/admin/admin-profile';
                            }, 1000);
                        } else {
                            $('#admin-upload-kyc-btn').html('Upload KYC').prop('disabled', false);
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>