<?php

$userId = $_POST['userId'] ?? 0;

if ($userId == 0) {
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

$notificationList = $notificationObj->fetchUserNotification($userId);

if (sizeof($notificationList) == 0) {
    ?>
    <div class="p-3 empty-notification">
        <p class="m-0"> Empty! </p>
    </div>
    <?php
} else {
    foreach ($notificationList as $notification) {
        $notificationId = $notification['notification_id'];
        $type = $notification['type'];
        $date = $notification['date'];

        // seen || unseen
        $statusClass = $notification['status'] == "unseen" ? "unseen-notification" : "seen-notification";
        
        if ($notification['type'] == "account-verified") {
            $userObj->fetch($notification['user_id']);
            $userName = $userObj->getFullName();
            $link = "/bookrack/profile/";

            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
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
        } elseif ($type == "new book") {
            $userObj->fetch($notification['user_id']);
            $userName = $userObj->getFullName();
            $bookObj->fetch($notification['book_id']);
            $bookTitle = $bookObj->title;
            $link = "/bookrack/admin/admin-book-details/{$notification['book_id']}";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/new-book.png" alt="">
                </div>

                <div class="details">
                    <div class="detail">
                        <p class="m-0">
                            <?= "$userName added a new book " ?>
                            <span class="fw-semibold"> "<?= $bookTitle ?>" </span>
                        </p>
                    </div>

                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($type == "cart checkout") {
            $userObj->fetch($notification['user_id']);
            $cartObj->fetch($notification['cart_id']);
            $link = "/bookrack/admin/admin-book-requests/{$cartObj->getId()}";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
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
        } elseif ($type == "order-confirmation") {
            $cartId = $notification['cart_id'];
            $link = "/bookrack/cart/pending";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/order-confirmed.png" alt="">
                </div>

                <div class="details">
                    <!-- notification detail -->
                    <div class="detail">
                        <p class="m-0">
                            <?= "Your order has been confirmed. " ?>
                        </p>
                    </div>

                    <!-- date -->
                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($type == "book-request") {
            $bookObj->fetch($notification['book_id']);
            $title = ucwords($bookObj->title);
            $link = "/bookrack/requests";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/book-request.png" alt="">
                </div>

                <div class="details">
                    <!-- notification detail -->
                    <div class="detail">
                        <p class="m-0">
                            <?= "Your book '" . $title . "' has been requested." ?>
                        </p>
                    </div>

                    <!-- date -->
                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($type == "book-received") {
            $bookObj->fetch($notification['book_id']);
            $title = ucwords($bookObj->title);
            $link = "/bookrack/requests";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/book-received.png" alt="">
                </div>

                <div class="details">
                    <!-- notification detail -->
                    <div class="detail">
                        <p class="m-0">
                            <?= "Your book '" . $title . "' has been received." ?>
                        </p>
                    </div>

                    <!-- date -->
                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($type == "order-arrived") {
            $cartId = $notification['cart_id'];
            $link = "/bookrack/cart/pending";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/order-arrived.png" alt="">
                </div>

                <div class="details">
                    <!-- notification detail -->
                    <div class="detail">
                        <p class="m-0">
                            <?= "Your order has arrived." ?>
                        </p>
                    </div>

                    <!-- date -->
                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($type == "order-packed") {
            $cartId = $notification['cart_id'];
            $link = "/bookrack/cart/pending";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/order-packed.png" alt="">
                </div>

                <div class="details">
                    <!-- notification detail -->
                    <div class="detail">
                        <p class="m-0">
                            <?= "Your order is ready." ?>
                        </p>
                    </div>

                    <!-- date -->
                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($type == "order-completed") {
            $cartId = $notification['cart_id'];
            $link = "/bookrack/cart/completed";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/order-completed.png" alt="">
                </div>

                <div class="details">
                    <!-- notification detail -->
                    <div class="detail">
                        <p class="m-0">
                            <?= "Your order is completed." ?>
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