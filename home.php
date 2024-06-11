<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Home </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="Assets/Brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="assets/css/bootstrap-css-5.3.3/bootstrap.min.css">

    <!-- css files -->
    <link rel="stylesheet" href="Assets/css/navbar.css">
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/header.css">
    <link rel="stylesheet" href="Assets/css/footer.css">
    <link rel="stylesheet" href="Assets/css/book.css">
    <link rel="stylesheet" href="Assets/css/home.css">
</head>

<body>
    <!-- header -->
    <header class="header w-100 border-bottom">
        <div class="container d-flex flex-row p-3 align-items-center justify-content-between header-container">
            <!-- logo -->
            <div class="d-flex flex-row gap-2 align-items-center header-logo pointer"
                onclick="window.location.href='home.php'">
                <img src="assets/Brand/bookrack-logo-black.png" alt="">
                <h3 class="f-reset fw-bold"> Bookrack </h3>
            </div>

            <!-- menu bar -->
            <i class="fa fa-bars pointer fs-3 d-md-none" id="open-menu"></i>

            <!-- menu -->
            <div class="flex-column d-md-flex flex-md-row gap-3 p-4 p-md-0 align-items-center menu" id="menu">
                <!-- close menu -->
                <div class="d-flex d-md-none justify-content-end container p-3 close" id="close-menu">
                    <i class="fa fa-multiply pointer fs-3"></i>
                </div>

                <!-- search -->
                <form action="" class="search-form">
                    <input type="search" name="search-content" id="search" placeholder="search here" class="p-2" required>
                </form>

                <!-- add book -->
                <div class="d-flex flex-row align-items-center gap-2 pointer justify-content-center p-2 px-3 text-white add-book" onclick="window.location.href='add-book.php'">
                    <i class="fa fa-add"></i>
                    <span style="white-space: nowrap;"> ADD BOOK </span>
                </div>

                <!-- wishlist -->
                <div class="d-flex flex-row align-items-center justify-content-center gap-2 border p-2 rounded pointer wishlist" onclick="window.location.href='profile.php?tab=wishlist'">
                    <i class="fa fa-bookmark"></i>
                    <span> Wishlist </span>
                </div>
                
                <!-- cart -->
                <div class="d-flex flex-row align-items-center justify-content-center gap-2 border p-2 rounded pointer cart" onclick="window.location.href='cart.php'">
                    <i class="fa fa-shopping-cart"></i>
                    <span> Cart </span>
                </div>
                
                <!-- profile menu -->
                <div class="position-relative profile-div">
                    <div class="d-none d-md-block profile-photo" id="profile-menu-trigger">
                        <img src="assets/Images/user-2.jpg" alt="" class="pointer">
                    </div>

                    <div class="position-absolute profile-menu" id="profile-menu">
                        <ul>
                            <li onclick="window.location.href='profile.php?'"> <i class="fa fa-user"></i> <span>My Profile</span> </li>
                            <li onclick="window.location.href='profile.php?tab=my-books'"> <i class="fa fa-book"></i> <span>My Books</span> </li>
                            <li onclick="window.location.href='profile.php?tab=earning'"> <i class="fa fa-dollar"></i> <span>Earning</span> </li>
                            <li onclick="window.location.href='index.php'"> <i class="fa fa-sign-out"></i> <span>Sign Out</span> </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>  

    <!-- main -->
    <main class="d-flex flex-row gap-lg-3 pb-5 container main">
        <!-- aside :: filter & advertisement  -->
        <aside class="aside gap-3" id="aside">
            <!-- filter -->
            <section class="filter-section">
                <!-- heading -->
                <section class="d-flex flex-row justify-content-between align-items-center row-heading">
                    <p class="f-reset fw-bold text-secondary"> Filters </p>

                    <div class="d-flex flex-row align-items-center gap-2 icon-container">
                        <i class="fa fa-filter d-none d-lg-block text-secondary"></i>
                        <i class="fa fa-multiply fs-3 d-lg-none text-secondary pointer" id="filter-hide-trigger"></i>
                    </div>
                </section>

                <hr>

                <!-- filter parameters -->
                <section class="mt-3 filter-parameter-section">
                    <form method="GET" class="d-flex flex-column gap-3" id="filter-form">
                        <!-- price -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="min-price" class="form-label"> Price </label>
                                <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <!-- min price -->
                                <input type="number" name="min-price" class="form-control" id="min-price" aria-describedby="min price"
                                    placeholder="Min">

                                <p class="f-reset fw-bold"> - </p>

                                <!-- max price -->
                                <input type="number" name="max-price" class="form-control" id="max-price" aria-describedby="max price"
                                    placeholder="Max">
                            </div>
                        </div>

                        <!-- book category type -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="category" class="form-label"> Purpose </label>
                                <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                            </div>

                            <select class="form-select form-select-md" name="purpose" id="purpose" aria-label="Small select example">
                                <option value="all"> All </option>
                                <option value="renting"> Rent </option>
                                <option value="selling"> Buy </option>
                            </select>
                        </div>

                        <!-- book genre -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="genre" class="form-label"> Genre </label>
                                <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                            </div>

                            <select class="form-select form-select-md" name="genre" id="genre" aria-label="Small select example">
                                <option selected hidden value="all"> All genre </option>
                                <option value="genre1"> Genre 1 </option>
                                <option value="genre2"> Genre 2 </option>
                                <option value="genre3"> Genre 3 </option>
                                <option value="genre4"> Genre 4 </option>
                                <option value="genre5"> Genre 5 </option>
                            </select>
                        </div>

                        <!-- filter button -->
                        <button type="submit" class="filter-btn" id="filter-btn"> Filter </button>
                    </form>
                </section>
            </section>

            <!-- advertisement -->
            <section class="advertisement-section">
                <img src="assets/Images/ad-1.jpg" alt="advertisement" loading="lazy">
            </section>
        </aside>

        <!-- article -->
        <article class="article bg-md-success bg-sm-danger">
            <!-- top genre  -->
            <section class="d-flex flex-row gap-4 flex-wrap align-items-center top-genre-section">
                <p class="f-reset fs-5"> Top Genre </p>

                <div class="d-flex flex-row flex-wrap gap-2 genre-container">
                    <div class="genre">
                        <p class="f-reset text-secondary"> Genre 1 </p>
                    </div>

                    <div class="genre">
                        <p class="f-reset text-secondary"> Genre 2 </p>
                    </div>

                    <div class="genre">
                        <p class="f-reset text-secondary"> Genre 3 </p>
                    </div>

                    <div class="genre">
                        <p class="f-reset text-secondary"> Genre 4 </p>
                    </div>
                </div>
            </section>

            <!-- trending book section -->
            <section class="d-flex flex-column gap-3 section trending-book-section">
                <p class="f-reset fw-bold heading text-secondary fs-4"> Trending Books </p>
                <!-- trending book container -->
                <div class="d-flex flex-row flex-wrap gap-3 trending-book-container">
                    <!-- book container :: dummy data 1 -->
                    <div class="book-container">
                        <!-- book image -->
                        <div class="book-image">
                            <img src="assets/Images/cover-1.jpeg" alt="">
                        </div>
    
                        <!-- book details -->
                        <div class="book-details">
                            <!-- book title -->
                            <div class="book-title">
                                <p class="book-title"> To Kill a Mockingbird </p>
                                <a href="#">
                                    <i class="fa-regular fa-bookmark"></i>
                                </a>
                            </div>
    
                            <!-- book purpose -->
                            <p class="book-purpose"> Renting </p>
    
                            <!-- book description -->
                            <div class="book-description-container">
                                <p class="book-description"> Set in the American South during the 1930s, this classic
                                    novel explores themes of racial injustice and moral growth through the eyes of Scout
                                    Finch, a young girl whose father, Atticus Finch, is ... </p>
                            </div>
    
                            <!-- book price -->
                            <div class="book-price">
                                <p class="book-price"> NRs. 85 </p>
                            </div>
    
                            <button class="btn" onclick="window.location.href='book-details.php'"> Show More </button>
                        </div>
                    </div>
    
                    <!-- book container :: dummy data 2 -->
                    <div class="book-container">
                        <!-- book image -->
                        <div class="book-image">
                            <img src="assets/Images/cover-2.png" alt="">
                        </div>
    
                        <!-- book details -->
                        <div class="book-details">
                            <!-- book title -->
                            <div class="book-title">
                                <p class="book-title"> Don't Look Back </p>
                                <a href="#">
                                    <i class="fa-regular fa-bookmark"></i>
                                </a>
                            </div>
    
                            <!-- book purpose -->
                            <p class="book-purpose"> Selling </p>
    
                            <!-- book description -->
                            <div class="book-description-container">
                                <p class="book-description"> Set in the American South during the 1930s, this classic
                                    novel explores themes of racial injustice and moral growth through the eyes of Scout
                                    Finch, a young girl whose father, Atticus Finch, is ... </p>
                            </div>
    
                            <!-- book price -->
                            <div class="book-price">
                                <p class="book-price"> NRs. 170 </p>
                            </div>
    
                            <button class="btn" onclick="window.location.href='book-details.php'"> Show More </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- all books section -->
            <section class="d-flex flex-column gap-3 section all-books-section">
                <div class="d-flex justify-content-between align-items-center heading">
                    <p class="f-reset fw-bold heading text-secondary fs-4"> All Books </p>
                    <i class="fa fa-filter text-secondary pointer" id="filter-show-trigger-2"></i>
                </div>

                <!-- all book container -->
                <div class="d-flex flex-row flex-wrap gap-3 all-book-container">
                    <!-- book container :: dummy data 1 -->
                    <div class="book-container">
                        <!-- book image -->
                        <div class="book-image">
                            <img src="assets/Images/cover-1.jpeg" alt="">
                        </div>
    
                        <!-- book details -->
                        <div class="book-details">
                            <!-- book title -->
                            <div class="book-title">
                                <p class="book-title"> To Kill a Mockingbird </p>
                                <a href="#">
                                    <i class="fa-regular fa-bookmark"></i>
                                </a>
                            </div>
    
                            <!-- book purpose -->
                            <p class="book-purpose"> Renting </p>
    
                            <!-- book description -->
                            <div class="book-description-container">
                                <p class="book-description"> Set in the American South during the 1930s, this classic
                                    novel explores themes of racial injustice and moral growth through the eyes of Scout
                                    Finch, a young girl whose father, Atticus Finch, is ... </p>
                            </div>
    
                            <!-- book price -->
                            <div class="book-price">
                                <p class="book-price"> NRs. 85 </p>
                            </div>
    
                            <button class="btn" onclick="window.location.href='book-details.php'"> Show More </button>
                        </div>
                    </div>
    
                    <!-- book container :: dummy data 2 -->
                    <div class="book-container">
                        <!-- book image -->
                        <div class="book-image">
                            <img src="assets/Images/cover-2.png" alt="">
                        </div>
    
                        <!-- book details -->
                        <div class="book-details">
                            <!-- book title -->
                            <div class="book-title">
                                <p class="book-title"> Don't Look Back </p>
                                <a href="#">
                                    <i class="fa-regular fa-bookmark"></i>
                                </a>
                            </div>
    
                            <!-- book purpose -->
                            <p class="book-purpose"> Selling </p>
    
                            <!-- book description -->
                            <div class="book-description-container">
                                <p class="book-description"> Set in the American South during the 1930s, this classic
                                    novel explores themes of racial injustice and moral growth through the eyes of Scout
                                    Finch, a young girl whose father, Atticus Finch, is ... </p>
                            </div>
    
                            <!-- book price -->
                            <div class="book-price">
                                <p class="book-price"> NRs. 170 </p>
                            </div>
    
                            <button class="btn" onclick="window.location.href='book-details.php'"> Show More </button>
                        </div>
                    </div>
                </div>

                <!-- pagination -->
                <div class="d-flex flex-row mt-3 mx-md-auto mx-lg-0 align-items-center pagination-container">
                    <div class="pagination-controller">
                        <i class="fa-solid fa-chevron-left"></i>
                    </div>

                    <div class="pagination-stamp">
                        <p> 1 </p>
                    </div>

                    <div class="pagination-stamp active">
                        <p> 2 </p>
                    </div>

                    <div class="pagination-stamp">
                        <p> 3 </p>
                    </div>

                    <div class="pagination-stamp">
                        <p> 4 </p>
                    </div>

                    <div class="pagination-stamp">
                        <p> 5 </p>
                    </div>

                    <div class="pagination-controller">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </section>


            <!-- pagination -->
        </article>
    </main>

    <!-- jquery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- js :: current file -->
    <script>
        // filter
        var filterTriggerState = false;
        const aside = document.getElementById("aside");
        const showFilter = document.getElementById("filter-show-trigger-2");
        const hideFilter = document.getElementById("filter-hide-trigger");

        // menu
        const openMenu = document.getElementById("open-menu");
        const closeMenu = document.getElementById("close-menu");

        const menu = document.getElementById("menu");

        // profile menu
        var profileMenuState = false;
        const profileMenu = document.getElementById("profile-menu");
        const profileMenuTrigger = document.getElementById("profile-menu-trigger");

        // open menu
        openMenu.addEventListener('click', function () {
            menu.style.display = "flex";
            menu.style = "right: 0; transition: .4s";
        });


        // close menu
        closeMenu.addEventListener('click', function () {
            menu.style.display = "none";
            menu.style = "right: -100%; transition: .4s";
        });

        // profile menu
        profileMenuTrigger.addEventListener('click', function(){
            profileMenuState = !profileMenuState;
            if(profileMenuState){
                profileMenu.style.display = "block";
            }else{
                profileMenu.style.display = "none";
            }
        });


        // filter
        showFilter.addEventListener('click', function () {
            aside.style = "display:block";
            filterTriggerState = !filterTriggerState;
        });

        hideFilter.addEventListener('click', function () {
            aside.style = "display:none";
            filterTriggerState = !filterTriggerState;
        });

        // device width changing
        widthCheck = () => {
            if(window.innerWidth < 768){
                profileMenuState= true;
                }else{
                    profileMenuState= false;
            }
            if (window.innerWidth < 1188) {
                // user menu
                if(profileMenuState){
                    profileMenu.style.display = "block";
                    }else{
                        profileMenu.style.display = "none";
                }

                // filter
                if (!filterTriggerState) {
                    aside.style.display = "none";
                } else {
                    aside.style.display = "flex";
                }
            }
            if (window.innerWidth >= 1188) {
                // profileMenu.style.display = "none";
                showFilter.style.display = "none";
                aside.style.display = "flex";
            } else {
                showFilter.style.display = "block";
            }
        }

        window.addEventListener('resize', widthCheck);

        window.onload = () => {
            menu.style.display = "none";
            profileMenu.style.display = "none";
            widthCheck();
        }
    </script>
</body>

</html>