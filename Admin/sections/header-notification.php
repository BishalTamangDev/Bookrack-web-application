<?php
require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/notification.php';

$userObj = new User();
$bookObj = new Book();
$cartObj = new Cart();
$notificationObj = new Notification();

$notificationList = $notificationObj->fetchAdminNotification();

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

        // click purpose
        $statusId = $notification['status'] == "unseen" ? "notification-click" : "notification-clicked"; 

        if ($notification['type'] == "account-verification-apply") {
            $userObj->fetch($notification['user_id']);
            $userName = $userObj->getFullName();
            $link = "/bookrack/admin/admin-user-details/{$notification['user_id']}";
            ?>
            <div class="notification <?= $statusClass ?>" onclick="window.location.href='<?= $link ?>'" data-notification-id="<?=$notificationId?>">
                <div class="icon-div">
                    <img src="/bookrack/assets/icons/notification/account-verification-apply.png" alt="">
                </div>

                <div class="details">
                    <div class="detail">
                        <p class="m-0">
                            <?= "$userName applied for account verification. " ?>
                        </p>
                    </div>

                    <p class="date">
                        <?= $date ?>
                    </p>
                </div>
            </div>
            <?php
        } elseif ($notification['type'] == "new book") {
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
        } elseif ($notification['type'] == "cart checkout") {
            $userObj->fetch($notification['user_id']);
            $cartObj->fetch($notification['cart_id']);
            $link = "/bookrack/admin/admin-order-summary/{$cartObj->getId()}";
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
        }
    ?>
    <?php
    }
}
?>