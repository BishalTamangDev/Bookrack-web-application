<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

unset($_SESSION['bookrack-user-id']);
unset($_SESSION['status']);
unset($_SESSION['status-message']);

session_destroy();

header("Location: /bookrack/");

exit;