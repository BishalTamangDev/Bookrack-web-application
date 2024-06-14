<?php
// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['bookrack-user-id']);
unset($_SESSION['status']);

header("Location: /bookrack/");