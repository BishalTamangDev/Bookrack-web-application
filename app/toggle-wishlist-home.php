<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

$bookId = $_POST['bookId'];
$userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/../classes/wishlist.php';
$temp_wishlist = new Wishlist();
$temp_wishlist->setUserId($userId);

$status = $temp_wishlist->toggle($bookId);
echo $status;
exit;
