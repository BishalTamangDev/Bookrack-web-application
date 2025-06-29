<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Signin </title>

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
                <a href="/bookrack/">
                    <i class="fa fa-multiply fs-2 pointer text-secondary"></i>
                </a>
            </div>

            <!-- bottom content -->
            <div class="d-flex flex-column flex-lg-row gap-1 gap-lg-5 content">
                <!-- landing details -->
                <div class="d-flex flex-column gap-4 landing-content">
                    <div class="landing-content-heading">
                        <p class="m-0 fw-bolder"> SIGN IN & <br class="d-none">START RENTING OUT YOUR <br
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
                        <p class="m-0 fs-1"> Welcome Back :) </p>
                        <p class="m-0 text-secondary note"> To keep connected with us please login awith your
                            personal information by email address and password. </p>
                    </div>

                    <!-- sign in form -->
                    <form method="POST" class="d-flex flex-column signin-form" id="signin-form" autocomplete="on">
                        <!-- message section -->
                        <p class="m-0 mb-3 text-danger error-message" id="signin-message"> Message appears here.. </p>

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
                                <input type="password" name="password" id="password" class="form-control"
                                    id="floatingPasswordInput" placeholder="********" aria-label="password"
                                    aria-describedby="password" minlength="8" required>
                                <label for="floatingPasswordInput">Password</label>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" id="csrf_token" name="csrf_token">

                        <!-- password toggle -->
                        <div class="d-flex flex-row gap-2 mt-3 mb-3 pointer fit-content password-toggle-div"
                            id="password-toggle-div">
                            <i class="pt-1 fa fa-eye" id="show-password-icon"></i>
                            <i class="d-none pt-1 fa-regular fa-eye-slash" id="hide-password-icon"></i>
                            <span id="password-toggle-div-label"> Show Password </span>
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

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- script -->
    <script>
        $(document).ready(function () {
            // Generate CSRF token and set it to the forms
            function setCSRFToken() {
                $.get('/bookrack/app/csrf-token.php', function (data) {
                    $('#csrf_token').val(data);
                });
            }

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

            setCSRFToken();

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
            $('#signin-form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'app/signin.php',
                    type: "POST",
                    data: $(this).serialize(),
                    beforeSend: function () {
                        $('#signin-btn').html("Signing in...").prop("disabled", true);
                    },
                    success: function (response) {
                        if (response == "true") {
                            $('#signin-message').html("").hide();
                            window.location.href = "/bookrack/home";
                            $('#signin-form').trigger("reset");
                        } else {
                            $('#signin-message').html(response).show();
                            $('#signin-btn').html("Signin").prop("disabled", false);
                        }
                    },
                    error: function () {
                        $('#signin-message').html(response).show();
                        $('#signin-btn').html("Signin Now");
                        $('#signin-btn').prop("disabled", false);
                    }
                });
            });

            // toggle password
            $('#password-toggle-div').click(function () {
                var type = $('#password').attr('type') === 'password' ? 'text' : 'password';
                $('#password').attr('type', type);
                if (type === 'password') {
                    $('#hide-password-icon').addClass('d-none');
                    $('#show-password-icon').removeClass('d-none');
                    $('#password-toggle-div-label').html("Show Password");
                } else {
                    $('#hide-password-icon').removeClass('d-none');
                    $('#show-password-icon').addClass('d-none');
                    $('#password-toggle-div-label').html("Hide Password");
                }
            });
        });
    </script>
</body>

</html>