<?php
require_once __DIR__ . '/functions/genre-array.php';
require_once __DIR__ . '/functions/district-array.php';
require_once __DIR__ . '/classes/user.php';

$userExists = $profileUser->fetch($profileId);
$url = "profile";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title>
        <?php
        switch ($tab) {
            case "edit-profile":
                echo "Edit profile";
                break;
            case "kyc":
                echo "KYC";
                break;
            default:
                echo "My profile";
        }
        ?>
    </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/book.css">
    <link rel="stylesheet" href="/bookrack/css/profile.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <!-- main -->
    <main class="d-flex d-column flex-lg-row gap-lg-4 container main">
        <!-- aside :: profile detail  -->
        <aside class="aside gap-3" id="aside">
            <!-- profile section -->
            <section class="d-flex flex-column shadow-sm p-3 py-4 mb-4 gap-3 profile-section">
                <!-- profile picture -->
                <div class="d-flex flex-column gap-2 align-items-center profile-top">
                    <div class="profile-image">
                        <?php
                        if (!isset($userPhotoUrl))
                            $profileUser->setPhotoUrl();

                        if ($profileUser->photo != "") {
                            ?>
                            <img src="<?= $userPhotoUrl ?>" alt="profile picture" loading="lazy">
                            <?php
                        } else {
                            ?>
                            <img src="/bookrack/assets/images/blank-user.jpg" alt="profile picture" loading="lazy">
                            <?php
                        }
                        ?>
                    </div>

                    <div class="d-flex flex-row gap-2 align-items-center profile name-status">
                        <p class="m-0 text-secondary">
                            <?php echo $profileUser->name['first'] == "" ? $profileUser->email : $profileUser->getFullName(); ?>
                        </p>
                        <?php
                        if ($profileUser->accountStatus == "verified") {
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
                    <?php
                    // extract only the date from joined date
                    $fullDateTime = $profileUser->joinedDate;
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
                            <p class="m-0"> <?php echo $profileUser->joinedDate != "" ? $dateOnly : "-"; ?>
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

                        <div class="data-div">
                            <p class="m-0 fw-bold" id="book-offer-count"> - </p>
                        </div>
                    </div>

                    <!-- books offered -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-none"></i>
                            <span> Books consumed </span>
                        </div>

                        <div class="data-div">
                            <p class="m-0 fw-bold"> <?= "-" ?> </p>
                        </div>
                    </div>
                </div>
            </section>
        </aside>

        <!-- article -->
        <article class="article bg-md-success mb-4 bg-sm-danger">
            <!-- account state notification note div -->
            <?php
            if ($profileUser->accountStatus != "verified") {
                ?>
                <div class="alert alert-danger mb-4" role="alert">
                    Your account is still not verified. Make sure you have provided all your details. If you have provided
                    every details already, please wait sometime to get your account verified.
                </div>
                <?php
            }
            ?>

            <!-- contents -->
            <section class="d-flex flex-column gap-3 contents">
                <!-- tab -->
                <div class="d-flex flex-row gap-2 tab-container">
                    <!-- my profile -->
                    <a href="/bookrack/profile/" class="btn tab <?= $tab == 'view' ? 'active-tab' : 'inactive-tab' ?>">
                        <i class="fa fa-user"></i>
                        <p class="m-0"> My Profile </p>
                    </a>

                    <!-- Password & Security -->
                    <a href="/bookrack/profile/password-change"
                        class="btn tab <?= $tab == 'password-change' ? 'active-tab' : 'inactive-tab' ?>">
                        <i class="fa fa-lock"></i>
                        <p class="m-0"> Password & Security </p>
                    </a>

                    <!-- kyc -->
                    <a href="/bookrack/profile/kyc"
                        class="btn tab <?= $tab == 'kyc' ? 'active-tab' : 'inactive-tab' ?>">
                        <i class="fa-regular fa-note-sticky"></i>
                        <p class="m-0"> MY KYC </p>
                    </a>
                </div>

                <!-- profile :: view & edit -->
                <?php if ($tab == "view") {
                    ?>
                    <div class="d-flex flex-column gap-3 edit-profile-content">
                        <!-- top-section -->
                        <div class="d-flex flex-row align-items-center justify-content-between gap-3 top-section">
                            <!-- heading -->
                            <div class="d-flex flex-row align-items-center gap-2 heading">
                                <h4 class="m-0"> My Profile </h4>
                            </div>

                            <!-- form reset btn -->
                            <div class="d-flex flex-row gap-2 action">
                                <!-- cancel btn -->
                                <button class="btn btn-danger" id="profile-edit-cancel-btn"> Cancel </button>
                            </div>
                        </div>

                        <!-- edit profile deatail form -->
                        <form method="POST" class="d-flex flex-column gap-3 edit-profile-form" enctype="multipart/form-data"
                            id="profile-form">
                            <!-- user id -->
                            <input type="hidden" name="user-id" id="user-id" value="<?= $profileId ?>">

                            <!-- profile picture -->
                            <!-- <div class="d-flex flex-column gap-1 w-100 w-md-50 flex-grow-1 profile-picture">
                                <label for="edit-profile-profile-picture" class="form-label text-secondary"> Change profile
                                    picture </label>
                                <input type="file" name="edit-profile-profile-picture" class="border rounded form-control"
                                    id="edit-profile-profile-picture" accept="image/*">
                            </div> -->

                            <!-- name -->
                            <div class="d-flex flex-column flex-md-row gap-3 flex-wrap">
                                <div class="flex-grow-1 first-name-div">
                                    <label for="edit-profile-first-name" class="form-label">First name </label>
                                    <input type="text" class="form-control" id="edit-profile-first-name"
                                        name="edit-profile-first-name" aria-describedby="first name">
                                </div>

                                <div class="flex-grow-1 last-name-div">
                                    <label for="edit-profile-last-name" class="form-label">Last name</label>
                                    <input type="text" class="form-control" id="edit-profile-last-name"
                                        name="edit-profile-last-name" aria-describedby="last name">
                                </div>
                            </div>

                            <!-- date of birth & gender -->
                            <div class="d-flex flex-column flex-md-row gap-3 dob-gender">
                                <!-- date of birth -->
                                <div class="d-flex flex-column w-100 w-md-50 dob-div">
                                    <label for="edit-profile-dob" class="form-label"> Date of birth </label>
                                    <input type="date" class="p-2" name="edit-profile-dob">
                                </div>

                                <!-- gender -->
                                <div class="d-flex flex-column w-100 w-md-50 flex-grow-1">
                                    <label for="edit-profile-gender" class="form-label"> Gender </label>
                                    <select class="form-select" name="edit-profile-gender"
                                        aria-label="Default select example">
                                        <option value="">Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                            </div>

                            <!-- email & contact -->
                            <div class="d-flex flex-column flex-md-row gap-3 email-contact-div">
                                <!-- email -->
                                <div class="w-100 w-md-50 email-div">
                                    <label for="edit-profile-email" class="form-label"> Email address </label>
                                    <input type="text" class="form-control" id="edit-profile-email"
                                        name="edit-profile-email" aria-describedby="email" disabled>
                                </div>

                                <!-- phone number -->
                                <div class="w-100 w-md-50 contact-div">
                                    <label for="edit-profile-contact" class="form-label"> Contact </label>
                                    <input type="number" class="form-control" id="edit-profile-contact"
                                        name="edit-profile-contact" aria-describedby="contact" minlength="10"
                                        maxlength="10">
                                </div>
                            </div>

                            <!-- district && municipality -->
                            <div class="d-flex flex-column flex-md-row gap-3 address-div">
                                <!-- district -->
                                <div class="w-100 w-md-50 district-div">
                                    <label for="edit-profile-district" class="form-label"> District </label>
                                    <select class="form-select" name="edit-profile-district" id="edit-profile-district"
                                        aria-label="district select" disabled>
                                        <?php
                                        // if value is already set
                                        if ($profileUser->getAddressDistrict() != "") {
                                            ?>
                                            <option value="<?= $profileUser->getAddressDistrict() ?>" selected hidden>
                                                <?=  ucwords($profileUser->getAddressDistrict()) ?>
                                            </option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="" selected hidden> Select district </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- municipality -->
                                <div class="w-100 w-md-50 municipality-div">
                                    <label for="edit-profile-municipality" class="form-label"> Municipality </label>
                                    <input type="text" class="form-control" id="edit-profile-municipality" value="<?php if ($profileUser->getAddressMunicipality() != "")
                                        echo ucfirst($profileUser->getAddressMunicipality()); ?>" name="edit-profile-municipality"
                                        aria-describedby="municipality" disabled>
                                </div>
                            </div>

                            <!-- ward && tole && village -->
                            <div class="d-flex flex-column flex-md-row w-100 w-md-50 gap-3">
                                <!-- ward -->
                                <div class="w-100 w-md-50">
                                    <label for="edit-profile-ward" class="form-label"> Ward </label>
                                    <select name="edit-profile-ward" id="edit-profile-ward" class="form-control w-100"
                                        disabled>
                                        <option value="" selected hidden> Select Ward </option>
                                    </select>
                                </div>

                                <!-- tole/ village -->
                                <div class="w-100 w-md-50 ">
                                    <label for="edit-profile-tole-village" class="form-label"> Tole/ Village </label>
                                    <input type="text" class="form-control" id="edit-profile-tole-village" value="<?php if ($profileUser->getAddressToleVillage() != "")
                                        echo ucfirst($profileUser->getAddressToleVillage()); ?>" name="edit-profile-tole-village"
                                        aria-describedby="tole-village" disabled>
                                </div>
                            </div>

                            <i class="m-0 small text-secondary"> Note:- This address will be used for dropshipping. </i>

                            <!-- <button type="submit" class="btn rounded" id="update-profile-btn" name="update-profile-btn"> Update </button> -->
                        </form>
                    </div>

                    <?php
                    if ($tab == "view") {
                        ?>
                        <div class="d-flex flex-row gap-2 action">

                            <a class="btn btn-success fit-content d-flex flex-row gap-2 align-items-center"
                                id="edit-profile-btn"> <i class="fa fa-edit"> </i> Edit </a>

                            <?php
                            if ($profileUser->accountStatus == "pending") {
                                // check if all the data has been provided
                                if ($profileUser->checkAccountVerificationEligibility()) {
                                    ?>
                                    <form class="d-flex flex-column" id="account-verification-form">
                                        <input type="hidden" class="form-control" id="csrf_token_account_verification"
                                            name="csrf_token_account_verification">
                                        <button type="submit" class="m-0 btn btn-outline-primary p-2 px-3"
                                            id="account-verification-btn"> Apply for Account Verification </button>
                                    </form>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>

                <!-- kyc -->
                <?php
                if ($tab == "kyc") {
                    ?>
                    <div class="d-flex flex-column gap-1 kyc-section">
                        <?php
                        if ($profileUser->accountStatus == "pending") {
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
                        if ($profileUser->accountStatus == "verified") {
                            ?>
                            <label for="" class="form-label text-dark fs-4 text-secondary"> My KYC Documents </label>
                            <?php
                        }
                        ?>

                        <!-- existing kyc documents -->
                        <?php
                        if ($profileUser->accountStatus == "verified" || $profileUser->getDocumentType() != "") {
                            $profileUser->setKycFrontUrl();
                            ?>
                            <div class="d-flex flex-column gap-3 mb-4 kyc-documents">
                                <p class="m-0 fs-5"> Document type : <?= ucfirst($profileUser->getDocumentType()) ?>
                                </p>

                                <div class="d-flex flex-row gap-4 documents">
                                    <!-- kyc front side -->
                                    <div class="w-25 d-flex flex-column gap-1 document">
                                        <?php
                                        if ($profileUser->getDocumentType() == "citizenship") {
                                            $profileUser->setKycBackUrl();
                                            ?>
                                            <p class="m-0"> Front side </p>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($profileUser->getKycFrontUrl() != "") {
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
                                            if ($profileUser->getKycBackUrl() != "") {
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
                        if ($profileUser->accountStatus != "verified" && $profileUser->getDocumentType() == "") {
                            ?>
                            <form action="/bookrack/app/kyc.php" method="POST" class="d-flex flex-column gap-2 w-100 kyc-form"
                                id="kyc-form" enctype="multipart/form-data">
                                <!-- heading -->
                                <p class="m-0 fw-bold fs-3"> KYC Form </p>
                                <hr>
                                <input type="hidden" name="csrf_token_profile" id="csrf_token_profile" value="">
                                <input type="hidden" name="user-id" id="user-id" value="<?= $profileUser->getUserId() ?>">

                                <!-- kyc file inputs -->
                                <div class="d-flex flex-column w-100 gap-3">
                                    <!-- document type -->
                                    <div class="document-type w-100">
                                        <select class="form-select form-select w-100 w-lg-50" name="document-type"
                                            id="document-type-select" aria-label="document type select" required>
                                            <option value="" selected hidden> Select the document type </option>
                                            <option value="1"> Birth Certificate </option>
                                            <option value="2"> Citizenship </option>
                                        </select>
                                    </div>

                                    <!-- front side -->
                                    <div class="front-side w-100" id="kyc-front-field">
                                        <label for="" class="form-label text-secondary"> Front Side </label>
                                        <input type="file" name="kyc-front" class="border rounded form-control" id="kyc-front"
                                            accept="image/*" required>
                                    </div>

                                    <div class="back-side w-100" id="kyc-back-field">
                                        <!-- backside -->
                                        <label for="" class="form-label text-secondary"> Back Side </label>
                                        <input type="file" name="kyc-back" class="border rounded form-control" id="kyc-back"
                                            accept="image/*">
                                    </div>
                                </div>

                                <button type="submit" name="upload-kyc-btn" id="upload-kyc-btn" class="btn btn-success mt-3">
                                    Upload KYC </button>
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
                                <h4 class="m-0"> Change Password </h4>
                            </div>

                            <!-- form reset btn -->
                            <button class="btn btn-danger" onclick="window.location.href='/bookrack/profile/'"> Cancel
                            </button>
                        </div>

                        <!-- change password form -->
                        <form class="d-flex flex-column gap-4 password-change-form" id="change-password-form">
                            <input type="hidden" name="csrf_token_password" class="form-control" id="csrf_token_password">

                            <!-- old password -->
                            <div class="form-floating">
                                <input type="password" class="form-control" id="old-password" name="old-password"
                                    minlength="8" required>
                                <label for="old-password"> Old password </label>
                            </div>

                            <!-- new password -->
                            <div class="form-floating">
                                <input type="password" class="form-control" id="new-password" name="new-password"
                                    minlength="8" required>
                                <label for="new-password"> New password </label>
                            </div>

                            <!-- new password confirmation -->
                            <div class="form-floating">
                                <input type="password" class="form-control" id="new-password-confirmation"
                                    name="new-password-confirmation" minlength="8" required>
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
            </section>
        </article>
    </main>

    <!-- popup alert -->
    <p class="" id="custom-popup-alert"> Popup message appears here... </p>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- script -->
    <script>
        tab = "<?= $tab ?>";
        $('#custom-popup-alert').hide();

        function showPopupAlert(msg) {
            $('#custom-popup-alert').removeClass('text-success').addClass('text-danger');
            $('#custom-popup-alert').html(msg).fadeIn();
            setTimeout(function () {
                $('#custom-popup-alert').fadeOut("slow").html("");
            }, 4000);
        }
    </script>

    <script>
        $(document).ready(function () {
            tab = "<?= $tab ?>";

            if (tab == "view") {
                $('#profile-edit-cancel-btn').hide();

                function toggleInputFields(set) {
                    $('#edit-profile-first-name').prop('disabled', set);
                    $('#edit-profile-last-name').prop('disabled', set);
                    $('#edit-profile-dob').prop('disabled', set);
                    $('#edit-profile-gender').prop('disabled', set);
                    $('#edit-profile-contact').prop('disabled', set);
                    $('#edit-profile-district').prop('disabled', set);
                    $('#edit-profile-municipality').prop('disabled', set);
                    $('#edit-profile-ward').prop('disabled', set);
                    $('#edit-profile-tole-village').prop('disabled', set);
                    $('#edit-profile-location').prop('disabled', set);
                    $('#update-profile-btn').show();
                    $('#edit-profile-form-photo-container').removeClass('d-none');
                }

                // user profile loading function
                function loadUserProfileData(user_id, profile_task) {
                    $.ajax({
                        url: '/bookrack/sections/user-profile-fetch.php',
                        type: "POST",
                        data: { userId: user_id, tab: profile_task },
                        success: function (data) {
                            $('#profile-form').html(data);
                            $('#update-profile-btn').hide();
                        },
                        error: function () {
                            console.log("Error in fetching user detail.");
                        }
                    });
                }

                // first name
                $(document).on('keydown', '#edit-profile-first-name', function () {
                    var asciiValue = event.keyCode || event.which;
                    if (asciiValue == 32) {
                        event.preventDefault();
                    }
                });

                // last name
                $(document).on('keydown', '#edit-profile-last-name', function (event) {
                    var asciiValue = event.keyCode || event.which;
                    if (asciiValue == 32) {
                        event.preventDefault();
                    }
                });

                // contact
                $(document).on('keydown', '#edit-profile-contact', function (event) {
                    var asciiValue = event.keyCode || event.which;
                    if (asciiValue >= 48 && asciiValue <= 57) {
                        // allow input
                    } else if (asciiValue == 8 || asciiValue == 37 || asciiValue == 39 || asciiValue == 46) {

                    } else {
                        event.preventDefault();
                    }
                });

                loadUserProfileData("<?= $_SESSION['bookrack-user-id'] ?>", "<?= $tab ?>");

                // edit button
                $('#edit-profile-btn').click(function () {
                    $('#profile-edit-cancel-btn').show();
                    $('#edit-profile-btn').addClass('d-none');
                    toggleInputFields(false);
                });

                // reset btn
                $('#profile-edit-cancel-btn').click(function () {
                    $('#profile-edit-cancel-btn').hide();
                    loadUserProfileData("<?= $_SESSION['bookrack-user-id'] ?>", "<?= $tab ?>");
                    $('#edit-profile-btn').removeClass('d-none');
                });

                // update profile
                $('#profile-form').submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData($('#profile-form')[0]);
                    $.ajax({
                        url: '/bookrack/app/update-user.php',
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#update-profile-btn').html('Updating...').prop('disabled', true);
                        },
                        success: function (data) {
                            $('#custom-popup-alert').html(data).fadeIn();
                            setTimeout(function () {
                                $('#custom-popup-alert').fadeOut();
                            }, 4000);
                            if (data == "Profile updated successfully.") {
                                toggleInputFields(true);
                                location.reload();
                                $('#profile-edit-cancel-btn').click();
                            }
                            $('#update-profile-btn').html('Update').prop('disabled', false);
                        },
                        error: function () {
                            $('#update-profile-btn').html('Update').prop('disabled', false);
                            $('#custom-popup-alert').html("Error occured. Please try later.").fadeIn();
                            setTimeout(function () {
                                $('#custom-popup-alert').fadeOut();
                            }, 4000);
                        }
                    });
                });

                // account verification
                function setCsrfTokenForAccountVerification() {
                    $.get('/bookrack/app/csrf-token.php', function (data) {
                        $('#csrf_token_account_verification').val(data);
                    });
                }

                setCsrfTokenForAccountVerification();

                $('#account-verification-form').submit(function (event) {
                    event.preventDefault();
                    var formData = $('#account-verification-form').serialize()
                    $.ajax({
                        url: '/bookrack/app/account-verification.php',
                        type: "POST",
                        data: formData,
                        beforeSend: function () {
                            $('#account-verification-btn').html('Verifying...');
                        },
                        success: function (data) {
                            if (data == "success") {
                                showPopupAlert("Your account has been verified.");
                                location.reload();
                                $('#account-verification-btn').html('Account Verified').prop('disabled', true);
                            } else {
                                showPopupAlert("Your account couldn't be verified.");
                                $('#account-verification-btn').html('Apply for Account Verification');
                            }
                        },
                        error: function () {
                            $('#account-verification-btn').html('Apply for Account Verification');

                        },
                    });
                });
            } else if (tab == "password-change") {
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

                // change password script
                function setCsrfTokenForPassword() {
                    $.get('/bookrack/app/csrf-token.php', function (data) {
                        $('#csrf_token_password').val(data);
                    });
                }

                setCsrfTokenForPassword();

                $('#change-password-form').on('submit', function (event) {
                    event.preventDefault();
                    // same old and new password
                    var oldPassword = $('#old-password').val();
                    var newPassword = $('#new-password').val();
                    var newPasswordConfirmation = $('#new-password-confirmation').val();

                    if (oldPassword == newPassword) {
                        showPopupAlert("Please use different password for old password and new password field.");
                    } else if (newPassword != newPasswordConfirmation) {
                        showPopupAlert("Please enter the new password and password for confirmation same.");
                    } else {
                        const updatePasswordBtn = $('#update-password-btn');
                        var formData = $('#change-password-form').serialize()
                        $.ajax({
                            url: '/bookrack/app/update-password.php',
                            type: "POST",
                            data: formData,
                            beforeSend: function () {
                                updatePasswordBtn.html("Updating password...").prop('disabled', true);
                            },
                            success: function (data) {
                                showPopupAlert(data);
                                updatePasswordBtn.html("Update Password").prop('disabled', false);
                                if (data == "Password changed successfully.") {
                                    $('#change-password-form').trigger("reset");
                                }
                            },
                            error: function () {
                                updatePasswordBtn.html("Update Password").prop('disabled', false);
                                showPopupAlert("Please enter the new password and password for confirmation same.");
                            }
                        });
                    }
                });
            } else if (tab == "kyc") {
                // kyc           
                $('#document-type-select').on('change', function () {
                    if ($('#document-type-select').val() == 1)
                        $('#kyc-back-field').hide();
                    else
                        $('#kyc-back-field').show();
                });

                $('#kyc-form').submit(function (e) {
                    e.preventDefault();

                    // check if empty
                    error = true;
                    if ($('#document-type-select').val() == 2) {
                        if ($('#kyc-back').val() == "") {
                            $('#custom-popup-alert').html("Please attach back side of your citizenship.").show();
                            setTimeout(function () {
                                $('#custom-popup-alert').fadeOut();
                            }, 4000);
                        } else {
                            error = false;
                        }
                    } else {
                        error = false;
                    }

                    if (!error) {
                        var formData = new FormData($('#kyc-form')[0]);
                        $.ajax({
                            url: '/bookrack/app/kyc.php',
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            beforeSend: function () {
                                $('#upload-kyc-btn').html('Uploading...');
                                $('#upload-kyc-btn').prop('disabled', true);
                            },
                            success: function (data) {
                                $('#upload-kyc-btn').html('Upload KYC');
                                $('#upload-kyc-btn').prop('disabled', false);
                                $('#custom-popup-alert').html(data).show();

                                if (data == "Document submitted successfully.") {
                                    $('#kyc-form').trigger("reset");
                                    location.reload(true);
                                }
                                setTimeout(function () {
                                    $('#custom-popup-alert').fadeOut();
                                }, 4000);
                            },
                            error: function () {
                                $('#upload-kyc-btn').html('Upload KYC');
                                $('#upload-kyc-btn').prop('disabled', false);
                                $('#custom-popup-alert').html("An unexpected error occured. Please try again.").show();
                                setTimeout(function () {
                                    $('#custom-popup-alert').fadeOut();
                                }, 4000);
                            }
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>