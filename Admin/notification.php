<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "notification";
$adminId = $_SESSION['bookrack-admin-id'];

// fetching the admin profile details
require_once __DIR__ . '/app/admin-class.php';
require_once __DIR__ . '/../app/functions.php';

$profileAdmin = new Admin();
$profileAdmin->fetch($adminId);

if ($profileAdmin->getAccountStatus() != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require_once __DIR__ . '/../app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/notification.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php include 'nav.php'; ?>

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
                        <img src="/bookrack/assets/icons/Notification/new-user.png" alt="" loading="lazy">
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
                        <img src="/bookrack/assets/icons/Notification/book-offer.png" alt="" loading="lazy">
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

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../app/script-include.php'; ?>
</body>

</html>