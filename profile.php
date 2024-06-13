<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Home </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/Assets/Brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.min.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/Assets/css/navbar.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/header.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/footer.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/book.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/profile.css">
</head>

<body>
    <!-- header -->
    <?php include 'header.php';?>

    <!-- main -->
    <main class="d-flex d-column flex-lg-row gap-lg-4 container main">
        <!-- aside :: profile detail  -->
        <aside class="aside gap-3" id="aside">
            <!-- profile section -->
            <section class="d-flex flex-column p-3 py-4 mb-4 gap-3 profile-section">
                <!-- profile picture -->
                <div class="d-flex flex-column align-items-center gap-2 profile-top">
                    <div class="profile-image">
                        <img src="/bookrack/assets/Images/user-1.png" alt="profile picture">
                    </div>
                    <p class="f-reset text-secondary"> Rupak Dangi </p>
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
                            <p class="f-reset"> Kathmandu </p>
                        </div>
                    </div>

                    <!-- membership -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-user"></i>
                            <span> Member since </span>
                        </div>
                        <div class="data-div">
                            <p class="f-reset"> May 6, 2024 </p>
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
                            <p class="f-reset fw-bold"> 12 </p>
                        </div>
                    </div>

                    <!-- books offered -->
                    <div class="d-flex flex-row profile-detail">
                        <div class="title-div">
                            <i class="fa fa-none"></i>
                            <span>Books consumed </span>
                        </div>

                        <div class="data-div">
                            <p class="f-reset fw-bold"> 47 </p>
                        </div>
                    </div>
                </div>

                <button class="btn" id="edit-profile-btn"
                    onclick="window.location.href='/bookrack/profile/edit-profile'"> Edit </button>
            </section>
        </aside>

        <!-- article -->
        <article class="article bg-md-success mb-4 bg-sm-danger">
            <!-- tab -->
            <section class="d-flex flex-row flex-wrap gap-3 gap-md-3 mt-1 mb-4 tab-section">
                <!-- profile tab -->
                <div class="tab">
                    <p onclick="window.location.href='/bookrack/profile/view-profile'"> MY PROFILE </p>
                    <div class="indicator <?php echo ($tab == "view-profile") ? "active" : "inactive"; ?>"></div>
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

            <!-- account state notification note div -->
            <section
                class="d-flex flex-row gap-2 justify-content-between border rounded p-3 mb-4 account-state-section">
                <p class="f-reset text-justify text-danger" id="account-state-message">
                    Note: Complete setting up your details to get access to all the feature.
                </p>
            </section>

            <!-- contents -->
            <section class="d-flex flex-column gap-5 contents">
                <!-- change password -->
                <div class="<?php if ($tab != "password-change")
                    echo "d-none"; ?> d-flex flex-column gap-3 password-change-content">
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
                    <form method="POST" class="d-flex flex-column gap-4 password-change-form">
                        <!-- old password -->
                        <div class="form-floating">
                            <input type="password" class="form-control" id="old-password" name="old-password"
                                placeholder="Password required">
                            <label for="old-password"> Old password </label>
                        </div>

                        <!-- new password -->
                        <div class="form-floating">
                            <input type="password" class="form-control" id="new-password" name="new-password"
                                placeholder="Password" required>
                            <label for="new-password"> New password </label>
                        </div>

                        <!-- new password confirmation -->
                        <div class="form-floating">
                            <input type="password" class="form-control" id="new-password-confirmation"
                                name="new-password-confirmation" placeholder="Password" required>
                            <label for="new-password-confirmation"> New password confirmation </label>
                        </div>

                        <div class="d-flex flex-row  align-items-center gap-2 pointer show-hide-password-div"
                            id="show-hide-password">
                            <i class="fa fa-eye"></i>
                            <p class="f-reset"> Show password </p>
                        </div>

                        <button type="submit" class="btn" id="update-password-btn"> Update Password </button>
                    </form>
                </div>

                <!-- edit profile -->
                <div class="<?php if ($tab != "edit-profile" && $tab != "view-profile")
                    echo "d-none"; ?>  d-flex flex-column gap-3 edit-profile-content">
                    <!-- top-section -->
                    <div class="d-flex flex-row align-items-center justify-content-between mb-2 gap-3 top-section">
                        <!-- heading -->
                        <div class="d-flex flex-row align-items-center gap-2 heading">
                            <i class="fa fa-edit fs-4 text-secondary"></i>
                            <h4 class="f-reset">  <?php echo $tab=="view-profile"? "My Profile":"Edit Profile"; ?> </h4>
                        </div>

                        <!-- form reset btn -->
                         <div class="d-flex flex-row gap-2 action">

                             <button class="btn btn-warning text-white <?php if($tab=="view-profile") echo "d-none";?>" onclick="window.location.href='/bookrack/profile/edit-profile'">
                                 Reset </button>
                                
                                <!-- cancel btn -->
                            <button class="btn btn-danger <?php if($tab=="view-profile") echo "d-none";?>" onclick="window.location.href='/bookrack/profile/view-profile'">
                                Cancel </button>
                            </div>
                            </div>

                    <!-- edit profile deatail form -->
                    <form class="d-flex flex-column gap-4 edit-profile-form">
                        <!-- profile picture & password-->
                        <div class="d-flex flex-column flex-md-row gap-3 align-items-center profile-pic-password-div">
                            <div class="<?php if($tab=="view-profile") echo "d-none";?> w-100 w-md-50 flex-grow-1 profile-picture">
                                <label for="edit-profile-profile-picture" class="form-label text-secondary"> Change
                                    profile picture </label>
                                <input type="file" name="edit-profile-profile-picture"
                                    class="border rounded form-control" id="edit-profile-profile-picture">
                            </div>

                            <div
                                class="d-flex flex-row flex-grow-1 gap-2 align-items-center w-100 w-md-50 password-div">
                                <div class="d-flex flex-row gap-2 align-items-center bg-dark change-password"
                                    onclick="window.location.href='/bookrack/profile/password-change'">
                                    <i class="fa fa-lock text-light"></i>
                                    <p class="f-reset text-light"> Change Password </p>
                                </div>
                            </div>
                        </div>

                        <!-- name -->
                        <div class="d-flex flex-column flex-md-row gap-3 flex-wrap">
                            <div class="flex-grow-1 first-name-div">
                                <label for="edit-profile-first-name" class="form-label">First name </label>
                                <input type="email" class="form-control" id="edit-profile-first-name" value="first name"
                                    name="edit-profile-first-name" aria-describedby="emailHelp" <?php if($tab=="view-profile") echo "disabled";?>>
                            </div>

                            <div class="flex-grow-1 last-name-div">
                                <label for="edit-profile-last-name" class="form-label">Last name</label>
                                <input type="email" class="form-control" id="edit-profile-last-name" value="last name"
                                    name="edit-profile-last-name" aria-describedby="emailHelp" <?php if($tab=="view-profile") echo "disabled";?>>
                            </div>
                        </div>

                        <!-- date of birth & gender -->
                        <div class="d-flex flex-column flex-md-row gap-3 dob-gender">
                            <!-- date of birth -->
                            <div class="d-flex flex-column w-100 w-md-50 dob-div">
                                <label for="edit-profile-dob" class="form-label"> Date of birth </label>
                                <input type="date" class="p-2" name="edit-profile-dob" <?php if($tab=="view-profile") echo "disabled";?>>
                            </div>

                            <!-- gender -->
                            <div class="d-flex flex-column w-100 w-md-50 flex-grow-1">
                                <label for="edit-profile-gender" class="form-label"> Gender </label>
                                <select class="form-select" name="edit-profile-gender"
                                    aria-label="Default select example" <?php if($tab=="view-profile") echo "disabled";?>>
                                    <option value="0" selected hidden>Select gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Others</option>
                                </select>
                            </div>
                        </div>

                        <!-- address -->
                        <div class="d-flex flex-column flex-md-row gap-3 address-div">
                            <!-- district -->
                            <div class="w-100 w-md-50 district-div">
                                <label for="edit-profile-district" class="form-label"> District </label>
                                <select class="form-select" name="edit-profile-district"
                                    aria-label="Default select example" <?php if($tab=="view-profile") echo "disabled";?>>
                                    <option value="0" selected hidden> Select district </option>
                                    <option value="1"> DIstrict 1 </option>
                                    <option value="2"> DIstrict 2 </option>
                                    <option value="3"> DIstrict 3 </option>
                                    <option value="4"> DIstrict 4 </option>
                                    <option value="5"> DIstrict 5 </option>
                                </select>
                            </div>

                            <!-- location -->
                            <div class="w-100 w-md-50 location-div">
                                <label for="edit-profile-location" class="form-label"> Location </label>
                                <input type="email" class="form-control" id="edit-profile-location" value="location name"
                                    name="edit-profile-location" aria-describedby="emailHelp" <?php if($tab=="view-profile") echo "disabled";?>>
                            </div>
                        </div>

                        <i class="f-reset small text-secondary"> Note:- This address will be used for dropshipping. </i>

                        <button type="submit" class="btn <?php if($tab=="view-profile") echo "d-none";?>" id="update-profile-btn"> Update </button>
                    </form>
                </div>

                <!-- my books -->
                <div class="<?php if ($tab != "my-books")
                    echo "d-none"; ?> d-flex flex-column gap-4 my-book-content">
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
                        <!-- book container :: dummy data 1 -->
                        <div class="book-container my-book my-book-active">
                            <!-- book image -->
                            <div class="book-image">
                                <img src="/bookrack/assets/Images/cover-1.jpeg" alt="">
                            </div>

                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title">
                                    <p class="book-title"> To Kill a Mockingbird </p>
                                    <i class="fa-regular fa-bookmark"></i>
                                </div>

                                <!-- book purpose -->
                                <p class="book-purpose"> Renting </p>

                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> Set in the American South during the 1930s, this
                                        classic
                                        novel explores themes of racial injustice and moral growth through the eyes of
                                        Scout
                                        Finch, a young girl whose father, Atticus Finch, is ... </p>
                                </div>

                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price"> NRs. 85 </p>
                                </div>

                                <button class="btn" onclick="window.location.href='/bookrack/book-details'"> Show More
                                </button>
                            </div>
                        </div>

                        <!-- book container :: dummy data 2 -->
                        <div class="book-container my-book my-book-inactive">
                            <!-- book image -->
                            <div class="book-image">
                                <img src="/bookrack/assets/Images/cover-2.png" alt="">
                            </div>

                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title">
                                    <p class="book-title"> Don't Look Back </p>
                                    <i class="fa-regular fa-bookmark"></i>
                                </div>

                                <!-- book purpose -->
                                <p class="book-purpose"> Selling </p>

                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> Set in the American South during the 1930s, this
                                        classic
                                        novel explores themes of racial injustice and moral growth through the eyes of
                                        Scout
                                        Finch, a young girl whose father, Atticus Finch, is ... </p>
                                </div>

                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price"> NRs. 170 </p>
                                </div>

                                <button class="btn" onclick="window.location.href='/bookrack/book-details'"> Show More
                                </button>
                            </div>
                        </div>

                        <!-- book container :: dummy data 3 -->
                        <div class="book-container my-book my-book-sold-out">
                            <!-- book image -->
                            <div class="book-image">
                                <img src="/bookrack/assets/Images/cover-3.jpg" alt="">
                            </div>

                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title">
                                    <p class="book-title"> Intuition </p>
                                    <i class="fa-regular fa-bookmark"></i>
                                </div>

                                <!-- book purpose -->
                                <p class="book-purpose"> Selling </p>

                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> Set in the American South during the 1930s, this
                                        classic
                                        novel explores themes of racial injustice and moral growth through the eyes of
                                        Scout
                                        Finch, a young girl whose father, Atticus Finch, is ... </p>
                                </div>

                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price"> NRs. 170 </p>
                                </div>

                                <button class="btn" onclick="window.location.href='/bookrack/book-details'"> Show More
                                </button>
                            </div>
                        </div>
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

                <!-- wishlist -->
                <!-- my books container-->
                <div class="<?php if ($tab != "wishlist")
                    echo "d-none"; ?>  d-flex flex-column gap-4 my-book-content wishlist-content">
                    <div class="d-flex flex-row flex-wrap gap-3 wishlist-container">
                        <!-- book container :: dummy data 1 -->
                        <div class="book-container">
                            <!-- book image -->
                            <div class="book-image">
                                <img src="/bookrack/assets/Images/cover-1.jpeg" alt="">
                            </div>

                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title">
                                    <p class="book-title"> To Kill a Mockingbird </p>
                                    <i class="fa-regular fa-bookmark"></i>
                                </div>

                                <!-- book purpose -->
                                <p class="book-purpose"> Renting </p>

                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> Set in the American South during the 1930s, this
                                        classic
                                        novel explores themes of racial injustice and moral growth through the eyes of
                                        Scout
                                        Finch, a young girl whose father, Atticus Finch, is ... </p>
                                </div>

                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price"> NRs. 85 </p>
                                </div>

                                <button class="btn" onclick="window.location.href='/bookrack/book-details'"> Show More
                                </button>
                            </div>
                        </div>

                        <!-- book container :: dummy data 2 -->
                        <div class="book-container">
                            <!-- book image -->
                            <div class="book-image">
                                <img src="/bookrack/assets/Images/cover-2.png" alt="">
                            </div>

                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title">
                                    <p class="book-title"> Don't Look Back </p>
                                    <i class="fa-regular fa-bookmark"></i>
                                </div>

                                <!-- book purpose -->
                                <p class="book-purpose"> Selling </p>

                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> Set in the American South during the 1930s, this
                                        classic
                                        novel explores themes of racial injustice and moral growth through the eyes of
                                        Scout
                                        Finch, a young girl whose father, Atticus Finch, is ... </p>
                                </div>

                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price"> NRs. 170 </p>
                                </div>

                                <button class="btn" onclick="window.location.href='/bookrack/book-details'"> Show More
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="empty-div">
                        <img src="/bookrack/assets/Icons/empty.svg" alt="">
                        <p class="empty-message"> Your wishlist is empty! </p>
                    </div>
                </div>

                <!-- requested books -->
                <div class="<?php if ($tab != "requested-books")
                    echo "d-none"; ?> d-flex flex-column gap-4 requested-book-content">
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

                <!-- earning -->
                <div class="<?php if ($tab != "earning")
                    echo "d-none"; ?> d-flex flex-column gap-4 requested-book-content earning-content">
                    <!-- earning filter -->
                    <div class="d-flex flex-row gap-2 requested-book-filter">
                        <select class="form-select" name="earning-purpose" id="earning-purpose" aria-label="earning purpose select">
                            <option value="earning-purpose-all" selected> All Purpose </option>
                            <option value="earning-purpose-rent"> Rent </option>
                            <option value="earning-purpose-buy-sell"> Buy/ Sell </option>
                        </select>

                        <select class="form-select" name="earning-state" name="earning-state" id="earning-state" aria-label="earning state select">
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
                                <tr
                                    class="earning-tr earning-purpose-rent-tr earning-state-active-tr">
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
            </section>
        </article>
    </main>

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
        $('#earning-purpose').change(function(){
            filterEarning();
        });

        // earning state
        $('#earning-state').change(function(){
            filterEarning();
        });
            
        filterEarning = () =>{
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