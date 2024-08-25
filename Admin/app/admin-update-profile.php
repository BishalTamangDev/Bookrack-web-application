<?php
$status = false;
$adminId = $_POST['edit-admin-id'] ?? 0;

if ($adminId == 0) {
    echo $status;
    exit;
}

require_once __DIR__ . '/../../classes/admin.php';
require_once __DIR__ . '/../../functions/delete-cloud-file.php';

$authProperties = [];

$admin = new admin();
$admin->fetch($adminId);

// checking if profile picture has been changed
$hasProfilePhoto = (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

if (isset($_POST['first-name']))
    $properties['name_first'] = strtolower($_POST['first-name']);

if (isset($_POST['last-name']))
    $properties['name_last'] = strtolower($_POST['last-name']);

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
    $filePath = "admins/$newFileName";

    $properties['photo'] = $newFileName;

    try {
        // upload profile picture
        $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);

        $response = $database->getReference("admins/{$adminId}")->update($properties);
        $status = $response ? true : false;
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
    $status = $response ? true : false;
}

echo $status ? true : false;

exit;