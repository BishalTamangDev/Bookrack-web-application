<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> 404 - Page Not Found </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <link rel="stylesheet" href="/bookrack/css/404.css">
</head>

<body>
    <main class="main">
        <section
            class="d-flex flex-column-reverse flex-lg-row section container m-auto w-100 w-lg-75 gap-5 page-not-found-section">
            <div class="d-flex flex-column gap-3 w-75 page-not-found-detail">
                <h2 class="m-0 fw-bold title"> Oops.</h2>
                <p class="m-0 sub-title"> Sorry, the page you are looking for does not exist.</p>
                <p class="m-0 text-secondary description">
                    You may have mistyped the address or the page may have moved.
                </p>
                <ul>
                    <li><a href="/bookrack/home">Home</a></li>
                </ul>
            </div>

            <div class="w-50 page-not-found-image">
                <img src="/bookrack/assets/icons/empty.svg" alt="" loading="lazy">
            </div>
        </section>
    </main>
</body>

</html>