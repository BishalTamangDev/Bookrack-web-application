<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Password Recovery </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/signin.css">
    <link rel="stylesheet" href="/bookrack/css/forgot-password.css">
</head>

<body>
    <!-- main -->
    <main class="main">
        <div class="d-flex flex-column gap-4 m-md-auto p-lg-5 p-3 py-4 signin-container">
            <!-- heading -->
            <div class="d-flex flex-row justify-content-between heading">
                <!-- brand logo -->
                <img src="/bookrack/assets/brand/bookrack-logo-color.png" alt="" loading="lazy">

                <!-- cancel -->
                <a href="/bookrack/signin">
                    <i class="fa fa-multiply fs-2 pointer text-secondary"></i>
                </a>
            </div>

            <!-- bottom content -->
            <div class="d-flex flex-column gap-1 gap-lg-5 content">
                <!-- email -->
                <div class="<?php if ($tab != "email")
                    echo "d-none"; ?> d-flex flex-column gap-4 gap-md-4 py-4 email-content">
                    <div class="d-flex flex-column gap-2 heading">
                        <p class="f-reset fs-1"> Don't worry :) </p>
                        <p class="f-reset text-secondary note"> We'll send <code>OTP</code> code to the following email
                            address. Use it for further processing. </p>
                    </div>

                    <!-- email form -->
                    <form action="/bookrack/forgot-password/otp" method="POST"
                        class="d-flex flex-column forgot-password-email-form">
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

                        <div
                            class="d-flex flex-row gap-3 flex-wrap justify-content-between remember-me-forgot-password mb-3">
                            <a href="/bookrack/forgot-password/otp" class="float float-right"> Already have an OTP code?
                            </a>
                        </div>

                        <div class="action">
                            <button type="submit" class="btn btn-warning text-light" name="send-otp-btn"
                                id="send-otp-btn"> Send OTP </button>
                        </div>
                    </form>
                </div>

                <!-- otp -->
                <div class="<?php if ($tab != "otp")
                    echo "d-none"; ?> d-flex flex-column gap-4 gap-md-4 py-4 otp-content">
                    <div class="d-flex flex-column gap-2 heading">
                        <p class="f-reset fs-1"> Got OTP code? </p>
                        <p class="f-reset note">
                            Enter the OTP code send to <span class="text-secondary">someone@gmail.com</span>
                        </p>
                    </div>

                    <!-- otp form -->
                    <form action="/bookrack/forgot-password/reset-password" method="POST"
                        class="d-flex flex-column forgot-password-email-form">
                        <!-- error message section -->
                        <p class="f-reset text-danger mb-3"> Error message appears here... </p>

                        <!-- otp -->
                        <div class="mb-3">
                            <input type="text" class="form-control" name="otp" id="otp" aria-describedby="otp"
                                maxlength="4" required>
                        </div>

                        <div
                            class="d-flex flex-row gap-3 flex-wrap justify-content-between remember-me-forgot-password mb-3">
                            <a href="/bookrack/forgot-password/email"> Didn't get the code? </a>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3 action">
                            <button type="submit" class="btn btn-warning text-light" name="verify-otp-btn"
                                id="verify-otp-btn"> Proceed </button>
                        </div>
                    </form>
                </div>

                <!-- password reset -->
                <div class="<?php if ($tab != "reset-password")
                    echo "d-none"; ?> d-flex flex-column gap-4 gap-md-4 py-4 password-reset-content">
                    <div class="d-flex flex-column gap-2 heading">
                        <p class="f-reset fs-1"> You're one step away from recovering your password :) </p>
                    </div>

                    <!-- password reset form -->
                    <form action="/bookrack/signin" method="POST"
                        class="d-flex flex-column gap-3 forgot-password-email-form">
                        <!-- error message section -->
                        <p class="f-reset text-danger mb-3"> Error message appears here... </p>

                        <!-- password -->
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>

                        <!-- retype password -->
                        <div class="form-floating">
                            <input type="password" class="form-control" name="retype-password" id="retype-password"
                                placeholder="Re-Enter the Password" required>
                            <label for="floatingPassword">Re-type the Password</label>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3 action">
                            <button type="submit" class="btn btn-warning text-light" name="reset-password-btn"
                                id="reset-password-btn"> Reset Password </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

</body>

</html>