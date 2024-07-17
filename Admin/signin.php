<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-dashboard");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Admin Signin </title>

    <?php require_once __DIR__ . '/../app/header-include.php' ?>

    <!-- local css -->
    <link rel="stylesheet" href="/bookrack/assets/css/signin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/signin.css">
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
                <a href="/bookrack/home">
                    <i class="fa fa-multiply fs-2 pointer text-secondary"></i>
                </a>
            </div>

            <!-- bottom content -->
            <div class="d-flex flex-column flex-lg-row gap-1 gap-lg-5 content">
                <!-- signin content -->
                <div class="d-flex flex-column w-100 gap-4 gap-md-4 py-4 sign-content">
                    <div class="d-flex flex-column gap-2 heading">
                        <p class="f-reset fs-1 fw-bold"> Admin Sign In </p>
                        <p class="f-reset text-secondary note"> To keep connected with us please signin with your
                            personal information by email address and password. </p>
                    </div>

                    <!-- sign in form -->
                    <form method="POST" action="/bookrack/admin/app/admin-authentication.php"
                        class="d-flex flex-column signin-form" id="admin-signin-form">
                        <!-- session status and status message -->
                        <?php
                        if (isset($_SESSION['status']) && isset($_SESSION['status-message'])) {
                            ?>
                            <p class="m-0 mb-2 <?= $_SESSION['status'] ? "text-success" : "text-danger" ?>">
                                <?= $_SESSION['status-message'] ?>
                            </p>
                            <?php
                        }
                        ?>

                        <!-- email address -->
                        <div class="input-group mb-3">
                            <span class="input-group-text px-4">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <div class="form-floating">
                                <input type="email" name="email" value="<?php
                                if (isset($_SESSION['temp-email'])) {
                                    echo $_SESSION['temp-email'];
                                }
                                ?>" class="form-control" id="admin-email" placeholder="someone@gmail.com"
                                    aria-label="email address" aria-describedby="email address" required>
                                <label for="admin-email">Email address</label>
                            </div>
                        </div>

                        <!-- password -->
                        <div class="input-group mb-3">
                            <span class="input-group-text px-4">
                                <i class="fa-solid fa-unlock"></i>
                            </span>
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" id="admin-password" value="<?php
                                if (isset($_SESSION['temp-password'])) {
                                    echo $_SESSION['temp-password'];
                                }
                                ?>" placeholder="********" aria-label="password" aria-describedby="password"
                                    minlength="8" required>
                                <label for="admin-password">Password</label>
                            </div>
                        </div>

                        <div
                            class="d-none d-flex flex-row gap-3 flex-wrap justify-content-between remember-me-forgot-password mb-3">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember-me"
                                    name="admin-remember-me">
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>

                            <a href=""> Forgot Password? </a>
                        </div>

                        <div class="d-flex flex-row flex-wrap gap-3 action">
                            <button type="submit" class="btn" name="admin-signin-btn" id="signin-btn"> Sign in Now
                            </button>
                            <a href="/bookrack/admin/admin-signup" class="btn"> Create Account </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- unset session status and message -->
    <?php
    unset($_SESSION['status']);
    unset($_SESSION['status-message']);

    unset($_SESSION['temp-email']);
    unset($_SESSION['temp-password']);
    ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../app/script-include.php'; ?>

    <script>
        // password input : // prevent space as input
        $('#admin-password').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });

        // email input
        $('#admin-email').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>