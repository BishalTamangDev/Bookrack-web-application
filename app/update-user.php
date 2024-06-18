<?php

require_once __DIR__ . '/../../bookrack/app/functions.php';

require_once __DIR__ . '/../../bookrack/app/user-class.php';

if (isset($_POST['update-profile-btn'])) {
    $status = false;

    $newUser = new User();

    // checking if profile picture has been changed
    $hasProfilePhoto = (isset($_FILES['edit-profile-profile-picture']) && $_FILES['edit-profile-profile-picture']['error'] === UPLOAD_ERR_OK)? true:false;
    
    // getting the form details
    $newUser->setUserId($_POST['user-id']);
    $newUser->fetchUserDetails($_POST['user-id']);
    
    $newUser->setFirstName($_POST['edit-profile-first-name']);
    $newUser->setLastName($_POST['edit-profile-last-name']);
    $newUser->setDob($_POST['edit-profile-dob']);
    $newUser->setGender($_POST['edit-profile-gender']);
    $newUser->setContact($_POST['edit-profile-contact']);
    $newUser->setAddressDistrict($_POST['edit-profile-district']);
    $newUser->setAddressLocation($_POST['edit-profile-location']);

    $properties = [
        'first_name' => $newUser->getFirstName(),
        'last_name' => $newUser->getLastName(),
        'gender' => $newUser->getGender(),
        'dob' => $newUser->getDob(),
        'contact' => $newUser->getContact(),
        'address' => [
            'district' => $newUser->getAddressDistrict(),
            'location' => $newUser->getAddressLocation()
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
                $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);
                $status = true;
                
                if ($status) {
                    $status = $database->getReference("users/{$newUser->getUserId()}")->update($properties);
                }
            } catch (Exception $e) {
                $_SESSION['status'] = false;
            }

            // in case photo uploaded
            if($status){
                // delete previous profile picture
                $temp = deleteFileFromStorageBucket("users", $oldProfilePictureFileName);
            }

            $_SESSION['status'] = $status ? true : false;
            $_SESSION['status-message'] = $status ? "Profile picture uploaded successfully." : "Failed to upload profile picture";
    } else {
        $status = $database->getReference("users/{$newUser->getUserId()}")->update($properties);
        $_SESSION['status'] = $status ? true : false;
        $_SESSION['status-message'] = $status ? "Profile updated successfully." : "Profile updation failed.";
    }

    header("Location: /bookrack/profile/view-profile");
    exit();
}