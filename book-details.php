<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Home </title>

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
    <link rel="stylesheet" href="/bookrack/assets/css/navbar.css">
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/header.css">
    <link rel="stylesheet" href="/bookrack/assets/css/footer.css">
    <link rel="stylesheet" href="/bookrack/assets/css/book-details.css">
</head>

<body>
    <!-- header -->
    <?php include 'header.php';?>

    <!-- main -->
    <main class="d-flex flex-column gap-3 pb-5 container main">
        <!-- title, rating, count -->
        <section class="d-flex flex-column title-rating-count-container">
            <!-- book title -->
            <p class="f-reset fw-bold fs-3"> The Black Universe </p>

            <!-- rating & count-->
            <div class="d-flex flex-row gap-2 align-items-center rating-count-container">
                <!-- rating -->
                <div class="d-flex flex-row align-items-center rating-container">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                    <img src="/bookrack/assets/icons/half-rating.png" alt="" loading="lazy">
                </div>

                <!-- count -->
                <p class="f-reset count-container"> (89) </p>
            </div>
        </section>

        <section class="d-flex flex-column flex-lg-row gap-4 book-detail-container">
            <!-- images -->
            <div class="d-flex flex-column flex-md-row flex-lg-column gap-2 book-image-container">
                <!-- top image -->
                <div class="d-flex flex-row top">
                    <div class="book-image">
                        <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy">
                    </div>
                </div>

                <!-- bottom images -->
                <div class="d-flex flex-row flex-md-column flex-lg-row gap-2 bottom">
                    <!-- cover -->
                    <div class="book-image">
                        <abbr title="cover page">
                            <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy">
                        </abbr>
                    </div>

                    <!-- price page -->
                    <div class="book-image">
                        <abbr title="price page">
                            <img src="/bookrack/assets/images/book-3.jpg" alt="" loading="lazy">
                        </abbr>
                    </div>

                    <!-- ISBN page -->
                    <div class="book-image">
                        <abbr title="isbn page">
                            <img src="/bookrack/assets/images/ISBN-1.jpg" alt="" loading="lazy">
                        </abbr>
                    </div>
                </div>
            </div>

            <!-- details -->
            <div class="d-flex flex-column gap-4 mt-2 mt-lg-0 all-detail-container">
                <!-- description & availability status -->
                <div class="d-flex flex-column justify-content-between gap-2 description-availability">
                    <!-- description heading & availability -->
                    <div class="d-flex flex-row justify-content-between description-deading-availability">
                        <p class="p f-reset fw-bold fs-4 text-secondary"> Description </p>

                        <div class="d-flex flex-row align-items-center bg-success px-3 availability-div">
                            <p class="f-reset text-light"> Available for Rent </p>
                        </div>
                    </div>

                    <!-- description container -->
                    <div class="description-container">
                        <!-- description -->
                        <p class="f-reset">
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laboriosam doloribus, perspiciatis
                            harum nostrum doloremque aspernatur error fugit saepe esse nobis distinctio ipsum corrupti
                            totam pariatur excepturi natus at amet, repellat architecto aperiam laborum. Officia
                            voluptatum sequi numquam! Laboriosam in, illo explicabo tenetur modi minima vero
                            voluptatibus totam repellendus nulla dolorum.
                        </p>
                    </div>
                </div>

                <!-- genre container -->
                <div class="d-flex flex-column gap-2 genre-container">
                    <!-- genre heading -->
                    <p class="p f-reset fw-bold fs-4 text-secondary"> Genre </p>

                    <!-- genre list -->
                    <div class="d-flex flex-row gap-2 align-items-center flex-wrap genre-list">
                        <div class="genre">
                            <p> Lorem ipsum dolor sit. </p>
                        </div>

                        <div class="genre">
                            <p> Genre 2 </p>
                        </div>

                        <div class="genre">
                            <p> Genre 3 </p>
                        </div>

                        <div class="genre">
                            <p> Lorem, ipsum. </p>
                        </div>
                    </div>
                </div>

                <!-- author container -->
                <div class="d-flex flex-column gap-2 author-container">
                    <!-- authors heading -->
                    <p class="p f-reset fw-bold fs-4 text-secondary"> Author[s] </p>

                    <!-- author list -->
                    <div class="d-flex flex-row gap-2 align-items-center flex-wrap author-list">
                        <div class="author">
                            <p> Author 1 </p>
                        </div>

                        <div class="author">
                            <p> Lorem, ipsum dolor. </p>
                        </div>

                        <div class="author">
                            <p> Author 3 </p>
                        </div>

                        <div class="author">
                            <p> Lorem, ipsum. </p>
                        </div>
                    </div>
                </div>

                <!-- remaining details -->
                <div class="d-flex flex-column flex-md-row flex-wrap misc-container">
                    <!-- edition -->
                    <div class="misc-div">
                        <div class="title">
                            <p> Edition </p>
                        </div>
                        <div class="data">
                            <p> 5<sup>th</sup></p>
                        </div>
                    </div>

                    <!-- price -->
                    <div class="misc-div">
                        <div class="title">
                            <p> Price </p>
                        </div>
                        <div class="data">
                            <p class="text-success fw-bold"> NRs. 140</p>
                        </div>
                    </div>

                    <!-- owner -->
                    <div class="misc-div">
                        <div class="title">
                            <p> Owner </p>
                        </div>
                        <div class="data">
                            <p> Rupak Dangi </p>
                        </div>
                    </div>

                    <!-- publisher -->
                    <div class="misc-div">
                        <div class="title">
                            <p> Publisher </p>
                        </div>
                        <div class="data">
                            <p> Marvin McKinney </p>
                        </div>
                    </div>

                    <!-- ISBN -->
                    <div class="misc-div">
                        <div class="title">
                            <p> ISBN </p>
                        </div>
                        <div class="data">
                            <p>978-84356-028-9</p>
                        </div>
                    </div>

                    <!-- language -->
                    <div class="misc-div">
                        <div class="title">
                            <p> Language </p>
                        </div>
                        <div class="data">
                            <p> English </p>
                        </div>
                    </div>
                </div>

                <!-- action -->
                <div class="d-flex flex-wrap flex-md-row operation-container">
                    <!-- request button -->
                    <a href="" class="btn" id="request-btn"> REQUEST NOW </a>
                    
                    <!-- wishlist -->
                    <a href="" class="btn" id="wishlist-btn"><i class="fa fa-bookmark"></i>Add to wishlist</a>
                    
                    <!-- add to cart -->
                    <a href="" class="btn" id="cart-btn"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                </div>
            </div>
        </section>
    </main>

    <!-- footer -->

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- js :: current file -->
    <script>
        const openMenu = document.getElementById("open-menu");
        const closeMenu = document.getElementById("close-menu");

        // menu
        const menu = document.getElementById("menu");

        openMenu.addEventListener('click', function () {
            menu.style = "right: 0; transition: .4s";
        });

        closeMenu.addEventListener('click', function () {
            menu.style = "right: -100%; transition: .4s";
        });

        // device width changing
        widthCheck = () => {
        }

        window.addEventListener('resize', widthCheck);


        window.onload = () => {
            widthCheck();
        }
    </script>
</body>

</html>