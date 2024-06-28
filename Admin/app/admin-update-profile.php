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

    $admin = new admin();
    $admin->fetch($adminId);

    // checking if profile picture has been changed
    $hasProfilePhoto = (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $fullName = ucfirst($firstName) . ' ' . ucfirst($lastName);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phoneNumber = '+977' . $_POST['phone-number'];
    // $district = $_POST['edit-profile-district'];
    // $location = $_POST['edit-profile-location'];

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
        // 'address' => [
            // 'district' => $district,
            // 'location' => strtolower($location)
        // ],
    ];

    if ($hasProfilePhoto) {
        global $auth;
        global $database;

        // auth
        $authUpdated = $auth->updateUser($adminId, $authProperties);

        if ($authUpdated) {
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
        }
    } else {
        global $auth;
        global $database;

        // auth
        $authUpdated = $auth->updateUser($adminId, $authProperties);

        if ($authUpdated) {
            $response = $database->getReference("admins/{$adminId}")->update($properties);
            if ($response)
                $status = 1;
        }
    }
    $_SESSION['status'] = $status ? 1 : 0;
    $_SESSION['status-message'] = $status ? "Profile updated successfully." : "Profile updation failed.";

    header("Location: /bookrack/admin/admin-profile");
    exit();
}
