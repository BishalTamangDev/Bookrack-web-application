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

if (isset($_POST['upload-kyc-btn'])) {
    $_SESSION['status'] = false;

    $newUser = new User();

    // fetch user details
    $newUser->setUserId($_POST['user-id']);

    $newUser->fetch($_POST['user-id']);

    // getting the form details
    $documentType = $_POST['document-type'];
    $hasKycFront = (isset($_FILES['kyc-front']) && $_FILES['kyc-front']['error'] === UPLOAD_ERR_OK) ? 1 : 0;
    $hasKycBack = (isset($_FILES['kyc-back']) && $_FILES['kyc-back']['error'] === UPLOAD_ERR_OK) ? 1 : 0;


    if ($documentType == 1) {
        // birth certificate
        $oldFileFront = $newUser->getKycFront();
        $oldFileBack = $newUser->getKycBack();

        // kyc front :: extract details from photo
        $newUser->setDocumentType('birth-certificate');
        $newUser->setKycFront($_FILES['kyc-front']);

        // file properties of new front document
        $fileTmpPath = $_FILES['kyc-front']['tmp_name'];
        $fileName = $_FILES['kyc-front']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $filePath = 'kyc/' . $newFileName;

        // properties to be changed
        $properties['kyc']['document_type'] = 'birth-certificate';
        $properties['kyc']['front'] = $newFileName;
        $properties['kyc']['back'] = "";

        try{
            // upload birth certificate
            $bucket->upload(fopen($fileTmpPath, 'r'), ['name' => $filePath]);
            try {
                // update user detail
                $response = $database->getReference("users/{$newUser->getUserId()}")->update($properties);
                $_SESSION['status'] = true;
            } catch (Exception $e) {
                $_SESSION['status'] = false;
            }
        } catch(Exception $e){
            $_SESSION['status'] = false;
        }
        
        // in case photo uploaded, delete the existing kyc document
        if ($_SESSION['status'] == true) {
            // delete previous profile picture
            $temp = deleteFileFromStorageBucket("kyc", $oldFileFront);
        }

        $_SESSION['status-message'] = "Document submitted successfully";
    } else {
        // citizenship
        if ($hasKycBack) {
            $_SESSION['status'] = true;
            $_SESSION['status-message'] = "Document submitted successfully";
        } else {
            $_SESSION['status-message'] = "Please upload back side of the citizenship also.";
        }
    }
    header("Location: /bookrack/profile/kyc");
}