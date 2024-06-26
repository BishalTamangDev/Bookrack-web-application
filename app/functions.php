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

$genreArray = array(
    "Adventure",
    "Art",
    "Autobiography",
    "Biography",
    "Business",
    "Chick Lit",
    "Children's",
    "Classics",
    "Comics",
    "Contemporary",
    "Cookbooks",
    "Crime",
    "Drama",
    "Dystopian",
    "Economics",
    "Education",
    "Erotica",
    "Essays",
    "Fairy Tales",
    "Fantasy",
    "Fiction",
    "Folklore",
    "Graphic Novels",
    "Historical Fiction",
    "History",
    "Horror",
    "Humor",
    "Inspirational",
    "LGBTQ+",
    "Literary Fiction",
    "Manga",
    "Memoir",
    "Mystery",
    "Mythology",
    "New Adult",
    "Non-Fiction",
    "Paranormal",
    "Philosophy",
    "Poetry",
    "Politics",
    "Psychology",
    "Religion",
    "Romance",
    "Satire",
    "Science",
    "Science Fiction",
    "Self-Help",
    "Short Stories",
    "Spirituality",
    "Sports",
    "Steampunk",
    "Suspense",
    "Technology",
    "Thriller",
    "Travel",
    "True Crime",
    "Urban Fantasy",
    "Western",
    "Women's Fiction",
    "Young Adult (YA)"
);

function deleteFileFromStorageBucket($folder, $filename)
// function to delete images form storage bucket
{
    $status = false;
    global $bucket;

    if ($folder == "users") {
        $prefix = 'users/';
    } elseif ($folder == "kyc") {
        $prefix = 'kyc/';
    } else {
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

// get array index value
function getArrayIndexValue($dataToBeSearched, $which)
{
    global $districtArray;

    if ($which == "district") {
        $index = array_search($dataToBeSearched, $districtArray);
    } else {
        $index = "";
    }

    return $index;
}