<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/");
}

require_once __DIR__ . '/../../bookrack/app/functions.php';

require_once __DIR__ . '/../../bookrack/app/user-class.php';

if (isset($_POST['update-profile-btn'])) {
    $status = 0;

    $newUser = new User();

    // checking if profile picture has been changed
    $hasProfilePhoto = (isset($_FILES['edit-profile-profile-picture']) && $_FILES['edit-profile-profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

    // getting the form details
    $newUser->setUserId($_POST['user-id']);
    $newUser->fetch($_POST['user-id']);

    $newUser->setFirstName($_POST['edit-profile-first-name']);
    $newUser->setLastName($_POST['edit-profile-last-name']);
    $newUser->setDob($_POST['edit-profile-dob']);
    $newUser->setGender($_POST['edit-profile-gender']);
    $newUser->setContact($_POST['edit-profile-contact']);
    $newUser->setAddressDistrict($_POST['edit-profile-district']);
    $newUser->setAddressLocation($_POST['edit-profile-location']);

    
    $properties = [
        'name' => [
            'first' => getLowerCaseString($newUser->getFirstName()),
            'last' => getLowerCaseString($newUser->getLastName()),
        ],
        'gender' => $newUser->getGender(),
        'dob' => $newUser->getDob(),
        'contact' => $newUser->getContact(),
        'address' => [
            'district' => $newUser->getAddressDistrict(),
            'location' => getLowerCaseString($newUser->getAddressLocation())
        ],
    ];

    if ($hasProfilePhoto) {
        // getting previous profile picture
        $oldProfilePictureFileName = $newUser->getProfilePicture();

        // profile picture :: extract details from photo
        $newUser->setProfilePicture($_FILES['edit-profile-profile-picture']);
        $fileTmpPath = $_FILES['edit-profile-profile-picture']['tmp_name'];
        $fileName = $_FILES['edit-profile-profile-picture']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $filePath = 'users/' . $newFileName;

        $properties['profile_picture'] = $newFileName;

        try {
            // upload profile picture
            $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);

            try {
                $response = $database->getReference("users/{$newUser->getUserId()}")->update($properties);
                $status = 1;
            } catch (Exception $e) {
                $status = 0;
            }
        } catch (Exception $e) {
            $status = 0;
        }

        // in case photo uploaded
        if ($status) {
            // delete previous profile picture
            $temp = deleteFileFromStorageBucket("users", $oldProfilePictureFileName);
        }
    } else {
        try {
            $response = $database->getReference("users/{$newUser->getUserId()}")->update($properties);
            $status = 1;
        } catch (Exception $e) {
            $status = 0;
        }
    }

    $_SESSION['status'] = $status ? 1 : 0;
    $_SESSION['status-message'] = $status ? "Profile updated successfully." : "Profile updation failed.";

    header("Location: /bookrack/profile/view-profile");
    exit();
}