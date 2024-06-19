<?php
// starting session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['bookrack-admin-id']);
unset($_SESSION['admin-status']);
unset($_SESSION['admin-status-message']);

header("Location: /bookrack/admin/dashboard");