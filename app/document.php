<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

require_once __DIR__ . '/../functions/delete-cloud-file.php';
require_once __DIR__ . '/../classes/user.php';

$status = false;

$user = new User();
$userId = $_POST['user-id'];
$userExists = $user->fetch($userId);

if ($userExists && $userId == $_SESSION['bookrack-user-id']) {
    // get file details
    $documentFrontFileOld = $user->getDocumentFront();
    $documentBackFileOld = $user->getDocumentBack();

    // form values
    $documentType = $_POST['document-type'];
    $hasDocumentFront = (isset($_FILES['document-front']) && $_FILES['document-front']['error'] === UPLOAD_ERR_OK) ? 1 : 0;
    $hasDocumentBack = (isset($_FILES['document-back']) && $_FILES['document-back']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

    // file properties of new front document
    $fileTmpPath1 = $_FILES['document-front']['tmp_name'];
    $fileName1 = $_FILES['document-front']['name'];
    $fileExtension1 = pathinfo($fileName1, PATHINFO_EXTENSION);
    $newFileName1 = md5(time() . $fileName1) . '.' . $fileExtension1;
    $filePath1 = "document/$newFileName1";

    // birth certificate as document
    if ($documentType == 1) {
        // document front :: extract details from photo
        $user->setDocumentType('birth-certificate');
        $user->setDocumentFront($_FILES['document-front']);

        // properties to be changed
        $properties['document_type'] = 'birth-certificate';
        $properties['document_front'] = $newFileName1;
        $properties['document_back'] = "";

        try {
            // upload birth certificate
            $bucket->upload(fopen($fileTmpPath1, 'r'), ['name' => $filePath1]);
            try {
                // update user detail
                $response = $database->getReference("users/{$userId}")->update($properties);
                $status = true;
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }

        // in case photo uploaded, delete the existing document document
        if ($status) {
            // delete previous front document
            if ($documentFrontFileOld != "") {
                $res = deleteFileFromStorageBucket("document", $documentFrontFileOld);
                $status = $res ? true : false;
            }
        }
    } elseif ($documentType == 2) {

        // file properties of new back document
        $fileTmpPath2 = $_FILES['document-back']['tmp_name'];
        $fileName2 = $_FILES['document-back']['name'];
        $fileExtension2 = pathinfo($fileName2, PATHINFO_EXTENSION);
        $newFileName2 = md5(time() . $fileName2) . '.' . $fileExtension2;
        $filePath2 = "document/$newFileName2";

        if ($newFileName1 == $newFileName2) {
            sleep(1);
            $newFileName2 = md5(time() . $fileName2) . '.' . $fileExtension2;
            $filePath2 = "document/$newFileName2";
        }

        // properties to be changed
        $properties['document_type'] = 'citizenship';
        $properties['document_front'] = $newFileName1;
        $properties['document_back'] = $newFileName2;

        try {
            // upload citizenship
            $bucket->upload(fopen($fileTmpPath1, 'r'), ['name' => $filePath1]);
            $status = true;
        } catch (Exception $e) {
        }

        if ($status == true) {
            try {
                $bucket->upload(fopen($fileTmpPath2, 'r'), ['name' => $filePath2]);
                $staus = true;
            } catch (Exception $e) {
                $status = false;
            }
        }

        if ($status == true) {
            $response = $database->getReference("users/{$userId}")->update($properties);
            $status = $response ? true : false;
        }
    }
}

echo $status ? "Document submitted successfully." : "An unexpected error occured.";