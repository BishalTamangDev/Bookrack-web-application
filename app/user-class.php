<?php

require_once __DIR__ . '/../../bookrack/app/connection.php';

class User
{
    private $userId;

    public $name = [
        "first" => "",
        "last" => "",
    ];
    public $email;
    private $password;
    private $contact;
    private $dob;
    public $gender;

    private $address = [
        "district" => "",
        "location" => ""
    ];
    public $profilePicture;

    private $kyc = [
        "document_type" => "",
        "front" => "",
        "back" => ""
    ];
    public $joinedDate;
    public $accountStatus;

    // Constructor
    public function __construct()
    {

        $this->userId = "";
        $this->name = [
            "first" => "",
            "last" => ""
        ];
        $this->email = "";
        $this->password = "";
        $this->contact = "";
        $this->dob = "";
        $this->gender = "";
        $this->address = [
            "district" => "",
            "location" => "",
        ];
        $this->profilePicture = "";
        $this->kyc = [
            "document_type" => "",
            "front" => "",
            "back" => ""
        ];
        $this->joinedDate = "";
        $this->accountStatus = "";
    }

    public function setUser($userId, $name, $email, $password, $contact, $dob, $gender, $address, $profilePicture, $kyc, $joinedDate, $accountStatus)
    {
        $this->userId = $userId;
        $this->name = [
            "first" => $name['first'],
            "last" => $name['last'],
        ];
        $this->email = $email;
        $this->password = $password;
        $this->contact = $contact;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->address = [
            "district" => $address['district'],
            "location" => $address['location'],
        ];
        $this->profilePicture = $profilePicture;
        $this->kyc = [
            "document_type" => $kyc['document_type'],
            "front" => $kyc['front'],
            "back" => $kyc['back']
        ];
        $this->joinedDate = $joinedDate;
        $this->accountStatus = $accountStatus;
    }


    // Getters
    public function getUserId()
    {
        return $this->userId;
    }

    public function getFirstName()
    {
        return $this->name["first"];
    }

    public function getLastName()
    {
        return $this->name["last"];
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function getAddressDistrict()
    {
        return $this->address["district"];
    }

    public function getAddressLocation()
    {
        return $this->address["location"];
    }

    public function getAccountStatus()
    {
        return $this->accountStatus;
    }

    public function getJoinedDate()
    {
        return $this->joinedDate;
    }

    public function getDocumentType()
    {
        return $this->kyc["document_type"];
    }

    public function getKycFront()
    {
        return $this->kyc["front"];
    }

    public function getKycBack()
    {
        return $this->kyc["back"];
    }



    // Setters
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setFirstName($firstName)
    {
        $this->name["first"] = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->name["last"] = $lastName;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    public function setAddressDistrict($addressDistrict)
    {
        $this->address["district"] = $addressDistrict;
    }

    public function setAddressLocation($addressLocation)
    {
        $this->address["location"] = $addressLocation;
    }

    public function setAccountStatus($accountStatus)
    {
        $this->accountStatus = $accountStatus;
    }

    public function setgetJoinedDate($joinedDate)
    {
        $this->joinedDate = $joinedDate;
    }

    public function setDocumentType($documentType)
    {
        $this->kyc["document_type"] = $documentType;
    }

    public function setKycFront($kycFront)
    {
        $this->kyc["front"] = $kycFront;
    }

    public function setKycBack($kycBack)
    {
        $this->kyc["back"] = $kycBack;
    }


    // user registration
    public function registerUser()
    {
        global $database;

        $postData = [
            'name' => [
                'first' => $this->name['first'],
                'last' => $this->name['last'],
            ],
            'dob' => $this->getDob(),
            'gender' => $this->gender,
            'email' => $this->email,
            'password' => $this->getPassword(),
            'contact' => $this->getContact(),
            'address' => [
                'district' => $this->getAddressDistrict(),
                'location' => $this->getAddressLocation()
            ],
            'profile_picture' => $this->getProfilePicture(),
            'kyc' => [
                'document_type' => $this->getDocumentType(),
                'front' => $this->getKycFront(),
                'back' => $this->getKycBack(),
            ],
            'joined_date' => date("Y:m:d H:i:s"),
            'account_status' => 'pending'
        ];

        $postRef = $database->getReference("users")->push($postData);

        return $postRef ? true : false;
    }


    // fetch user details from the database
    function fetch($userId)
    {
        global $database;

        $response = $database->getReference("users")->getChild($userId)->getSnapshot()->getValue();

        if ($response) {
            $this->setUser($userId, $response['name'], $response['email'], $response['password'], $response['contact'], $response['dob'], $response['gender'], $response['address'], $response['profile_picture'], $response['kyc'], $response['joined_date'], $response['account_status']);
            return true;
        } else {
            return false;
        }
    }


    // authentication
    public function checkEmailExistence()
    {
        global $database;

        // fetching all users
        $response = $database->getReference("users")->getSnapshot()->getValue();

        // checking for the existence of email address
        $emailExists = false;
        foreach ($response as $key => $row) {
            if ($row['email'] == $this->email) {
                $this->setUserId($key);
                $emailExists = true;
            }
        }

        return $emailExists;
    }

    // verify password
    public function verifyPassword()
    {
        global $database;
        $response = $database->getReference("users")->getChild($this->getUserId())->getSnapshot()->getValue();
        return password_verify($this->getPassword(), $response['password']) ? true : false;
    }

    public function getProfilePictureImageUrl()
    {
        global $bucket;

        $profilePictureUrl = "/bookrack/assets/images/blank-user.jpg";

        $prefix = 'users/';
        $options = [
            'prefix' => $prefix,
            'delimiter' => '/'
        ];

        $objects = $bucket->objects($options);

        foreach ($objects as $object) {
            // Check if the object's name (filename) matches the filename we are looking for
            if ($object->name() === $prefix . $this->getProfilePicture()) {
                // Generate a signed URL valid until tomorrow for the matched object
                $profilePictureUrl = $object->signedUrl(new DateTime('tomorrow'));
                break; // Exit the loop once we find the matching filename
            }
        }

        return $profilePictureUrl;
    }

    public function getKycFrontUrl()
    {
        global $bucket;

        $kycFrontUrl = "blank";

        if ($this->getKycFront() != "") {
            $prefix = 'kyc/';
            $options = [
                'prefix' => $prefix,
                'delimiter' => '/'
            ];

            $objects = $bucket->objects($options);

            foreach ($objects as $object) {
                // Check if the object's name (filename) matches the filename we are looking for
                if ($object->name() === $prefix . $this->getKycFront()) {
                    // Generate a signed URL valid until tomorrow for the matched object
                    $kycFrontUrl = $object->signedUrl(new DateTime('tomorrow'));
                    break; // Exit the loop once we find the matching filename
                }
            }
        }

        return $kycFrontUrl;
    }

    public function getKycBackUrl()
    {
        global $bucket;

        $kycBackUrl = "blank";

        if ($this->getKycBack() != "") {
            $prefix = 'kyc/';
            $options = [
                'prefix' => $prefix,
                'delimiter' => '/'
            ];

            $objects = $bucket->objects($options);

            foreach ($objects as $object) {
                // Check if the object's name (filename) matches the filename we are looking for
                if ($object->name() === $prefix . $this->getKycBack()) {
                    // Generate a signed URL valid until tomorrow for the matched object
                    $kycBackUrl = $object->signedUrl(new DateTime('tomorrow'));
                    break; // Exit the loop once we find the matching filename
                }
            }
        }

        return $kycBackUrl;
    }

    // update profile details
    public function updateProfile()
    {

    }

    // fetch all users
    public function fetchAllUsers()
    {
        global $database;

        // fetching all users
        $response = $database->getReference("users")->getSnapshot()->getValue();

        return $response;
    }
}