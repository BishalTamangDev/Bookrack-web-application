<?php
require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/notification.php';

$userObj = new User();
$bookObj = new Book();
$cartObj = new Cart();
$notificationObj = new Notification();

$notificationIdList = $notificationObj->fetchAdminNotificationId();
?>

<div class="notification-container" id="notification-main-container">
    <div class="d-flex flex-row gap-2 icon-count pointer" id="notification-trigger">
        <i class="fa fa-bell fs-4"></i>
        <div class="position-absolute notification-count-div text-align-center">
            <p class="m-0 text-danger"> 9+ </p>
        </div>
    </div>

    <div class="invisible position-absolute bg-white notification-div" id="notification-container">
        <div class="d-flex flex-row justify-content-between align-items-center p-2 py-2 heading-div">
            <div class="title">
                <p class="m-0 font-weight-bold"> Notifications </p>
            </div>
        </div>

        <hr class="m-0">

        <div class="d-flex flex-column mt-2 pb-2 px-2 notifications">
            <?php
            if (sizeof($notificationIdList) > 0) {
                foreach ($notificationIdList as $notificationId) {
                    $notificationObj->fetch($notificationId);
                    $type = $notificationObj->type;
                    $data = $notificationObj->date;
                    
                    if ($notificationObj->type == "new user") {
                        // fetch user detail
                        $userObj->fetch($notificationObj->userId);
                        $email = $userObj->email;
                        $link = "/bookrack/admin/admin-user-details/{$notificationObj->userId}";
                        ?>
                        <div class="d-flex flex-row gap-2 pointer p-2 notification">
                            <div class="d-flex flex-row justify-content-around align-items-center icon-div">
                                <img src="/bookrack/assets/icons/notification/new-user.png" alt="">
                            </div>

                            <div class="details">
                                <!-- notification detail -->
                                <div class="detail">
                                    <p class="m-0">
                                        A new user joined with the email address <?=$email?>.
                                    </p>
                                </div>

                                <!-- date -->
                                <div class="date">
                                    <p class="m-0 small text-secondary">
                                        <?= $data?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    } elseif ($notificationObj->type == "new book") {
                        $userObj->fetch($notificationObj->userId);
                        $userName = $userObj->getFullName();
                        $bookObj->fetch($notificationObj->bookId);
                        $bookTitle = $bookObj->title;
                        $link = "/bookrack/admin/admin-book-details/{$notificationObj->bookId}";
                        ?>
                        <div class="d-flex flex-row gap-2 pointer p-2 notification">
                            <div class="d-flex flex-row justify-content-around align-items-center icon-div">
                                <img src="/bookrack/assets/icons/notification/book-added.png" alt="">
                            </div>

                            <div class="details">
                                <!-- notification detail -->
                                <div class="detail">
                                    <p class="m-0">
                                        <?="$userName added a new book \"$bookTitle\"."?>
                                    </p>
                                </div>

                                <!-- date -->
                                <div class="date">
                                    <p class="m-0 small text-secondary">
                                        <?= $data?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    } elseif ($notificationObj->type == "cart checkout") {
                        $userObj->fetch($notificationObj->userId);
                        $cartObj->fetch($notificationObj->cartId);
                        $link = "/bookrack/admin/admin-book-requests/{$cartObj->getId()}";
                        ?>
                        <div class="d-flex flex-row gap-2 pointer p-2 notification">
                            <div class="d-flex flex-row justify-content-around align-items-center icon-div">
                                <img src="/bookrack/assets/icons/notification/book-added.png" alt="">
                            </div>

                            <div class="details">
                                <!-- notification detail -->
                                <div class="detail">
                                    <p class="m-0">
                                        <?=$userObj->getFullName()." checked out the cart." ?>
                                    </p>
                                </div>

                                <!-- date -->
                                <div class="date">
                                    <p class="m-0 small text-secondary">
                                        <?=$data?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                }
            } else {
                ?>
                <div class="d-flex flex-row gap-2 pointer p-2 notification">
                    <p class="m-0"> Empty! </p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<!-- notification format -->
<div class="d-none d-flex flex-row gap-2 pointer p-2 notification">
    <div class="d-flex flex-row justify-content-around align-items-center icon-div">
        <img src="/bookrack/assets/icons/notification/book-added.png" alt="">
    </div>

    <div class="details">
        <!-- notification detail -->
        <div class="detail">
            <p class="m-0">
                Notification details appears here...
            </p>
        </div>

        <!-- date -->
        <div class="date">
            <p class="m-0 small text-secondary">
                0000-00-00 00-00-00
            </p>
        </div>
    </div>
</div>