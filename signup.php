<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

// if user is saved in the session, redirect to homepage
if (isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Signup </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/signin.css">
</head>

<body>
    <!-- main -->
    <main class="main">
        <div class="d-flex flex-column gap-4 m-lg-auto p-lg-5 p-3 py-4 signin-container">
            <!-- heading -->
            <div class="d-flex flex-row justify-content-between heading">
                <!-- brand logo -->
                <img src="/bookrack/assets/brand/bookrack-logo-color.png" alt="" loading="lazy">

                <!-- cancel -->
                <a href="/bookrack/landing">
                    <i class="fa fa-multiply fs-2 pointer text-secondary"></i>
                </a>
            </div>

            <!-- bottom content -->
            <div class="d-flex flex-column flex-lg-row gap-1 gap-lg-5 content">
                <!-- landing details -->
                <div class="d-flex flex-column gap-4 landing-content">
                    <div class="landing-content-heading">
                        <p class="m-0 fw-bolder"> SIGN UP & <br class="d-none">START RENTING OUT YOUR <br
                                class="d-none">FAVOURITE BOOKS INSTANTLY
                        </p>
                    </div>

                    <div class="landing-content-image">
                        <img src="/bookrack/assets/images/free.jpg" alt="" loading="lazy">
                    </div>
                </div>

                <!-- signup content -->
                <?php
                if ($tab == 'email') {
                    ?>
                    <div class="d-flex flex-column gap-4 gap-md-4 py-4 sign-content">
                        <div class="d-flex flex-column gap-2 heading">
                            <p class="m-0 fs-1"> Hello :) </p>
                            <p class="m-0 text-secondary note"> To keep connected with us please login awith your
                                personal information by email address and password. </p>
                        </div>

                        <!-- sign up form -->
                        <form method="POST" action="" class="d-flex flex-column signin-form" id="signup-form">
                            <!-- message section -->
                            <p class="m-0 mb-3 text-danger" id="signup-message"> Error message appears here </p>

                            <!-- email address -->
                            <div class="input-group mb-3">
                                <span class="input-group-text px-4">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control" id="user-password"
                                        placeholder="someone@gmail.com" aria-label="email address"
                                        aria-describedby="email address" required>
                                    <label for="user-password">Email address</label>
                                </div>
                            </div>

                            <!-- password -->
                            <div class="input-group mb-3">
                                <span class="input-group-text px-4">
                                    <i class="fa-solid fa-unlock"></i>
                                </span>
                                <div class="form-floating">
                                    <input type="password" name="password" class="form-control" id="user-email"
                                        placeholder="********" aria-label="password" aria-describedby="password"
                                        minlength="8" required>
                                    <label for="user-email">Password</label>
                                </div>
                            </div>

                            <input type="hidden" class="form-control" id="csrf_token" name="csrf_token">

                            <div class="mb-3">
                                <p class="m-0 text-secondary"> Note: make sure you complete your profile setting to use
                                    the featured we provide. </p>
                            </div>

                            <div class="d-flex flex-row flex-wrap gap-3 action">
                                <button type="submit" name="signup-btn" class="btn" id="signup-btn"> Signup Now </button>
                                <a href="signin" class="btn btn-light"> Already have an account </a>
                            </div>
                        </form>
                    </div>
                    <?php
                }
                ?>

                <?php
                if ($tab == "email-verification") {
                    ?>
                    <!-- email verification content -->
                    <div class="d-flex flex-column gap-4 gap-md-4 py-4 sign-content">
                        <div class="d-flex flex-column gap-2 heading">
                            <p class="m-0 fs-1"> Email Verification </p>

                            <p class="m-0 text-secondary"> Enter the OTP code. </p>

                            <?php
                            if (isset($_SESSION['status'])) {
                                ?>
                                <p class="m-0 text-danger mt-3 mb-3"> <?php echo $_SESSION['status-message']; ?> </p>
                                <?php
                            }
                            ?>
                        </div>

                        <!-- sign up form -->
                        <form action="/bookrack/signup/authentication"
                            class="d-flex flex-column signin-form email-verification-form" method="POST" autocomplete="off">
                            <!-- otp -->
                            <div class="mb-3">
                                <input type="text" class="form-control" name="otp" id="otp" aria-describedby="otp"
                                    maxlength="4" required>
                            </div>

                            <div class="d-flex flex-row flex-wrap gap-3 action">
                                <button type="submit" name="otp-btn" class="btn" id="otp-btn"> Verify Now </button>
                                <a href="" class="btn"> Resend OTP </a>
                            </div>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <script>
        $(document).ready(function () {
            // Generate CSRF token and set it to the forms
            function setCSRFToken() {
                $.get('app/csrf-token.php', function (data) {
                    $('#csrf_token').val(data);
                });
            }

            setCSRFToken();

            $('#signup-message').hide();

            // password input
            // prevent space as input
            $('#user-email').keydown(function () {
                var asciiValue = event.keyCode || event.which;
                if (asciiValue == 32) {
                    event.preventDefault();
                }
            });

            // email input
            $('#user-password').keydown(function () {
                var asciiValue = event.keyCode || event.which;
                if (asciiValue == 32) {
                    event.preventDefault();
                }
            });

            // form submission
            $('#signup-form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'app/signup.php',
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response == "true") {
                            $('#signup-form').trigger("reset");
                            window.location.href = "/bookrack/signin";
                            $('#signup-message').html("").hide();
                        } else {
                            $('#signup-message').html(response).show();
                            $('#signup-btn').html("Signup Now");
                            $('#signup-btn').prop("disabled", false);
                        }
                    },
                    beforeSend: function () {
                        $('#signup-btn').prop("disabled", true);
                        $('#signup-btn').html("Please wait...");
                    },
                    error: function () {
                        $('#signup-message').html(response).show();
                        $('#signup-btn').html("Signup Now");
                        $('#signup-btn').prop("disabled", false);
                    }
                });
            });
        });
    </script>
</body>

</html>