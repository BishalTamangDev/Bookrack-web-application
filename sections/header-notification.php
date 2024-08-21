<?php

$userId = $_POST['userId'] ?? 0;

if($userId == 0) {
    echo false;
    exit;
}

require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../classes/book.php';
require_once __DIR__ . '/../classes/cart.php';
require_once __DIR__ . '/../classes/notification.php';

$userObj = new User();
$bookObj = new Book();
$cartObj = new Cart();
$notificationObj = new Notification();

$notificationIdList = $notificationObj->fetchUserNotificationId($userId);

if (sizeof($notificationIdList) == 0) {
    ?>
    <div class="p-3 empty-notification">
        <p class="m-0"> Empty! </p>
    </div>
    <?php
} else {
    foreach ($notificationIdList as $notificationId) {
        $notificationObj->fetch($notificationId);
        $type = $notificationObj->type;
        $date = $notificationObj->date;

        if($notificationObj->type == "account-verified") {
            $userObj->fetch($notificationObj->userId);
            $userName = $userObj->getFullName();
            $link = "/bookrack/profile/";
            ?>
            <div class="notification" onclick="window.location.href='<?=$link?>'">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/account-verified.png" alt="">
                </div>

                <div class="details">
                    <div class="detail">
                        <p class="m-0"> Your account has been verified. </p>
                    </div>

                    <p class="date">
                        <?= $date ?>
                    </p>
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
            <div class="notification" onclick="window.location.href='<?=$link?>'">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/new-book.png" alt="">
                </div>

                <div class="details">
                    <div class="detail">
                        <p class="m-0">
                            <?= "$userName added a new book " ?>
                            <span class="fw-semibold"> "<?=$bookTitle?>" </span>
                        </p>
                    </div>

                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($notificationObj->type == "cart checkout") {
            $userObj->fetch($notificationObj->userId);
            $cartObj->fetch($notificationObj->cartId);
            $link = "/bookrack/admin/admin-book-requests/{$cartObj->getId()}";
            ?>
            <div class="notification" onclick="window.location.href='<?=$link?>'">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/cart.png" alt="">
                </div>

                <div class="details">
                    <!-- notification detail -->
                    <div class="detail">
                        <p class="m-0">
                            <?= $userObj->getFullName() . " checked out the cart." ?>
                        </p>
                    </div>

                    <!-- date -->
                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        }
    ?>
    <?php
    }
}
?>