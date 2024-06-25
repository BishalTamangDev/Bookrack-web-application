<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/");
}

$url = "profile";

require_once __DIR__ . '/../bookrack/app/user-class.php';
require_once __DIR__ . '/../bookrack/app/book-class.php';
require_once __DIR__ . '/../bookrack/app/wishlist-class.php';

require_once __DIR__ . '/../bookrack/app/functions.php';

$profileUser = new User();

$profileUser->setUserId($_SESSION['bookrack-user-id']);

// get user details
$userFound = $profileUser->fetch($profileUser->getUserId());

if (!$userFound) {
    header("Location: /bookrack/signout");
}

$bookObj = new Book();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title>
        <?php
        if ($tab == "edit-profile")
            echo "Edit profile";
        elseif ($tab == "kyc")
            echo "KYC";
        elseif ($tab == "my-books")
            echo "My books";
        elseif ($tab == "wishlist")
            echo "Wishlist";
        elseif ($tab == "requested-books")
            echo "Requested books";
        elseif ($tab == "earning")
            echo "Earning";
        else
            echo "My profile";
        ?>
    </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.min.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/navbar.css">
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/header.css">
    <link rel="stylesheet" href="/bookrack/assets/css/footer.css">
    <link rel="stylesheet" href="/bookrack/assets/css/book.css">
    <link rel="stylesheet" href="/bookrack/assets/css/profile.css">
</head>

<body>
    <!-- header -->
    <?php
    include 'header.php';
    ?>

    <!-- main -->
    <main class="d-flex d-column flex-lg-row gap-lg-4 container main">
        <!-- aside :: profile detail  -->
        <aside class="aside gap-3" id="aside">
            <!-- profile section -->
            <section class="d-flex flex-column p-3 py-4 mb-4 gap-3 profile-section">
                <!-- profile picture -->
                <div class="d-flex flex-column gap-2 align-items-center profile-top">
                    <div class="profile-image">
                        <?php
                        if ($profileUser->getProfilePicture() == "") {
                            ?>
                            <img src="/bookrack/assets/images/blank-user.jpg" alt="profile picture">
                            <?php
                        } else {
                            ?>
                            <img src="<?= $profileUser->getProfilePictureImageUrl() ?>" alt="profile picture">
                            <?php
                        }
                        ?>
                    </div>

                    <div class="d-flex flex-row gap-2 align-items-center profile name-status">
                        <p class="m-0 text-secondary">
                            <?php echo $profileUser->getFirstName() == "" ? $profileUser->getEmail() : getPascalCaseString($profileUser->getFirstName()) . " " . getPascalCaseString($profileUser->getLastName()); ?>
                        </p>
                        <?php
                        if ($profileUser->getAccountStatus() == "verified") {
                            ?>
                            <div class="status-div bg-success"></div>
                            <?php
                        } else {
                            ?>
                            <div class="status-div bg-danger"></div>
                            <?php
                        } ?>
                    </div>

                </div>

                <!-- bottom -->
                <div class="d-flex flex-column gap-2 profile-bottom">
                    <!-- address -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-map-pin"></i>
                            <span> From </span>
                        </div>
                        <div class="data-div">
                            <p class="f-reset">
                                <?php echo $profileUser->getAddressLocation() != "" ? getPascalCaseString($profileUser->getAddressLocation()) . ", " . $districtArray[$profileUser->getAddressDistrict()] : "-"; ?>
                            </p>
                        </div>
                    </div>

                    <?php
                    // extract only the date from joined date
                    $fullDateTime = $profileUser->getJoinedDate();
                    $dateTime = new DateTime($fullDateTime);
                    $dateOnly = $dateTime->format('Y-m-d');
                    ?>

                    <!-- membership -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-user"></i>
                            <span> Member since </span>
                        </div>
                        <div class="data-div">
                            <p class="f-reset"> <?php echo $profileUser->getJoinedDate() != "" ? $dateOnly : "-"; ?>
                            </p>
                        </div>
                    </div>

                    <!-- stat -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-pie-chart"></i>
                            <span>Stat</span>
                        </div>
                    </div>

                    <!-- books offered -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-none"></i>
                            <span>Books offered</span>
                        </div>

                        <?php
                        $offeredBookCount = count($bookObj->fetchBookByUserId($profileUser->getUserId()));
                        ?>

                        <div class="data-div">
                            <p class="f-reset fw-bold"> <?= $offeredBookCount ?> </p>
                        </div>
                    </div>

                    <!-- books offered -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-none"></i>
                            <span>Books consumed </span>
                        </div>

                        <div class="data-div">
                            <p class="f-reset fw-bold"> <?= "-" ?> </p>
                        </div>
                    </div>
                </div>

                <button class="btn" id="edit-profile-btn"
                    onclick="window.location.href='/bookrack/profile/edit-profile'"> Edit </button>
            </section>
        </aside>

        <!-- article -->
        <article class="article bg-md-success mb-4 bg-sm-danger">
            <!-- account state notification note div -->
            <?php
            if ($profileUser->getAccountStatus() != "verified") {
                ?>
                <div class="alert alert-danger mb-4" role="alert">
                    Your account is still not verified. Make sure you have provided all your details. If you have provided
                    every details already, please wait sometime to get your account verified.
                </div>
                <?php
            }
            ?>

            <!-- tab -->
            <section class="d-flex flex-row flex-wrap gap-3 gap-md-3 mt-1 mb-3 tab-section">
                <!-- profile tab -->
                <div class="tab">
                    <p onclick="window.location.href='/bookrack/profile/view-profile'"> MY PROFILE </p>
                    <div
                        class="indicator <?php echo ($tab == "view-profile" || $tab == "edit-profile" || $tab == "password-change" || $tab == "kyc") ? "active" : "inactive"; ?>">
                    </div>
                </div>

                <!-- my books tab -->
                <div class="tab">
                    <p onclick="window.location.href='/bookrack/profile/my-books'"> MY BOOKS </p>
                    <div class="indicator <?php echo ($tab == "my-books") ? "active" : "inactive"; ?>"></div>
                </div>

                <!-- wihslist tab -->
                <div class="tab">
                    <p onclick="window.location.href='/bookrack/profile/wishlist'"> WISHLIST </p>
                    <div class="indicator <?php echo ($tab == "wishlist") ? "active" : "inactive"; ?>"></div>
                </div>

                <!-- requested books tab -->
                <div class="tab">
                    <p onclick="window.location.href='/bookrack/profile/requested-books'"> REQUESTED BOOKS </p>
                    <div class="indicator <?php echo ($tab == "requested-books") ? "active" : "inactive"; ?>"></div>
                </div>

                <!-- earning tab -->
                <div class="tab">
                    <p onclick="window.location.href='/bookrack/profile/earning'"> EARNING </p>
                    <div class="indicator <?php echo ($tab == "earning") ? "active" : "inactive"; ?>"></div>
                </div>
            </section>

            <!-- contents -->
            <section class="d-flex flex-column gap-3 contents">
                <!-- profile :: view & edit -->
                <?php if ($tab == "edit-profile" || $tab == "view-profile") {
                    ?>
                    <div class="d-flex flex-column gap-3 edit-profile-content">
                        <!-- top-section -->
                        <div class="d-flex flex-row align-items-center justify-content-between gap-3 top-section">
                            <!-- heading -->
                            <?php
                            if ($tab == "edit-profile") {
                                ?>
                                <div class="d-flex flex-row align-items-center gap-2 heading">
                                    <i class="fa fa-edit fs-4 text-secondary"></i>
                                    <h4 class="f-reset"> Edit Profile </h4>
                                </div>

                                <!-- form reset btn -->
                                <div class="d-flex flex-row gap-2 action">
                                    <button class="btn btn-outline-warning" id="profile-edit-reset-btn"
                                        onclick="window.location.href='/bookrack/profile/edit-profile'"> Reset </button>

                                    <!-- cancel btn -->
                                    <button class="btn btn-danger" id="profile-edit-cancel-btn"
                                        onclick="window.location.href='/bookrack/profile/view-profile'">
                                        Cancel </button>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <!-- edit profile deatail form -->
                        <form action="/bookrack/app/update-user.php" method="POST"
                            class="d-flex flex-column gap-3 edit-profile-form" enctype="multipart/form-data">
                            <!-- password & status message-->
                            <div class="d-flex flex-column gap-3 m-0 flex-grow-1 w-100 password-div">
                                <?php
                                if ($tab == "view-profile") {
                                    ?>
                                    <div class="d-flex flex-row gap-2">
                                        <div class="d-flex flex-row gap-2 align-items-center bg-dark p-2 px-3 rounded change-password pointer"
                                            onclick="window.location.href='/bookrack/profile/password-change'">
                                            <i class="fa fa-lock text-light"></i>
                                            <p class="f-reset text-light"> Change Password </p>
                                        </div>

                                        <p class="m-0 btn btn-outline-success p-2 px-3"
                                            onclick="window.location.href='/bookrack/profile/kyc'"> My KYC </p>
                                    </div>
                                    <?php
                                }
                                ?>

                                <?php
                                // status message
                                if (isset($_SESSION['status-message']) && isset($_SESSION['status'])) {
                                    ?>
                                    <p class="m-0 <?php echo $_SESSION['status'] ? "text-success" : "text-danger"; ?>">
                                        <?= $_SESSION['status-message'] ?>
                                    </p>
                                    <?php
                                }
                                ?>
                            </div>

                            <!-- user id -->
                            <input type="hidden" name="user-id" id="user-id" value="<?= $profileUser->getUserId() ?>">

                            <!-- profile picture -->
                            <?php
                            if ($tab == "edit-profile") {
                                ?>
                                <div class="d-flex flex-column gap-1 w-100 w-md-50 flex-grow-1 profile-picture">
                                    <label for="edit-profile-profile-picture" class="form-label text-secondary"> Change profile
                                        picture </label>
                                    <input type="file" name="edit-profile-profile-picture" class="border rounded form-control"
                                        id="edit-profile-profile-picture" accept="image/*">
                                </div>
                                <?php
                            }
                            ?>


                            <!-- name -->
                            <div class="d-flex flex-column flex-md-row gap-3 flex-wrap">
                                <div class="flex-grow-1 first-name-div">
                                    <label for="edit-profile-first-name" class="form-label">First name </label>
                                    <input type="text" class="form-control" id="edit-profile-first-name" value="<?php if ($profileUser->getFirstName() != "")
                                        echo getPascalCaseString($profileUser->getFirstName()); ?>"
                                        name="edit-profile-first-name" aria-describedby="first name" <?php if ($tab == "view-profile")
                                            echo "disabled"; ?> required>
                                </div>

                                <div class="flex-grow-1 last-name-div">
                                    <label for="edit-profile-last-name" class="form-label">Last name</label>
                                    <input type="text" class="form-control" id="edit-profile-last-name" value="<?php if ($profileUser->getLastName() != "")
                                        echo getPascalCaseString($profileUser->getLastName()); ?>"
                                        name="edit-profile-last-name" aria-describedby="last name" <?php if ($tab == "view-profile")
                                            echo "disabled"; ?> required>
                                </div>
                            </div>

                            <!-- date of birth & gender -->
                            <div class="d-flex flex-column flex-md-row gap-3 dob-gender">
                                <!-- date of birth -->
                                <div class="d-flex flex-column w-100 w-md-50 dob-div">
                                    <label for="edit-profile-dob" class="form-label"> Date of birth </label>
                                    <input type="date" class="p-2" value="<?php if ($profileUser->getDob() != "")
                                        echo $profileUser->getDob(); ?>" name="edit-profile-dob" <?php if ($tab == "view-profile")
                                              echo "disabled"; ?> required>
                                </div>

                                <!-- gender -->
                                <div class="d-flex flex-column w-100 w-md-50 flex-grow-1">
                                    <label for="edit-profile-gender" class="form-label"> Gender </label>
                                    <select class="form-select" name="edit-profile-gender"
                                        aria-label="Default select example" <?php if ($tab == "view-profile")
                                            echo "disabled"; ?> required>
                                        <?php
                                        if ($profileUser->getGender() == "") {
                                            ?>
                                            <option value="" selected hidden>Select gender</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?= $profileUser->getGender() ?>" selected hidden>
                                                <?php
                                                if ($profileUser->getGender() == 0) {
                                                    echo "Male";
                                                } elseif ($profileUser->getGender() == 1) {
                                                    echo "Female";
                                                } elseif ($profileUser->getGender() == 1) {
                                                    echo "Others";
                                                } else {
                                                    echo "Select gender";
                                                }
                                                ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                        <option value="0">Male</option>
                                        <option value="1">Female</option>
                                        <option value="2">Others</option>
                                    </select>
                                </div>
                            </div>

                            <!-- email & contact -->
                            <div class="d-flex flex-column flex-md-row gap-3 email-contact-div">
                                <!-- email -->
                                <div class="w-100 w-md-50 email-div">
                                    <label for="edit-profile-email" class="form-label"> Email address </label>
                                    <input type="text" class="form-control" id="edit-profile-email"
                                        value="<?= $profileUser->getEmail() ?>" name="edit-profile-email"
                                        aria-describedby="email" disabled>
                                </div>

                                <!-- contact -->
                                <div class="w-100 w-md-50 contact-div">
                                    <label for="edit-profile-contact" class="form-label"> Contact </label>
                                    <input type="text" class="form-control" id="edit-profile-contact" value="<?php if ($profileUser->getContact() != "")
                                        echo $profileUser->getContact(); ?>" name="edit-profile-contact"
                                        aria-describedby="contact" <?php if ($tab == "view-profile")
                                            echo "disabled"; ?> required>
                                </div>
                            </div>

                            <!-- address -->
                            <div class="d-flex flex-column flex-md-row gap-3 address-div">
                                <!-- district -->
                                <div class="w-100 w-md-50 district-div">
                                    <label for="edit-profile-district" class="form-label"> District </label>
                                    <select class="form-select" name="edit-profile-district" aria-label="district select"
                                        <?php if ($tab == "view-profile")
                                            echo "disabled"; ?> required>

                                        <?php
                                        // if value is already set
                                        if ($profileUser->getAddressDistrict() != "") {
                                            ?>
                                            <option value="<?= $profileUser->getAddressDistrict() ?>" selected hidden>
                                                <?= $districtArray[$profileUser->getAddressDistrict()] ?>
                                            </option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="" selected hidden> Select district </option>
                                            <?php
                                        }

                                        foreach ($districtArray as $district) {
                                            ?>
                                            <option value="<?php echo getArrayIndexValue($district, "district"); ?>">
                                                <?= $district ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- location -->
                                <div class="w-100 w-md-50 location-div">
                                    <label for="edit-profile-location" class="form-label"> Location </label>
                                    <input type="text" class="form-control" id="edit-profile-location" value="<?php if ($profileUser->getAddressLocation() != "")
                                        echo getPascalCaseString($profileUser->getAddressLocation()); ?>"
                                        name="edit-profile-location" aria-describedby="location" <?php if ($tab == "view-profile")
                                            echo "disabled"; ?> required>
                                </div>
                            </div>

                            <i class="f-reset small text-secondary"> Note:- This address will be used for dropshipping. </i>

                            <?php
                            if ($tab == "edit-profile") {
                                ?>
                                <button type="submit" class="btn rounded" id="update-profile-btn" name="update-profile-btn">
                                    Update </button>
                                <?php
                            }
                            ?>
                        </form>
                    </div>
                    <?php
                }
                ?>

                <!-- kyc -->
                <?php
                if ($tab == "kyc") {
                    ?>
                    <div class="d-flex flex-column gap-1 kyc-section">
                        <?php
                        if ($profileUser->getAccountStatus() == "pending") {
                            ?>
                            <hr>
                            <p class="m-0 text-secondary text-danger fst-italic">
                                <?php
                                if ($profileUser->getDocumentType() == "") {
                                    echo " You haven't submitted your kyc document yet. Submit now in order to use our services.";
                                } else {
                                    echo "Your documents are on hold to be verified.";
                                }
                                ?>
                            </p>
                            <?php
                        }
                        ?>
                        <hr>

                        <?php
                        if ($profileUser->getAccountStatus() == "verified") {
                            ?>
                            <label for="" class="form-label text-dark fs-4 text-secondary"> My KYC Documents </label>
                            <?php
                        }
                        ?>

                        <?php
                        if (isset($_SESSION['status'])) {
                            if ($_SESSION['status'] == true) {
                                ?>
                                <p class="m-0 text-success"> <?= $_SESSION['status-message'] ?> </p>
                                <?php
                            } else {
                                ?>
                                <p class="m-0 text-danger"> <?= $_SESSION['status-message'] ?> </p>
                                <?php
                            }
                            ?>
                            <?php
                        }
                        ?>

                        <!-- existing kyc documents -->
                        <?php
                        if ($profileUser->getAccountStatus() == "verified" || $profileUser->getDocumentType() != "") {
                            ?>
                            <div class="d-flex flex-column gap-3 mb-4 kyc-documents">
                                <p class="m-0 fs-5"> Document type : <?= getPascalCaseString($profileUser->getDocumentType()) ?>
                                </p>

                                <div class="d-flex flex-row gap-4 documents">
                                    <!-- kyc front side -->
                                    <div class="w-25 d-flex flex-column gap-1 document">
                                        <?php
                                        if ($profileUser->getDocumentType() == "citizenship") {
                                            ?>
                                            <p class="m-0"> Front side </p>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($profileUser->getKycFrontUrl() != "blank") {
                                            ?>
                                            <img src="<?= $profileUser->getKycFrontUrl() ?>" alt="kyc front picture">
                                            <?php
                                        } else {
                                            ?>
                                            <div class="p-2 px-3 border">
                                                <p class="small m-0 text-danger"> Not uploaded yet. </p>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <!-- hide back side if the submitted document is birth certificate -->
                                    <?php
                                    if ($profileUser->getDocumentType() == "citizenship") {
                                        ?>
                                        <!-- back kyc -->
                                        <div class="w-25 d-flex flex-column gap-1 document">
                                            <p class="m-0"> Back side </p>
                                            <?php
                                            if ($profileUser->getKycBackUrl() != "blank") {
                                                ?>
                                                <img src="<?= $profileUser->getKycBackUrl() ?>" alt="kyc front picture">
                                                <?php
                                            } else {
                                                ?>
                                                <div class="p-2 px-3 border">
                                                    <p class="small m-0 text-danger"> Not uploaded yet. </p>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <!-- hide this form if kyc is already verified -->
                        <?php
                        if ($profileUser->getAccountStatus() != "verified" && $profileUser->getDocumentType() == "") {
                            ?>
                            <form action="/bookrack/app/kyc.php" method="POST" class="d-flex flex-column gap-2 w-100 kyc-form"
                                id="kyc-form" enctype="multipart/form-data">
                                <!-- heading -->
                                <p class="m-0 fw-bold fs-3"> KYC Form </p>

                                <hr>

                                <input type="hidden" name="user-id" id="user-id" value="<?= $profileUser->getUserId() ?>">

                                <!-- kyc file inputs -->
                                <div class="d-flex flex-column w-100 gap-3">
                                    <!-- document type -->
                                    <div class="document-type w-25">
                                        <select class="form-select form-select" name="document-type"
                                            aria-label="document type select" required>
                                            <option value="" selected hidden> Select the document type </option>
                                            <option value="1"> Birth Certificate </option>
                                            <option value="2"> Citizenship </option>
                                        </select>
                                    </div>

                                    <!-- front side -->
                                    <div class="front-side w-100">
                                        <label for="" class="form-label text-secondary"> Front Side </label>
                                        <input type="file" name="kyc-front" class="border rounded form-control" id="kyc-front"
                                            accept="image/*" required>
                                    </div>

                                    <div class="back-side w-100">
                                        <!-- backside -->
                                        <label for="" class="form-label text-secondary"> Back Side </label>
                                        <input type="file" name="kyc-back" class="border rounded form-control" id="kyc-back"
                                            accept="image/*">
                                    </div>
                                </div>

                                <button type="submit" name="upload-kyc-btn" class="btn btn-success mt-3"> Upload KYC </button>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>

                <!-- change password -->
                <?php
                if ($tab == "password-change") {
                    ?>
                    <div class="d-flex flex-column gap-3 password-change-content">
                        <!-- top-section -->
                        <div class="d-flex flex-row align-items-center justify-content-between mb-2 gap-3 top-section">
                            <!-- heading -->
                            <div class="d-flex flex-row align-items-center gap-2 heading">
                                <i class="fa fa-edit fs-4 text-secondary"></i>
                                <h4 class="f-reset"> Password Change </h4>
                            </div>

                            <!-- form reset btn -->
                            <button class="btn btn-danger" onclick="window.location.href='/bookrack/profile/'"> Cancel
                            </button>
                        </div>

                        <!-- change password form -->
                        <form method="POST" action="/bookrack/app/password-change.php"
                            class="d-flex flex-column gap-4 password-change-form" id="change-password-form">
                            <?php
                            // status message
                            if (isset($_SESSION['status-message']) && isset($_SESSION['status'])) {
                                ?>
                                <p class="m-0 <?php echo $_SESSION['status'] ? "text-success" : "text-danger"; ?>">
                                    <?= $_SESSION['status-message'] ?>
                                </p>
                                <?php
                            }
                            ?>

                            <!-- old password -->
                            <div class="form-floating">
                                <input type="password" class="form-control" id="old-password" name="old-password" value="<?php
                                if (isset($_SESSION['temp-old-password'])) {
                                    echo $_SESSION['temp-old-password'];
                                }
                                unset($_SESSION['temp-old-password']);
                                ?>" placeholder="" minlength="8" required>
                                <label for="old-password"> Old password </label>
                            </div>

                            <!-- new password -->
                            <div class="form-floating">
                                <input type="password" class="form-control" id="new-password" name="new-password" value="<?php
                                if (isset($_SESSION['temp-new-password'])) {
                                    echo $_SESSION['temp-new-password'];
                                }
                                unset($_SESSION['temp-new-password']);
                                ?>" placeholder="" minlength="8" required>
                                <label for="new-password"> New password </label>
                            </div>

                            <!-- new password confirmation -->
                            <div class="form-floating">
                                <input type="password" class="form-control" id="new-password-confirmation" value="<?php
                                if (isset($_SESSION['temp-new-password-confirmation'])) {
                                    echo $_SESSION['temp-new-password-confirmation'];
                                }
                                unset($_SESSION['temp-new-password-confirmation']);
                                ?>" name="new-password-confirmation" placeholder="" minlength="8" required>
                                <label for="new-password-confirmation"> New password confirmation </label>
                            </div>

                            <div class="d-flex flex-row align-items-center gap-2 pointer show-hide-password-div"
                                id="show-hide-password">
                                <i class="fa fa-eye"></i>
                                <p class="m-0" id="show-hide-password-label"> Show password </p>
                            </div>

                            <button type="submit" class="btn" name="update-password-btn" id="update-password-btn"> Update
                                Password </button>
                        </form>
                    </div>
                    <?php
                }
                ?>


                <!-- my books -->
                <?php if ($tab == "my-books") {

                    ?>
                    <div class="d-flex flex-column gap-4 my-book-content">
                        <!-- my book filter -->
                        <div class="d-flex flex-row flex-wrap gap-2 book-status-container">
                            <div class="book-status active-book-status" id="my-book-status-all">
                                <p> All Books </p>
                            </div>

                            <div class="book-status inactive-book-status" id="my-book-status-active">
                                <p> Active Books </p>
                            </div>

                            <div class="book-status inactive-book-status" id="my-book-status-inactive">
                                <p> Inactive Books </p>
                            </div>

                            <div class="book-status inactive-book-status" id="my-book-status-sold-out">
                                <p> Sold Out </p>
                            </div>
                        </div>

                        <!-- my books container-->
                        <div class="d-flex flex-row flex-wrap gap-3 trending-book-container">
                            <?php
                            $bookList = $bookObj->fetchBookByUserId($profileUser->getUserId());
                            foreach ($bookList as $key => $book) {
                                $bookObj->setCoverPhoto($book['photo']['cover']);
                                $coverPhotoUrl = $bookObj->getCoverPhotoUrl();
                                ?>
                                <div class="book-container my-book my-book-active">
                                    <!-- book image -->
                                    <div class="book-image">
                                        <img src="<?= $coverPhotoUrl ?>" alt="">
                                    </div>

                                    <!-- book details -->
                                    <div class="book-details">
                                        <!-- book title -->
                                        <div class="book-title">
                                            <p class="book-title"> <?= $book['title'] ?> </p>
                                            <i class="fa-regular fa-bookmark"></i>
                                        </div>

                                        <!-- book purpose -->
                                        <p class="book-purpose"> <?= getPascalCaseString($book['purpose']) ?> </p>

                                        <!-- book description -->
                                        <div class="book-description-container">
                                            <p class="book-description"> <?= $book['description'] ?> </p>
                                        </div>

                                        <!-- book price -->
                                        <div class="book-price">
                                            <p class="book-price"> <?= getFormattedPrice($book['price']['offer']) ?> </p>
                                        </div>

                                        <button class="btn" onclick="window.location.href='/bookrack/book-details/<?= $key ?>'">
                                            Show More
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <!-- add book -->
                        <div class="d-flex flex-column justify-content-center add-book-container"
                            onclick="window.location.href='/bookrack/add-book'">
                            <div class="add-book">
                                <i class="fa fa-plus text-light"></i>
                            </div>
                            <p class="f-reset text-dark"> Add New Book </p>
                        </div>
                    </div>

                    <?php
                }
                ?>

                <!-- wishlist -->
                <?php
                if ($tab == "wishlist") {
                    ?>
                    <!-- my books container-->
                    <div class="d-flex flex-column gap-4 my-book-content wishlist-content">
                        <?php
                        $wishlist = new Wishlist();
                        $wishlist->setUserId($profileUser->getUserId());
                        $userWishlist = $wishlist->fetchWishlist();
                        ?>
                        <?php
                        if (sizeof($userWishlist) > 0) {
                            ?>
                            <div class="d-flex flex-row flex-wrap gap-3 wishlist-container">
                                <?php
                                foreach ($userWishlist as $wishedBookId) {
                                    $bookObj->fetch($wishedBookId);
                                    $coverPhotoUrl = $bookObj->getCoverPhotoUrl();
                                    ?>
                                    <div class="book-container">
                                        <!-- book image -->
                                        <div class="book-image">
                                            <!-- <img src="/bookrack/assets/images/cover-1.jpeg" alt=""> -->
                                            <img src="<?= $coverPhotoUrl ?>" alt="book photo">
                                        </div>

                                        <!-- book details -->
                                        <div class="book-details">
                                            <!-- book title -->
                                            <div class="book-title-wishlist">
                                                <p class="book-title"> <?= $bookObj->getTitle() ?> </p>

                                                <div class="wishlist">
                                                    <a
                                                        href="/bookrack/app/wishlist-code.php?book-id=<?= $wishedBookId ?>&ref_url=<?= $url ?>">

                                                        <?php
                                                        if (in_array($wishedBookId, $userWishlist)) {
                                                            ?>
                                                            <i class="fa-solid fa-bookmark"></i>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <i class="fa-regular fa-bookmark"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- book purpose -->
                                            <p class="book-purpose"> <?= getPascalCaseString($bookObj->getPurpose()) ?> </p>

                                            <!-- book description -->
                                            <div class="book-description-container">
                                                <p class="book-description"> <?= getPascalCaseString($bookObj->getDescription()) ?>
                                                </p>
                                            </div>

                                            <!-- book price -->
                                            <div class="book-price">
                                                <p class="book-price"> <?= getFormattedPrice($bookObj->getOfferPrice()) ?> </p>
                                            </div>

                                            <button class="btn"
                                                onclick="window.location.href='/bookrack/book-details/<?= $wishedBookId ?>'"> Show
                                                More
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="empty-div">
                                <img src="/bookrack/assets/icons/empty.svg" alt="">
                                <p class="empty-message"> Your wishlist is empty! </p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>

                <!-- requested books -->
                <?php
                if ($tab == "requested-books") {
                    ?>
                    <div class="d-flex flex-column gap-4 requested-book-content">
                        <!-- requested books filter -->
                        <div class="d-flex flex-row gap-2 requested-book-filter">
                            <select class="form-select" name="book-request-purpose" id="request-purpose"
                                aria-label="book request purpose">
                                <option value="requested-books-purpose-all"> All purpose </option>
                                <option value="requested-books-purpose-rent"> Rent </option>
                                <option value="requested-books-purpose-buy-sell"> Buy/Sell </option>
                            </select>

                            <select class="form-select" name="book-request-state" id="request-state"
                                aria-label="book request purpose">
                                <option value="requested-books-state-all"> All State </option>
                                <option value="requested-books-state-pending"> Pending </option>
                                <option value="requested-books-state-completed"> Completed </option>
                            </select>
                        </div>

                        <div class="requested-book-table-container">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col"> SN </th>
                                        <th scope="col"> Title </th>
                                        <th scope="col"> Price </th>
                                        <th scope="col"> Purpose </th>
                                        <th scope="col"> Starting Date </th>
                                        <th scope="col"> Ending Date </th>
                                        <th scope="col"> State </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="requested-book-tr requested-book-purpose-rent-tr requested-book-state-pending-tr">
                                        <th scope="row">1</th>
                                        <td> The Great Gatsby </td>
                                        <td> NRs. 120 </td>
                                        <td> Rent </td>
                                        <td> 2024/02/05 </td>
                                        <td> 2024/02/05 </td>
                                        <td> Pending </td>
                                    </tr>

                                    <tr
                                        class="requested-book-tr requested-book-purpose-buy-sell-tr requested-book-state-completed-tr">
                                        <th scope="row">2</th>
                                        <td> Harry Porter and the Socerer's Stonr </td>
                                        <td> NRs. 75 </td>
                                        <td> Sell </td>
                                        <td> 2024/02/05 </td>
                                        <td> 2024/02/05 </td>
                                        <td> Completed </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr style="text-align: center;">
                                        <td colspan="7" style="color: rgb(194, 16, 16);"> You haven't requested any book
                                            yet! </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                ?>


                <!-- earning -->
                <?php
                if ($tab == "earning") {
                    ?>
                    <div class="d-flex flex-column gap-4 requested-book-content earning-content">
                        <!-- earning filter -->
                        <div class="d-flex flex-row gap-2 requested-book-filter">
                            <select class="form-select" name="earning-purpose" id="earning-purpose"
                                aria-label="earning purpose select">
                                <option value="earning-purpose-all" selected> All Purpose </option>
                                <option value="earning-purpose-rent"> Rent </option>
                                <option value="earning-purpose-buy-sell"> Buy/ Sell </option>
                            </select>

                            <select class="form-select" name="earning-state" name="earning-state" id="earning-state"
                                aria-label="earning state select">
                                <option value="earning-state-all" selected> All state </option>
                                <option value="earning-state-active"> Active </option>
                                <option value="earning-state-completed"> Completed </option>
                            </select>
                        </div>

                        <div class="requested-book-table-container">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col"> SN </th>
                                        <th scope="col"> Title </th>
                                        <th scope="col"> Price </th>
                                        <th scope="col"> Purpose </th>
                                        <th scope="col"> Starting Date </th>
                                        <th scope="col"> Ending Date </th>
                                        <th scope="col"> State </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="earning-tr earning-purpose-rent-tr earning-state-active-tr">
                                        <th scope="row">1</th>
                                        <td> The Great Gatsby </td>
                                        <td> NRs. 120 </td>
                                        <td> Rent </td>
                                        <td> 2024/02/05 </td>
                                        <td> 2024/02/05 </td>
                                        <td> Active </td>
                                    </tr>

                                    <tr class="earning-tr earning-purpose-buy-sell-tr earning-state-completed-tr">
                                        <th scope="row">2</th>
                                        <td> Harry Porter and the Socerer's Stonr </td>
                                        <td> NRs. 75 </td>
                                        <td> Sell </td>
                                        <td> 2024/02/05 </td>
                                        <td> 2024/02/05 </td>
                                        <td> Completed </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr style="text-align: center;">
                                        <td colspan="7" style="color: rgb(194, 16, 16);"> No earning yet! </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </section>
        </article>
    </main>

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <?php
    // unset session status & status
    unset($_SESSION['status']);
    unset($_SESSION['status-message']);
    ?>

    <!-- footer -->

    <!-- modal -->

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- edit profile script -->
    <script>
        // first name
        $('#edit-profile-first-name').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });

        // last name
        $('#edit-profile-last-name').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });

        // contact name
        $('#edit-profile-contact').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });
    </script>

    <!-- my books script -->
    <script>
        // my books
        $myBookStatus = "all";
        $('.book-container').show();

        // my books - all
        $('#my-book-status-all').click(function () {
            $myBookStatus = "all";
            toggleMyBooks();
        });

        // my books - active
        $('#my-book-status-active').click(function () {
            $myBookStatus = "active";
            toggleMyBooks();
        });

        // my books - inactive
        $('#my-book-status-inactive').click(function () {
            $myBookStatus = "inactive";
            toggleMyBooks();
        });

        // my books - sold out
        $('#my-book-status-sold-out').click(function () {
            $myBookStatus = "sold-out";
            toggleMyBooks();
        });

        toggleMyBooks = () => {
            $('.my-book').show();
            switch ($myBookStatus) {
                case 'active':
                    $('.my-book-inactive').hide();
                    $('.my-book-sold-out').hide();
                    break;
                case 'inactive':
                    $('.my-book-active').hide();
                    $('.my-book-sold-out').hide();
                    break;
                case 'sold-out':
                    $('.my-book-active').hide();
                    $('.my-book-inactive').hide();
                    break;
            };
        };

        toggleMyBooks();
    </script>

    <!-- change password script -->
    <script>
        // toggle the visibility of the password
        $('#show-hide-password').click(function () {
            if ($('#old-password').attr('type') === 'password') {
                $('#old-password').attr('type', 'text');
                $('#new-password').attr('type', 'text');
                $('#new-password-confirmation').attr('type', 'text');
                $('#show-hide-password-label').text("Hide Password");
            } else {
                $('#old-password').attr('type', 'password');
                $('#new-password').attr('type', 'password');
                $('#new-password-confirmation').attr('type', 'password');
                $('#show-hide-password-label').text("Show Password");
            }
        });
    </script>

    <!-- requested book script -->
    <script>
        // request purpose
        $('#request-purpose').change(function () {
            filterRequestedBooks();
        });

        // request status
        $('#request-state').change(function () {
            filterRequestedBooks();
        });

        filterRequestedBooks = () => {
            // purpose
            $('.requested-book-tr').show();
            switch ($('#request-purpose').val()) {
                case 'requested-books-purpose-rent':
                    $('.requested-book-purpose-buy-sell-tr').hide();
                    break;
                case 'requested-books-purpose-buy-sell':
                    $('.requested-book-purpose-rent-tr').hide();
                    break;
            }

            // state
            switch ($('#request-state').val()) {
                case 'requested-books-state-pending':
                    $('.requested-book-state-completed-tr').hide();
                    break;
                case 'requested-books-state-completed':
                    $('.requested-book-state-pending-tr').hide();
                    break;
            }
        }
    </script>

    <!-- earning script -->
    <script>
        // earning purpose
        $('#earning-purpose').change(function () {
            filterEarning();
        });

        // earning state
        $('#earning-state').change(function () {
            filterEarning();
        });

        filterEarning = () => {
            console.clear();

            // earning purpose
            $('.earning-tr').show();
            switch ($('#earning-purpose').val()) {
                case 'earning-purpose-rent':
                    $('.earning-purpose-buy-sell-tr').hide();
                    break;
                case 'earning-purpose-buy-sell':
                    $('.earning-purpose-rent-tr').hide();
                    break;
            }

            // earning state
            switch ($('#earning-state').val()) {
                case 'earning-state-active':
                    $('.earning-state-completed-tr').hide();
                    break;
                case 'earning-state-completed':
                    $('.earning-state-active-tr').hide();
                    break;
            }
        };
    </script>
</body>

</html>