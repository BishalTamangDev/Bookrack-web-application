<?php
// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['bookrack-user-id'])){
    header("Location: /bookrack/home");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Signin </title>

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
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
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
                    <form class="d-flex flex-column signin-form" action="/bookrack/signin/code-authentication" method="POST">
                        <!-- error message section -->
                        <?php
                        if(isset($_SESSION['status'])){
                            ?>
                            <p class="f-reset text-danger mb-3"> <?php echo $_SESSION['status']?> </p>
                            <?php
                            unset($_SESSION['status']);
                        }
                        ?>

                        <!-- email address -->
                        <div class="input-group mb-3">
                            <span class="input-group-text px-4">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="floatingEmailInput" value="<?php if(isset($_SESSION['temp-email'])) echo $_SESSION['temp-email'];?>"
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
                                <input type="password" name="password" class="form-control" id="floatingPasswordInput" value="<?php if(isset($_SESSION['temp-password'])) echo $_SESSION['temp-password'];?>"
                                    placeholder="********" aria-label="password" aria-describedby="password" minlength="8" required>
                                <label for="floatingPasswordInput">Password</label>
                            </div>
                        </div>

                        <div
                            class="d-flex flex-row gap-3 flex-wrap justify-content-between align-items-center remember-me-forgot-password mb-3">
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

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.min.css"></script>

    <!-- js :: current file -->
    <script>
        // password input
        // prevent space as input
        $('#floatingPasswordInput').keydown(function(){
            var asciiValue = event.keyCode || event.which;
            if(asciiValue == 32){
                event.preventDefault();
            }
        });
        
        // email input
        $('#floatingEmailInput').keydown(function(){
            var asciiValue = event.keyCode || event.which;
            if(asciiValue == 32){
                event.preventDefault();
            }
        });
    </script>
</body>

</html>