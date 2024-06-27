<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/");

$adminId = $_SESSION['bookrack-admin-id'];

if (isset($_POST['admin-update-profile-btn'])) {
    $status = false;

    require_once __DIR__ . '/admin-class.php';
    require_once __DIR__ . '/../../../bookrack/app/functions.php';

    // fetch admin details
    $tempAdmin = new Admin();
    $status = $tempAdmin->fetch($adminId);

    // get form values
    $hasProfilePhoto = (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) ? 1 : 0;
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];

    // set properties to be updated
    $properties = [
        'name' => [
            'first' => strtolower($firstName),
            'last' => strtolower($lastName),
        ],
        'gender' => $gender,
        'dob' => $dob,
        'contact' => $contact
    ];

    if ($hasProfilePhoto) {
        // getting previous profile picture
        $oldProfilePictureFileName = $tempAdmin->getProfilePicture();

        // profile picture :: extract details from photo
        $tempAdmin->setProfilePicture($_FILES['profile-picture']);
        $fileTmpPath = $_FILES['profile-picture']['tmp_name'];
        $fileName = $_FILES['profile-picture']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $filePath = 'admins/' . $newFileName;

        $properties['profile_picture'] = $newFileName;

        try {
            // upload profile picture
            $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);

            try {
                $response = $database->getReference("admins/{$tempAdmin->getId()}")->update($properties);
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
            $temp = deleteFileFromStorageBucket("admins", $oldProfilePictureFileName);
        }
    } else {


        try {
            $response = $database->getReference("admins/{$tempAdmin->getId()}")->update($properties);
            $status = true;
        } catch (Exception $e) {
        }
    }

    $_SESSION['status'] = $status ? true : false;
    $_SESSION['status-message'] = $status ? "Profile updated successfully." : "Profile updation failed.";

    header("Location: /bookrack/admin/profile");
}

exit();