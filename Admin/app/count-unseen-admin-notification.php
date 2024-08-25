<?php

require_once __DIR__ . '/../../classes/notification.php';

$tempNotification = new Notification();

$count = $tempNotification->countAdminUnseenNotification();

echo $count != 0 ? $count : "";

exit;