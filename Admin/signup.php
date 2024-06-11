<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Admin Signin </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/Assets/Brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/Assets/CSS/bootstrap-css-5.3.3/bootstrap.css">

    <!-- local css -->
    <link rel="stylesheet" href="/Assets/css/style.css">
    <link rel="stylesheet" href="/Assets/css/admin/admin.css">
    <link rel="stylesheet" href="/Assets/css/signin.css">
</head>

<body>
    <!-- main -->
    <main class="main">
        <div class="d-flex flex-column gap-4 m-lg-auto p-lg-5 p-3 py-4 signin-container">
            <!-- heading -->
            <div class="d-flex flex-row justify-content-between heading">
                <!-- brand logo -->
                <img src="/Assets/Brand/bookrack-logo-color.png" alt="" loading="lazy">

                <!-- cancel -->
                <a href="signin.php">
                    <i class="fa fa-multiply fs-2 pointer text-secondary"></i>
                </a>
            </div>

            <!-- bottom content -->
            <div class="d-flex flex-column flex-lg-row gap-1 gap-lg-5 content">
                <!-- landing details -->
                <div class="d-flex flex-column gap-4 landing-content">
                    <div class="landing-content-heading">
                        <p class="f-reset fw-bolder"> SIGN UP & <br class="d-none">START RENTING OUT YOUR <br
                            class="d-none">FAVOURITE BOOKS INSTANTLY
                    </p>
                    </div>

                    <div class="landing-content-image">
                        <img src="/Assets/Images/free.jpg" alt="" loading="lazy">
                    </div>
                </div>

                <!-- signin content -->
                <div class="d-nones d-flex flex-column gap-4 gap-md-4 py-4 sign-content">
                    <div class="d-flex flex-column gap-2 heading">
                        <p class="f-reset fs-1"> Welcome Back :) </p>
                        <p class="f-reset text-secondary note"> To keep connected with us please login awith your
                            personal information by email address and password. </p>
                    </div>

                    <!-- sign in form -->
                    <form class="d-flex flex-column signin-form">
                        <!-- error message section -->
                        <p class="f-reset text-danger mb-3"> Error message appears here... </p>

                        <!-- email address -->
                        <div class="input-group mb-3">
                            <span class="input-group-text px-4">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="floatingEmailInput"
                                    placeholder="someone@gmail.com" aria-label="email address"
                                    aria-describedby="email address" required>
                                <label for="floatingEmailInput">Email address</label>
                            </div>
                        </div>

                        <!-- password -->
                        <div class="input-group mb-3">
                            <span class="input-group-text px-4">
                                <i class="fa-solid fa-unlock"></i>
                            </span>
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" id="floatingPasswordInput"
                                    placeholder="********" aria-label="password" aria-describedby="password" required>
                                <label for="floatingPasswordInput">Password</label>
                            </div>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3 mt-3 action">
                            <button type="submit" class="btn signup-btn" id="signup-btn" name="signup-btn"> Signup </button>
                            <a href="signin.php" class="btn"> Already have account </a>
                        </div>
                    </form>
                </div>

                <!-- email verification content -->
                <div class="d-none d-flex flex-column gap-4 gap-md-4 py-4 sign-content">
                    <div class="d-flex flex-column gap-2 heading">
                        <p class="f-reset fs-1"> Email Verification </p>
                        <p class="f-reset text-secondary note"> Enter the OTP code sent to your email address. </p>
                    </div>

                    <!-- sign up form -->
                    <form class="d-flex flex-column signin-form email-cerification-form" action="home.php">
                        <!-- error message section -->
                        <p class="f-reset text-danger mb-3"> Error message appears here... </p>

                        <!-- otp -->
                        <div class="mb-3">
                            <input type="text" class="form-control" name="otp" id="otp" aria-describedby="otp" maxlength="4" required>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3 action">
                            <button type="submit" name="signup-btn" class="btn" id="signup-btn"> Verify Now </button>
                            <a href="signup.php" class="btn"> Didn't get OTP </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- jquery -->
    <script src="/Assets/Js/jquery-3.7.1.min.js"> </script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file  -->
    <script src="/Assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>
</body>

</html>