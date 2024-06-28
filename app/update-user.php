<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

require_once __DIR__ . '/../../bookrack/app/functions.php';

require_once __DIR__ . '/../../bookrack/app/user-class.php';



if (isset($_POST['update-profile-btn'])) {
    $status = 0;

    $user = new User();

    $userId = $_POST['user-id'];

    // checking if profile picture has been changed
    $hasProfilePhoto = (isset($_FILES['edit-profile-profile-picture']) && $_FILES['edit-profile-profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

    // getting the form details
    $userId = $_POST['user-id'];
    $user->fetch($userId);

    $firstName = $_POST['edit-profile-first-name'];
    $lastName = $_POST['edit-profile-last-name'];
    $fullName = ucfirst($firstName).' '.ucfirst($lastName);
    $dob = $_POST['edit-profile-dob'];
    $gender = $_POST['edit-profile-gender'];
    $phoneNumber = '+977'.$_POST['edit-profile-contact'];
    $district = $_POST['edit-profile-district'];
    $location = $_POST['edit-profile-location'];

    $authProperties = [
        'displayName' => $fullName,
        'phoneNumber' => $phoneNumber,
    ];

    $properties = [
        'name' => [
            'first' => strtolower($firstName),
            'last' => strtolower($lastName),
        ],
        'gender' => $gender,
        'dob' => $dob,
        'address' => [
            'district' => $district,
            'location' => strtolower($location)
        ],
    ];

    if ($hasProfilePhoto) {
        global $auth;
        global $database;

        // auth
        $authUpdated = $auth->updateUser($userId, $authProperties);

        if($authUpdated){
            // getting previous profile picture
            $oldPhotoName = $user->photo;
            
            // profile picture :: extract details from photo
            $photoFile = $_FILES['edit-profile-profile-picture'];
            $user->photo = $photoFile;
            $fileTmpPath = $photoFile['tmp_name'];
            $fileName = $photoFile['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $filePath = 'users/' . $newFileName;
            
            $properties['photo'] = $newFileName;
            
            try {
                // upload profile picture
                $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);
                
                    $response = $database->getReference("users/{$userId}")->update($properties);
                    if($response)
                        $status = 1;
            } catch (Exception $e) {
                $status = false;
            }
            
            // in case photo uploaded
            if ($status) {
                // delete previous profile picture
                $temp = deleteFileFromStorageBucket("users", $oldPhotoName);
            }
        }
    } else {
        global $auth;
        global $database;
        
        // auth
        $authUpdated = $auth->updateUser($userId, $authProperties);

        if($authUpdated){
            $response = $database->getReference("users/{$userId}")->update($properties);
            if($response)
                $status = 1;
        }
    }
    $_SESSION['status'] = $status ? 1 : 0;
    $_SESSION['status-message'] = $status ? "Profile updated successfully." : "Profile updation failed.";

    header("Location: /bookrack/profile/view-profile");
    exit();
}



if (isset($_POST['update-profile-btn-old'])) {
    $status = false;

    $user = new User();

    // checking if profile picture has been changed
    $hasProfilePhoto = (isset($_FILES['edit-profile-profile-picture']) && $_FILES['edit-profile-profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

    // getting the form details
    $user->setUserId($_POST['user-id']);
    $user->fetch($_POST['user-id']);

    $user->setFirstName($_POST['edit-profile-first-name']);
    $user->setLastName($_POST['edit-profile-last-name']);
    $user->setDob($_POST['edit-profile-dob']);
    $user->setGender($_POST['edit-profile-gender']);
    $user->setPhoneNumber($_POST['edit-profile-contact']);
    $user->setAddressDistrict($_POST['edit-profile-district']);
    $user->setAddressLocation($_POST['edit-profile-location']);


    $properties = [
        'name' => [
            'first' => strtolower($user->getFirstName()),
            'last' => strtolower($user->getLastName()),
        ],
        'gender' => $user->getGender(),
        'dob' => $user->getDob(),
        'contact' => $user->getPhoneNumber(),
        'address' => [
            'district' => $user->getAddressDistrict(),
            'location' => strtolower($user->getAddressLocation())
        ],
    ];

    if ($hasProfilePhoto) {
        // getting previous profile picture
        $oldPhotoName = $user->getPhoneNumber();

        // profile picture :: extract details from photo
        $user->setPhoto($_FILES['edit-profile-profile-picture']);
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
                $response = $database->getReference("users/{$user->getUserId()}")->update($properties);
                $status = true;
            } catch (Exception $e) {
                $status = false;
            }
        } catch (Exception $e) {
            $status = false;
        }

        // in case photo uploaded
        if ($status) {
            // delete previous profile picture
            $temp = deleteFileFromStorageBucket("users", $oldPhotoName);
        }
    } else {
        try {
            $response = $database->getReference("users/{$user->getUserId()}")->update($properties);
            $status = true;
        } catch (Exception $e) {
            $status = false;
        }
    }

    $_SESSION['status'] = $status ? 1 : 0;
    $_SESSION['status-message'] = $status ? "Profile updated successfully." : "Profile updation failed.";

    header("Location: /bookrack/profile/view-profile");
    exit();
}