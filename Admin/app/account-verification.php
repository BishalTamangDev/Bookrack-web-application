<?php
$status = false;

$adminId = $_POST['adminId'] ?? 0;

if($adminId == 0){
    echo $status;
    exit;
}

require_once __DIR__ . '/../../classes/admin.php';

$admin = new Admin();

$status = $admin->verifyAdminAccount($_SESSION['bookrack-admin-id']);

echo $status;

exit;