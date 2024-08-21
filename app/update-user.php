<?php

require_once __DIR__ . '/../functions/district-array.php';
require_once __DIR__ . '/../functions/delete-cloud-file.php';
require_once __DIR__ . '/../classes/user.php';

$status = false;

$user = new User();

$userId = $_POST['user-id'];

// checking if profile picture has been changed
$hasProfilePhoto = (isset($_FILES['edit-profile-profile-picture']) && $_FILES['edit-profile-profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

// getting the form details
$userId = $_POST['user-id'];
$user->fetch($userId);

$authProperties = [];

// first name
if (isset($_POST['edit-profile-first-name']))
    $properties['name']['first'] = strtolower($_POST['edit-profile-first-name']);

// last name
if (isset($_POST['edit-profile-last-name']))
    $properties['name']['last'] = strtolower($_POST['edit-profile-last-name']);

// display name
if (isset($_POST['edit-profile-first-name']) && isset($_POST['edit-profile-last-name']))
    $authProperties['displayName'] = ucfirst($_POST['edit-profile-first-name']) . ' ' . ucfirst($_POST['edit-profile-last-name']);

// dob
if (isset($_POST['edit-profile-dob']))
    $properties['dob'] = $_POST['edit-profile-dob'];

// gender 
if (isset($_POST['edit-profile-gender']))
    $properties['gender'] = $_POST['edit-profile-gender'];

// phone number
if (isset($_POST['edit-profile-contact']) && $_POST['edit-profile-contact'] != "")
    $authProperties['phoneNumber'] = '+977' . $_POST['edit-profile-contact'];

// district
if (isset($_POST['edit-profile-district']))
    $properties['address']['district'] = $_POST['edit-profile-district'];

// municipality
if (isset($_POST['edit-profile-municipality']))
    $properties['address']['municipality'] = strtolower($_POST['edit-profile-municipality']);

// ward
if (isset($_POST['edit-profile-ward']))
    $properties['address']['ward'] = strtolower($_POST['edit-profile-ward']);

// tole-village
if (isset($_POST['edit-profile-tole-village']))
$properties['address']['tole_village'] = strtolower($_POST['edit-profile-tole-village']);

if ($hasProfilePhoto) {
    global $auth;
    global $database;

    // auth
    if ($authProperties['displayName'] != "" || $authProperties['phoneNumber'] != "")
        $authUpdated = $auth->updateUser($userId, $authProperties);

    // getting previous profile picture
    $oldPhotoName = $user->photo;

    // profile picture :: extract details from photo
    $photoFile = $_FILES['edit-profile-profile-picture'];
    $user->photo = $photoFile;
    $fileTmpPath = $photoFile['tmp_name'];
    $fileName = $photoFile['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $filePath = "users/$newFileName";

    $properties['photo'] = $newFileName;

    try {
        // upload profile picture
        $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);

        $response = $database->getReference("users/{$userId}")->update($properties);
        if ($response)
            $status = true;
    } catch (Exception $e) {
        $status = false;
    }

    // in case photo uploaded
    if ($status) {
        // delete previous profile picture
        $temp = deleteFileFromStorageBucket("users", $oldPhotoName);
    }
} else {
    global $auth;
    global $database;

    // auth
    if ($authProperties['displayName'] != "" || $authProperties['phoneNumber'] != "")
        $authUpdated = $auth->updateUser($userId, $authProperties);

    $response = $database->getReference("users/{$userId}")->update($properties);
    if ($response)
        $status = true;
}

echo $status ? "Profile updated successfully." : "Profile updation failed.";

exit;