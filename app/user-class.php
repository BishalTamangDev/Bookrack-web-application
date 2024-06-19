<?php

require_once __DIR__ . '/../../bookrack/app/connection.php';

class User
{
    // user properties
    private $userId;
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $contact;
    private $dob;
    private $gender;
    private $addressDistrict;
    private $addressLocation;
    private $profilePicture;
    private $documentType;

    private $kycFront;
    private $kycBack;
    private $joinedDate;
    private $accountStatus;

    //  $userId, $firstName, $lastName, $email $password,$contact,$dob,$gender,$addressDistrict,$addressLocation,$profilePicture,$kycFront,$kycBack,$joinedDate,$accountStatus

    // Constructor
    public function __construct()
    {

        $this->userId = "";
        $this->firstName = "";
        $this->lastName = "";
        $this->email = "";
        $this->password = "";
        $this->contact = "";
        $this->dob = "";
        $this->gender = "";
        $this->addressDistrict = "";
        $this->addressLocation = "";
        $this->profilePicture = "";
        $this->documentType = "";
        $this->kycFront = "";
        $this->kycBack = "";
        $this->joinedDate = "";
        $this->accountStatus = "";
    }

    public function setUser($userId, $firstName, $lastName, $email, $password, $contact, $dob, $gender, $addressDistrict, $addressLocation, $profilePicture, $documentType, $kycFront, $kycBack, $joinedDate, $accountStatus)
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->contact = $contact;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->addressDistrict = $addressDistrict;
        $this->addressLocation = $addressLocation;
        $this->profilePicture = $profilePicture;
        $this->documentType = $documentType;
        $this->kycFront = $kycFront;
        $this->kycBack = $kycBack;
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
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
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
        return $this->addressDistrict;
    }

    public function getAddressLocation()
    {
        return $this->addressLocation;
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
        return $this->documentType;
    }

    public function getKycFront()
    {
        return $this->kycFront;
    }

    public function getKycBack()
    {
        return $this->kycBack;
    }



    // Setters
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
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
        $this->addressDistrict = $addressDistrict;
    }

    public function setAddressLocation($addressLocation)
    {
        $this->addressLocation = $addressLocation;
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
        $this->documentType = $documentType;
    }

    public function setKycFront($kycFront)
    {
        $this->kycFront = $kycFront;
    }

    public function setKycBack($kycBack)
    {
        $this->kycBack = $kycBack;
    }


    // user registration
    public function registerUser()
    {
        global $database;

        $postData = [
            'name' => [
                'first' => $this->getFirstName(),
                'last' => $this->getLastName(),
            ],
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'contact' => $this->getContact(),
            'dob' => $this->getDob(),
            'gender' => $this->getGender(),
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
            $this->setUser($_SESSION['bookrack-user-id'], $response['name']['first'], $response['name']['last'], $response['email'], $response['password'], $response['contact'], $response['dob'], $response['gender'], $response['address']['district'], $response['address']['location'], $response['profile_picture'], $response['kyc']['document_type'], $response['kyc']['front'], $response['kyc']['back'], $response['joined_date'], $response['account_status']);
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
}