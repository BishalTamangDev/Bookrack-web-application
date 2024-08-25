<?php
$status = false;
$adminId = $_POST['document-admin-id'] ?? 0;

if ($adminId == 0) {
    echo $status;
    exit;
}

require_once __DIR__ . '/../../classes/admin.php';
require_once __DIR__ . '/../../functions/delete-cloud-file.php';

$tempAdmin = new Admin();
$tempAdmin->fetch($adminId);

// getting the form details
$hasDocumentFront = (isset($_FILES['kyc-front']) && $_FILES['kyc-front']['error'] === UPLOAD_ERR_OK) ? 1 : 0;
$hasDocumentBack = (isset($_FILES['kyc-back']) && $_FILES['kyc-back']['error'] === UPLOAD_ERR_OK) ? 1 : 0;

// get file details
$oldFileFront = $tempAdmin->getDocumentFront();
$oldFileBack = $tempAdmin->getDocumentBack();

// birth certificate as kyc
if ($hasDocumentFront && $hasDocumentBack) {
    // file properties of new front document
    $fileTmpPath1 = $_FILES['kyc-front']['tmp_name'];
    $fileName1 = $_FILES['kyc-front']['name'];
    $fileExtension1 = pathinfo($fileName1, PATHINFO_EXTENSION);
    $newFileName1 = md5(time() . $fileName1) . '.' . $fileExtension1;
    $filePath1 = 'document/' . $newFileName1;

    // file properties of new back document
    $fileTmpPath2 = $_FILES['kyc-back']['tmp_name'];
    $fileName2 = $_FILES['kyc-back']['name'];
    $fileExtension2 = pathinfo($fileName2, PATHINFO_EXTENSION);
    $newFileName2 = md5(time() . $fileName2) . '.' . $fileExtension2;
    $filePath2 = 'document/' . $newFileName2;

    if ($newFileName1 == $newFileName2) {
        sleep(1);
        $newFileName2 = md5(time() . $fileName2) . '.' . $fileExtension2;
        $filePath2 = 'document/' . $newFileName2;
    }

    // properties to be changed
    $properties['document_type'] = 'citizenship';
    $properties['document_front'] = $newFileName1;
    $properties['document_back'] = $newFileName2;

    try {
        // upload citizenship
        $bucket->upload(fopen($fileTmpPath1, 'r'), ['name' => $filePath1]);
        $bucket->upload(fopen($fileTmpPath2, 'r'), ['name' => $filePath2]);

        try {
            // update user detail
            $response = $database->getReference("admins/{$adminId}")->update($properties);
            $status = true;
        } catch (Exception $e) {
            $status = false;
        }
    } catch (Exception $e) {
        $status = false;
    }

    // in case photo uploaded, delete the existing kyc document
    if ($status) {
        // delete previous kyc front
        if ($oldFileFront != "") {
            $temp = deleteFileFromStorageBucket("document", $oldFileFront);
        }

        // delete prvious kyc back
        if ($oldFileBack != "") {
            $temp = deleteFileFromStorageBucket("document", $oldFileBack);
        }
    }
}

echo $status;

exit;