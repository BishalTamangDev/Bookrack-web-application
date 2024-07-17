<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/");

$adminId = $_SESSION['bookrack-admin-id'];

require_once __DIR__ . '/admin-class.php';
require_once __DIR__ . '/../../app/functions.php';

if (isset($_POST['admin-update-profile-btn'])) {
    $status = 0;

    $authProperties = [];

    $admin = new admin();
    $admin->fetch($adminId);

    // checking if profile picture has been changed
    $hasProfilePhoto = (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

    if (isset($_POST['first-name']))
        $properties['name']['first'] = $_POST['first-name'];

    if (isset($_POST['last-name']))
        $properties['name']['last'] = $_POST['last-name'];

    if (isset($_POST['first-name']) && isset($_POST['last-name'])) {
        $authProperties = [
            'displayName' => ucfirst($_POST['first-name']) . ' ' . ucfirst($_POST['last-name']),
        ];
    }

    if (isset($_POST['dob']))
        $properties['dob'] = $_POST['dob'];

    if (isset($_POST['gender']))
        $properties['gender'] = $_POST['gender'];

    if (isset($_POST['phone-number']) && $_POST['phone-number'] != "")
        $authProperties['phoneNumber'] = '+977' . $_POST['phone-number'];

    if ($hasProfilePhoto) {
        global $auth;
        global $database;

        // auth
        $authUpdated = $auth->updateUser($adminId, $authProperties);

        // getting previous profile picture
        $oldPhotoName = $admin->photo;

        // profile picture :: extract details from photo
        $photoFile = $_FILES['profile-picture'];
        $admin->photo = $photoFile;
        $fileTmpPath = $photoFile['tmp_name'];
        $fileName = $photoFile['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $filePath = 'admins/' . $newFileName;

        $properties['photo'] = $newFileName;

        try {
            // upload profile picture
            $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);

            $response = $database->getReference("admins/{$adminId}")->update($properties);
            if ($response)
                $status = 1;
        } catch (Exception $e) {
            $status = false;
        }

        // in case photo uploaded
        if ($status) {
            $temp = deleteFileFromStorageBucket("admins", $oldPhotoName);
        }
    } else {
        global $auth;
        global $database;

        // auth
        $authUpdated = $auth->updateUser($adminId, $authProperties);

        $response = $database->getReference("admins/{$adminId}")->update($properties);
        if ($response)
            $status = 1;
    }
    $_SESSION['status'] = $status ? 1 : 0;
    $_SESSION['status-message'] = $status ? "Profile updated successfully." : "Profile updation failed.";

    header("Location: /bookrack/admin/admin-profile");
    exit();
}
