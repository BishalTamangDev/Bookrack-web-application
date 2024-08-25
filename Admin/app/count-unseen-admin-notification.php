<?php

require_once __DIR__ . '/../../classes/notification.php';

$tempNotification = new Notification();

$count = $tempNotification->countAdminUnseenNotification();

if($count == '') {
    echo '';
} else {
    if($count < 10) {
        echo $count;
    } else {
        echo "9<sup>+</sup>";
    }
}

exit;