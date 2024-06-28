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
    <title> Signin </title>

    <?php require_once __DIR__ . '/../bookrack/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/signin.css">
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
                        <p class="f-reset fw-bolder"> SIGN IN & <br class="d-none">START RENTING OUT YOUR <br
                                class="d-none">FAVOURITE BOOKS INSTANTLY
                        </p>
                    </div>

                    <div class="landing-content-image">
                        <img src="/bookrack/assets/images/free.jpg" alt="" loading="lazy">
                    </div>
                </div>

                <!-- signin content -->
                <div class="d-flex flex-column gap-4 gap-md-4 py-4 sign-content">
                    <div class="d-flex flex-column gap-2 heading">
                        <p class="f-reset fs-1"> Welcome Back :) </p>
                        <p class="f-reset text-secondary note"> To keep connected with us please login awith your
                            personal information by email address and password. </p>
                    </div>

                    <!-- sign in form -->
                    <form class="d-flex flex-column signin-form" action="/bookrack/app/authentication.php" method="POST" autocomplete="on">
                        <!-- status message section -->
                        <?php
                        if (isset($_SESSION['status'])) {
                            ?>
                            <p class="f-reset <?php echo $_SESSION['status'] ? "text-success" : "text-danger"; ?>  mb-3">
                                <?php echo $_SESSION['status-message'] ?> </p>
                            <?php
                        }
                        ?>

                        <!-- email address -->
                        <div class="input-group mb-3">
                            <span class="input-group-text px-4">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="floatingEmailInput"
                                    value="<?php if (isset($_SESSION['temp-email']))
                                        echo $_SESSION['temp-email']; ?>"
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
                                    value="<?php if (isset($_SESSION['temp-password']))
                                        echo $_SESSION['temp-password']; ?>"
                                    placeholder="********" aria-label="password" aria-describedby="password"
                                    minlength="8" required>
                                <label for="floatingPasswordInput">Password</label>
                            </div>
                        </div>

                        <div
                            class="d-none d-flex flex-row gap-3 flex-wrap justify-content-between align-items-center remember-me-forgot-password mb-3">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember-me" name="remember-me">
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>

                            <a href="/bookrack/forgot-password/email"> Forgot Password? </a>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3 action">
                            <button type="submit" class="btn" name="signin-btn" id="signin-btn"> Sign in </button>
                            <a href="/bookrack/signup" class="btn"> Create Account </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php 
    unset($_SESSION['status']);
    unset($_SESSION['status-message']);
    ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../bookrack/app/script-include.php'; ?>

    <!-- js :: current file -->
    <script>
        // password input
        // prevent space as input
        $('#floatingPasswordInput').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });

        // email input
        $('#floatingEmailInput').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>