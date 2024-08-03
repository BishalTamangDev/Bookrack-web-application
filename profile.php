<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

$userId = $_SESSION['bookrack-user-id'];

$url = "profile";

require_once __DIR__ . '/functions/genre-array.php';
require_once __DIR__ . '/functions/district-array.php';
require_once __DIR__ . '/classes/user.php';

$profileUser = new User();
$userExists = $profileUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/signout");

require_once __DIR__ . '/classes/book.php';
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
        switch ($tab) {
            case "edit-profile":
                echo "Edit profile";
                break;
            case "kyc":
                echo "KYC";
                break;
            case "my-books":
                echo "My books";
                break;
            case "wishlist":
                echo "Wishlist";
                break;
            case "requested-books":
                echo "Requested books";
                break;
            case "earning":
                echo "Earning";
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
    <?php
    require_once __DIR__ . '/sections/header.php'; ?>

    <?php $userBookList = $bookObj->fetchBookByUserId($userId); ?>

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
                    <!-- address -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-map-pin"></i>
                            <span> From </span>
                        </div>
                        <div class="data-div">
                            <p class="m-0">
                                <?php
                                echo $profileUser->getAddressDistrict() != "" ? $profileUser->getFullAddress() : "-"; ?>
                            </p>
                        </div>
                    </div>

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
                            <p class="m-0 fw-bold"> <?= sizeof($userBookList) ?> </p>
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

                <!-- <button class="btn" id="edit-profile-btn" onclick="window.location.href='/bookrack/profile/edit-profile'"> Edit </button> -->
                <?php
                if ($tab == "view") {
                    ?>
                    <button class="btn" id="edit-profile-btn"> Edit </button>
                    <?php
                } elseif($tab == "kyc" || $tab == "password-change") {
                    ?>
                    <a href="/bookrack/profile/" class="btn" id="view-profile-btn"> Show Profile Detail </a>
                    <?php
                }
                ?>
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

            <!-- tab -->
            <section class="d-flex flex-row flex-wrap gap-3 gap-md-3 mt-1 mb-3 tab-section">
                <!-- profile tab -->
                <div class="tab">
                    <p onclick="window.location.href='/bookrack/profile/'"> MY PROFILE </p>
                    <div
                        class="indicator <?php echo ($tab == "view" || $tab == "password-change" || $tab == "kyc") ? "active" : "inactive"; ?>">
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
                <?php if ($tab == "view") {
                    ?>
                    <div class="d-flex flex-row gap-2">
                        <div class="d-flex flex-row gap-2 align-items-center bg-dark p-2 px-3 rounded change-password pointer"
                            onclick="window.location.href='/bookrack/profile/password-change'">
                            <i class="fa fa-lock text-light"></i>
                            <p class="m-0 text-light"> Change Password </p>
                        </div>

                        <?php 
                        // if ($profileUser->accountStatus != "verified") {
                        if ($profileUser->accountStatus == "pending") {
                            // check if all the data has been provided
                            if($profileUser->checkAccountVerificationEligibility()) {
                                ?>
                                <form class="d-flex flex-column" id="account-verification-form">
                                    <input type="hidden" class="form-control" id="csrf_token_account_verification"
                                        name="csrf_token_account_verification">
                                    <button type="submit" class="m-0 btn btn-outline-primary p-2 px-3"
                                        id="account-verification-btn"> Apply for Account Verification </button>
                                </form>
                                <?php
                            }
                        } ?>

                        <p class="m-0 btn btn-outline-success p-2 px-3"
                            onclick="window.location.href='/bookrack/profile/kyc'"> My KYC </p>
                    </div>

                    <div class="d-flex flex-column gap-3 edit-profile-content">
                        <!-- top-section -->
                        <div class="d-flex flex-row align-items-center justify-content-between gap-3 top-section">
                            <!-- heading -->
                            <div class="d-flex flex-row align-items-center gap-2 heading">
                                <i class="fa fa-edit fs-4 text-secondary"></i>
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
                            <input type="hidden" name="user-id" id="user-id" value="<?= $userId ?>">

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
                                        name="edit-profile-email" aria-describedby="email" disabled>
                                </div>

                                <!-- phone number -->
                                <div class="w-100 w-md-50 contact-div">
                                    <label for="edit-profile-contact" class="form-label"> Contact </label>
                                    <input type="number" class="form-control" id="edit-profile-contact"
                                        name="edit-profile-contact" aria-describedby="contact" minlength="10" maxlength="10">
                                </div>
                            </div>

                            <!-- address -->
                            <div class="d-flex flex-column flex-md-row gap-3 address-div">
                                <!-- district -->
                                <div class="w-100 w-md-50 district-div">
                                    <label for="edit-profile-district" class="form-label"> District </label>
                                    <select class="form-select" name="edit-profile-district" aria-label="district select">
                                        <option value="" selected hidden> District </option>
                                    </select>
                                </div>

                                <!-- location -->
                                <div class="w-100 w-md-50 location-div">
                                    <label for="edit-profile-location" class="form-label"> Location </label>
                                    <input type="text" class="form-control" id="edit-profile-location"
                                        name="edit-profile-location" aria-describedby="location">
                                </div>
                            </div>

                            <i class="m-0 small text-secondary"> Note:- This address will be used for dropshipping. </i>

                            <!-- <button type="submit" class="btn rounded" id="update-profile-btn" name="update-profile-btn"> Update </button> -->
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
                            foreach ($userBookList as $bookId) {
                                $bookObj->fetch($bookId);
                                ?>
                                <div class="book-container my-book my-book-active">
                                    <!-- book image -->
                                    <div class="book-image">
                                        <?php $bookObj->setPhotoUrl(); ?>
                                        <img src="<?= $bookObj->photoUrl ?>" alt="">
                                    </div>

                                    <!-- book details -->
                                    <div class="book-details">
                                        <!-- book title -->
                                        <div class="book-title-wishlist">
                                            <p class="book-title"> <?= ucwords($bookObj->title) ?> </p>
                                        </div>

                                        <!-- book purpose -->
                                        <p class="book-purpose"> <?= ucfirst($bookObj->purpose) ?> </p>

                                        <!-- book description -->
                                        <div class="book-description-container">
                                            <p class="book-description"> <?= ucfirst($bookObj->description) ?> </p>
                                        </div>

                                        <!-- book price -->
                                        <div class="book-price">
                                            <p class="book-price">
                                                <?php
                                                if ($bookObj->purpose == "renting") {
                                                    $rent = 0.20 * $bookObj->price['actual'];
                                                    echo "NPR." . number_format($rent, 2) . "/week";
                                                } else {
                                                    $price = $bookObj->price['offer'];
                                                    echo "NPR." . number_format($price, 2);
                                                }
                                                ?>
                                            </p>
                                        </div>

                                        <button class="btn"
                                            onclick="window.location.href='/bookrack/book-details/<?= $bookObj->getId() ?>'">
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
                            <p class="m-0 text-dark"> Add New Book </p>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <!-- wishlist -->
                <?php
                if ($tab == "wishlist") {
                    ?>
                    <!-- skeleton container -->
                    <div class="skeletion-book-main-container" id="skeletion-book-main-container">
                        <!-- skeleton 1 -->
                        <div class="skeleton-book-container">
                            <div class="image"> </div>
                            <div class="text">
                                <p class="skeletion-text"> </p>
                                <p class="skeletion-text"> </p>
                                <p class="skeletion-text"> </p>
                            </div>
                        </div>
                    </div>

                    <!-- my books container-->
                    <div class="d-flex flex-column gap-4 my-book-content wishlist-content" id="wishlist-container"> </div>

                    <div class="empty-div" id="empty-wishlist-div">
                        <img src="/bookrack/assets/icons/empty.svg" alt="" loading="lazy">
                        <p class="empty-message"> Your wishlist is empty! </p>
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
            tab = "<?=$tab?>";

            if (tab == "view") {
                $('#profile-edit-cancel-btn').hide();

                function toggleInputFields(set) {
                    $('#edit-profile-first-name').prop('disabled', set);
                    $('#edit-profile-last-name').prop('disabled', set);
                    $('#edit-profile-dob').prop('disabled', set);
                    $('#edit-profile-gender').prop('disabled', set);
                    $('#edit-profile-contact').prop('disabled', set);
                    $('#eedit-profile-district').prop('disabled', set);
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

                // // first name
                $(document).on('keydown', '#edit-profile-first-name', function(){
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

                // contact name
                $(document).on('keydown', '#edit-profile-contact', function (event) {
                    var asciiValue = event.keyCode || event.which;
                    if(asciiValue >= 48 && asciiValue <=57) {
                        // allow input
                    } else if(asciiValue == 8 || asciiValue == 37 || asciiValue == 39 || asciiValue == 46) {

                    } else {
                        event.preventDefault();
                    }
                });

                loadUserProfileData("<?= $_SESSION['bookrack-user-id'] ?>", "<?= $tab ?>");

                // edit button
                $('#edit-profile-btn').click(function () {
                    $('#profile-edit-cancel-btn').show();
                    toggleInputFields(false);
                });

                // reset btn
                $('#profile-edit-cancel-btn').click(function () {
                    $('#profile-edit-cancel-btn').hide();
                    loadUserProfileData("<?= $_SESSION['bookrack-user-id'] ?>", "<?= $tab ?>");
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
            } else if (tab == 'my-books') {
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

                function toggleMyBooks() {
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
            } else if (tab == "wishlist") {
                $('#empty-wishlist-div').hide();
                // load wishlist
                $.ajax({
                    url: '/bookrack/sections/profile-wishlist.php',
                    type: "POST",
                    success: function (data) {
                        $('#skeletion-book-main-container').remove();
                        $('#wishlist-container').replaceWith(data);
                    }
                });

                // wishlist toggle
                $(document).on('click', '.wishlist-toggle-icon', function () {
                    let wishlist_book_id = $(this).data("book-id");
                    let wishlist_task = $(this).data("task");

                    // $(this).closest("a").html(nextWishlistState);
                    const targetA = $(this).closest("a");

                    const targetContainer = $(this).closest(".book-container");

                    $.ajax({
                        url: '/bookrack/app/toggle-wishlist-home.php',
                        type: "POST",
                        data: { bookId: wishlist_book_id, page: 1 },
                        beforeSend: function () {
                            let nextWishlistState = "";
                            if (wishlist_task == "add") {
                                nextWishlistState = "<i class='fa-solid fa-bookmark wishlist-toggle-icon' data-book-id=" + wishlist_book_id + " data-task='remove'></i>";
                            } else {
                                nextWishlistState = "<i class='fa-regular fa-bookmark wishlist-toggle-icon' data-book-id=" + wishlist_book_id + " data-task='add'></i>";
                            }
                            targetA.html(nextWishlistState);
                            targetContainer.remove();
                            checkPageWishlist();
                        },
                        success: function (response) {
                        }
                    });
                });

                // show empty wishlist message
                function checkPageWishlist() {
                    const bookCount = $('.book-container:visible').length;

                    if (bookCount == 0) {
                        $('#empty-wishlist-div').show();
                    }
                }

            } else if (tab == "requested-books") {
                // request purpose
                $('#request-purpose').change(function () {
                    filterRequestedBooks();
                });

                // request status
                $('#request-state').change(function () {
                    filterRequestedBooks();
                });

                function filterRequestedBooks() {
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
            } else if (tab == "earning") {
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
            }
        });
    </script>
</body>

</html>