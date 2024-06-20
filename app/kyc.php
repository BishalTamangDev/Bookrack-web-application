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


    // get file details
    $oldFileFront = $newUser->getKycFront();
    $oldFileBack = $newUser->getKycBack();

    // file properties of new front document
    $fileTmpPath1 = $_FILES['kyc-front']['tmp_name'];
    $fileName1 = $_FILES['kyc-front']['name'];
    $fileExtension1 = pathinfo($fileName1, PATHINFO_EXTENSION);
    $newFileName1 = md5(time() . $fileName1) . '.' . $fileExtension1;
    $filePath1 = 'kyc/' . $newFileName1;

    // birth certificate as kyc
    if ($documentType == 1) {
        // kyc front :: extract details from photo
        $newUser->setDocumentType('birth-certificate');
        $newUser->setKycFront($_FILES['kyc-front']);

        // properties to be changed
        $properties['kyc']['document_type'] = 'birth-certificate';
        $properties['kyc']['front'] = $newFileName1;
        $properties['kyc']['back'] = "";

        try{
            // upload birth certificate
            $bucket->upload(fopen($fileTmpPath1, 'r'), ['name' => $filePath1]);
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
            // delete previous front kyc
            if($oldFileFront != ""){
                $temp = deleteFileFromStorageBucket("kyc", $oldFileFront);
            }
        }

        $_SESSION['status-message'] = "Document submitted successfully";
    } else {
        // citizenship
        if ($hasKycBack) {
            // file properties of new back document
            $fileTmpPath2 = $_FILES['kyc-back']['tmp_name'];
            $fileName2 = $_FILES['kyc-back']['name'];
            $fileExtension2 = pathinfo($fileName2, PATHINFO_EXTENSION);
            $newFileName2 = md5(time() . $fileName2) . '.' . $fileExtension2;
            $filePath2 = 'kyc/' . $newFileName2;

            // kyc front :: extract details from photo
            $newUser->setDocumentType('citizenship');
            $newUser->setKycFront($_FILES['kyc-front']);
            $newUser->setKycBack($_FILES['kyc-back']);

            // properties to be changed
            $properties['kyc']['document_type'] = 'citizenship';
            $properties['kyc']['front'] = $newFileName1;
            $properties['kyc']['back'] = $newFileName2;


            try{
                // upload citizenship
                $bucket->upload(fopen($fileTmpPath1, 'r'), ['name' => $filePath1]);
                $bucket->upload(fopen($fileTmpPath2, 'r'), ['name' => $filePath2]);

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
                // delete previous kyc front
                if($oldFileFront != ""){
                    $temp = deleteFileFromStorageBucket("kyc", $oldFileFront);
                }

                // delete prvious kyc back
                if($oldFileBack != ""){
                    $temp = deleteFileFromStorageBucket("kyc", $oldFileBack);
                }
            }
            $_SESSION['status'] = true;
            $_SESSION['status-message'] = "Document submitted successfully";
        } else {
            $_SESSION['status-message'] = "Please upload back side of the citizenship also.";
        }
    }
    header("Location: /bookrack/profile/kyc");
}