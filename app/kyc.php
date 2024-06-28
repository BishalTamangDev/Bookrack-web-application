<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/user-class.php';

if (isset($_POST['upload-kyc-btn'])) {
    $status = 0;

    $user = new User();
    $userId = $_POST['user-id'];
    $user->fetch($userId);

    // get file details
    $kycFrontFileOld = $user->getKycFront();
    $kycBackFileOld = $user->getKycBack();

    // form values
    $documentType = $_POST['document-type'];
    $hasKycFront = (isset($_FILES['kyc-front']) && $_FILES['kyc-front']['error'] === UPLOAD_ERR_OK) ? 1 : 0;
    $hasKycBack = (isset($_FILES['kyc-back']) && $_FILES['kyc-back']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

    // file properties of new front document
    $fileTmpPath1 = $_FILES['kyc-front']['tmp_name'];
    $fileName1 = $_FILES['kyc-front']['name'];
    $fileExtension1 = pathinfo($fileName1, PATHINFO_EXTENSION);
    $newFileName1 = md5(time() . $fileName1) . '.' . $fileExtension1;
    $filePath1 = 'kyc/' . $newFileName1;

    // birth certificate as kyc
    if ($documentType == 1) {
        // kyc front :: extract details from photo
        $user->setDocumentType('birth-certificate');
        $user->setKycFront($_FILES['kyc-front']);

        // properties to be changed
        $properties['kyc']['document_type'] = 'birth-certificate';
        $properties['kyc']['front'] = $newFileName1;
        $properties['kyc']['back'] = "";

        try {
            // upload birth certificate
            $bucket->upload(fopen($fileTmpPath1, 'r'), ['name' => $filePath1]);
            try {
                // update user detail
                $response = $database->getReference("users/{$userId}")->update($properties);
                $status = 1;
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }

        // in case photo uploaded, delete the existing kyc document
        if ($status) {
            // delete previous front kyc
            if ($kycFrontFileOld != "") {
                $res = deleteFileFromStorageBucket("kyc", $kycFrontFileOld);
                $status = $res ? 1 : 0;
            }
        }

        $_SESSION['status-message'] = "Document submitted successfully";
    } elseif ($documentType == 2) {
        // citizenship
        if ($hasKycBack) {
            // file properties of new back document
            $fileTmpPath2 = $_FILES['kyc-back']['tmp_name'];
            $fileName2 = $_FILES['kyc-back']['name'];
            $fileExtension2 = pathinfo($fileName2, PATHINFO_EXTENSION);
            $newFileName2 = md5(time() . $fileName2) . '.' . $fileExtension2;
            $filePath2 = 'kyc/' . $newFileName2;
            
            if($newFileName1 == $newFileName2){
                sleep(1);
                $newFileName2 = md5(time() . $fileName2) . '.' . $fileExtension2;
                $filePath2 = 'kyc/' . $newFileName2;
            }

            // properties to be changed
            $properties['kyc']['document_type'] = 'citizenship';
            $properties['kyc']['front'] = $newFileName1;
            $properties['kyc']['back'] = $newFileName2;

            print_r($properties);

            try {
                // upload citizenship
                $bucket->upload(fopen($fileTmpPath1, 'r'), ['name' => $filePath1]);
                $status = 1;
            } catch (Exception $e) {
            }

            if($status == 1){
                try{
                    $bucket->upload(fopen($fileTmpPath2, 'r'), ['name' => $filePath2]);
                    $staus = 1;
                }catch (Exception $e){
                    $staus = 0;
                }
            }

            if($status == 1){
                $response = $database->getReference("users/{$userId}")->update($properties);
            }
            

            // in case photo uploaded, delete the existing kyc document
            // if ($status) {
            //     if ($kycFrontFileOld != "")
            //         $temp = deleteFileFromStorageBucket("kyc", $kycFrontFileOld);

            //     if ($kycBackFileOld != "")
            //         $temp = deleteFileFromStorageBucket("kyc", $kycBackFileOld);
            //     $staus = 1;
            // }
            $_SESSION['status-message'] = "Document submitted successfully";
        } else {
            $_SESSION['status-message'] = "Please upload back side of the citizenship also.";
        }
    }
    $_SESSION['status'] = $status ? true : false;
    header("Location: /bookrack/profile/kyc");
}