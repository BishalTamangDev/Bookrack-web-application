<?php

require_once __DIR__ . '/../../bookrack/app/connection.php';

require_once __DIR__ . '/../../bookrack/vendor/autoload.php';

// for user authentication
use Kreait\Firebase\Auth;

// for sending otp code to the email address
use PHPMailer\PHPMailer\PHPMailer;

// for checking the exception
use PHPMailer\PHPMailer\Exception;


// variables
$districtArray = array(
    "Achham",
    "Arghakhanchi",
    "Baglung",
    "Baitadi",
    "Bajhang",
    "Bajura",
    "Banke",
    "Bara",
    "Bardiya",
    "Bhaktapur",
    "Bhojpur",
    "Chitwan",
    "Dadeldhura",
    "Dailekh",
    "Dang",
    "Darchula",
    "Dhading",
    "Dhankuta",
    "Dhanusha",
    "Dolakha",
    "Dolpa",
    "Doti",
    "Gorkha",
    "Gulmi",
    "Humla",
    "Ilam",
    "Jajarkot",
    "Jhapa",
    "Jumla",
    "Kailali",
    "Kalikot",
    "Kanchanpur",
    "Kapilvastu",
    "Kaski",
    "Kathmandu",
    "Kavrepalanchok",
    "Khotang",
    "Lalitpur",
    "Lamjung",
    "Mahottari",
    "Makwanpur",
    "Manang",
    "Morang",
    "Mugu",
    "Mustang",
    "Myagdi",
    "Nawalparasi",
    "Nuwakot",
    "Okhaldhunga",
    "Palpa",
    "Panchthar",
    "Parbat",
    "Parsa",
    "Pyuthan",
    "Ramechhap",
    "Rasuwa",
    "Rautahat",
    "Rolpa",
    "Rukum",
    "Rupandehi",
    "Salyan",
    "Sankhuwasabha",
    "Saptari",
    "Sarlahi",
    "Sindhuli",
    "Sindhupalchok",
    "Siraha",
    "Solukhumbu",
    "Sunsari",
    "Surkhet",
    "Syangja",
    "Tanahu",
    "Taplejung",
    "Tehrathum",
    "Udayapur",
    "West Rukum",
    "Dhawalagiri (formerly Mustang)"
);     


// function to delete images form storage bucket
function deleteFileFromStorageBucket($folder, $filename)
{
    $status = false;
    global $bucket;

    if($folder == "users"){
        $prefix = 'users/';
    }elseif($folder == "kyc"){
        $prefix = 'kyc/';
    }else{
        $prefix = '';
    }

    $options = [
        'prefix' => $prefix,
        'delimiter' => '/'
    ];

    $objects = $bucket->objects($options);

    foreach ($objects as $object) {
        // Check if the object's name (filename) matches the filename we are looking for
        if ($object->name() === $prefix . $filename) {
            // Delete the object if the filename matches
            $object->delete();
            $status = true;
            break; // Exit the loop once we find and delete the matching filename
        }
    }

    return $status;
}

// function to generate OTP code
function generateOTP()
{
    return rand(100000, 999999); // Generate a 6-digit OTP
}

// sanitize email
function sanitizeEmail($email)
{
    return str_replace(['.', '@'], [',', ':'], $email);
}
//  formatted full name
function getFormattedName($first, $last){
    $first = trim(strtolower($first));
    $last = trim(strtolower($last));
    
    $first[0] = strtoupper($first[0]);
    $last[0] = strtoupper($last[0]);

    $fullName = $first." ".$last;
    
    return $fullName;
}


// pascal case string
function getPascalCaseString($str){
    $finalString = trim(strtolower($str));
    $finalString[0] = strtoupper($finalString[0]);

    if ($finalString == 'null') {
        $finalString = "";
    }

    return $finalString;
}

function getLowerCaseString($str){
    $str = trim(strtolower($str));
    return $str;
}

// get array index value
function getArrayIndexValue($dataToBeSearched, $which){
    global $districtArray;

    if($which == "district"){
        $index = array_search($dataToBeSearched, $districtArray);
    }else{
        $index = "";
    }

    return $index;
} 


// formatted price
function getFormattedPrice($price)
{
    $x = strval($price);

    $digitCount = strlen($x);
    $formattedPrice = "";

    switch ($digitCount) {
        case 4:
            $formattedPrice = 'NRs. ' . $x[0] . "," . $x[1] . $x[2] . $x[3];
            break;
        case 5:
            $formattedPrice = 'NRs. ' . $x[0] . $x[1] . "," . $x[2] . $x[3] . $x[4];
            break;
        case 6:
            $formattedPrice = 'NRs. ' . $x[0] . "," . $x[1] . $x[2] . "," . $x[3] . $x[4] . $x[5];
            break;
        case 7:
            $formattedPrice = 'NRs. ' . $x[0] . $x[1] . "," . $x[2] . $x[3] . "," . $x[4] . $x[5] . $x[6];
            break;
        case 8:
            $formattedPrice = 'NRs. ' . $x[0] . "," . $x[1] . $x[2] . "," . $x[3] . $x[4] . "," . $x[5] . $x[6] . $x[7];
            break;
        case 9:
            $formattedPrice = 'NRs. ' . $x[0] . $x[1] . "," . $x[2] . $x[3] . "," . $x[4] . $x[5] . "," . $x[6] . $x[7] . $x[8];
            break;
        case 10:
            $formattedPrice = 'NRs. ' . $x[0] . "," . $x[1] . $x[2] . "," . $x[3] . $x[4] . "," . $x[5] . $x[6] . "," . $x[7] . $x[8] . $x[9];
            break;
        default:
            $formattedPrice = 'NRS. ' . $x;
    }

    return $formattedPrice;
}