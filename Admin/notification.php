<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Notification </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/nav.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/notification.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php
    include 'nav.php';
    ?>

    <!-- main content -->
    <main class="main">
        <!-- heading -->
        <p class="page-heading"> Notifications </p>

        <!-- filters -->
        <section class="d-flex flex-row mt-3 notification-filter">
            <div class="filter filter-all active">
                <p class="f-reset"> All </p>
            </div>

            <div class="filter filter-seen">
                <p class="f-reset"> Seen </p>
            </div>

            <div class="filter filter-unseen">
                <p class="f-reset"> Unseen </p>
            </div>
        </section>

        <!-- notification container -->
        <section class="d-flex flex-column mt-3 notification-container">
            <!-- notification 1 -->
            <div class="notification-div">
                <!-- notification icon -->
                <div class="icon-div">
                    <div class="notification-icon-div">
                        <img src="/bookrack/assets/icons/Notification/new-user.png" alt="">
                    </div>
                </div>

                <!-- content -->
                <div class="content-div">
                    <!-- detail -->
                    <div class="detail-div">
                        <p class="f-reset title"> Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Consequatur tempora assumenda reiciendis corporis eaque deleniti repellendus esse!
                            Cupiditate, soluta nostrum. </p>
                        <p class="f-reset date"> 0000-00-00 </p>
                    </div>

                    <!-- operation -->
                    <div class="operation-div">
                        <button class="btn btn-primary"> View User Detail </button>
                    </div>
                </div>

                <!-- menu -->
                <div class="menu-div">
                    <div class="icon-div">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                        <!-- <i class="fa fa-home"> </i> -->
                    </div>
                </div>
            </div>

            <!-- notification 2 -->
            <div class="notification-div">
                <!-- notification icon -->
                <div class="icon-div">
                    <div class="notification-icon-div">
                        <img src="/bookrack/assets/icons/Notification/book-offer.png" alt="">
                    </div>
                </div>

                <!-- content -->
                <div class="content-div">
                    <!-- detail -->
                    <div class="detail-div">
                        <p class="f-reset title"> Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Consequatur tempora assumenda reiciendis corporis eaque deleniti repellendus esse!
                            Cupiditate, soluta nostrum. </p>
                        <p class="f-reset date"> 0000-00-00 </p>
                    </div>

                    <!-- operation -->
                    <div class="operation-div">
                        <button class="btn btn-primary"> View Book Detail </button>
                    </div>
                </div>

                <!-- menu -->
                <div class="menu-div">
                    <div class="icon-div">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                        <!-- <i class="fa fa-home"> </i> -->
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"> </script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>
</body>

</html>