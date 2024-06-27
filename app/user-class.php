<?php
require_once __DIR__ . '/../../bookrack/app/functions.php';
require_once __DIR__ . '/../../bookrack/app/connection.php';

class User
{
    private $password;
    private $userId;
    private $contact;

    private $dob;
    private $address = [
        "district" => "",
        "location" => ""
    ];
    private $kyc = [
        "document_type" => "",
        "front" => "",
        "back" => ""
    ];

    private $kycUrl = [
        "document_type" => "",
        "front" => "",
        "back" => ""
    ];
    public $name = [
        "first" => "",
        "last" => "",
    ];
    public $email;
    public $gender;

    public $profilePicture;
    public $profilePictureUrl;

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

    public function getFullName(){
        return  ucWords($this->name['first'].' '.$this->name['last']);
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

    public function getFullAddress(){
        global $districtArray;
        return ucWords($this->address['location'].', '.$districtArray[$this->address['district']]);
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
            'dob' => $this->dob,
            'gender' => $this->gender,
            'email' => $this->email,
            'password' => $this->password,
            'contact' => $this->contact,
            'address' => [
                'district' => $this->address['district'],
                'location' => $this->address['location']
            ],
            'profile_picture' => $this->profilePicture,
            'kyc' => [
                'document_type' => $this->kyc['document_type'],
                'front' => $this->kyc['front'],
                'back' => $this->kyc['back'],
            ],
            'joined_date' => date("Y:m:d H:i:s"),
            'account_status' => 'pending'
        ];

        $postRef = $database->getReference("users")->push($postData);

        return $postRef ? true : false;
    }


    // fetch user details from the database
    public function fetch($userId)
    {
        global $database;

        $response = $database->getReference("users")->getChild($userId)->getSnapshot()->getValue();

        if ($response) {
            $this->setUser($userId, $response['name'], $response['email'], $response['password'], $response['contact'], $response['dob'], $response['gender'], $response['address'], $response['profile_picture'], $response['kyc'], $response['joined_date'], $response['account_status']);
            // set profile picture
            $this->setProfilePictureUrl();
            return true;
        } else{
            return false;
        }
    }
    
    public function setProfilePictureUrl(){
        global $bucket;
                
        $prefix = 'users/';
        $objectName = $prefix . $this->profilePicture;
        
        $object = $bucket->object($objectName);
        
        if($object->exists())
            $this->profilePictureUrl = $object->signedUrl(new DateTime('tomorrow'));
        else
            $this->profilePictureUrl = null;
    }

    public function getProfilePictureUrl(){
        return $this->profilePictureUrl;
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

    public function setKycUrl()
    {
        global $bucket;

        $kycFrontFound = false;
        $kycBackFound = false;

        $prefix = 'kyc/';
        $options = [
            'prefix' => $prefix,
            'delimiter' => '/'
        ];

        $objects = $bucket->objects($options);

        foreach ($objects as $object) {
            if (!$kycFrontFound && $object->name() === $prefix . $this->kyc['front']) {
                $this->kycUrl['front'] = $object->signedUrl(new DateTime('tomorrow'));
                $kycFrontFound = true;
            } elseif (!$kycBackFound && $object->name() === $prefix . $this->kyc['back']) {
                $this->kycUrl['back'] = $object->signedUrl(new DateTime('tomorrow'));
                $kycBackFound = true;
            }

            if ($kycFrontFound && $kycBackFound)
                break;
        }
    }

    public function setKycFrontUrl()
    {
        global $bucket;

        if ($this->getKycFront() != "") {
            $prefix = 'kyc/';
            $objectName = $prefix . $this->kyc['front'];
            $object = $bucket->object($objectName);

            if($object->exists())
                $this->kycUrl['front'] = $object->signedUrl(new DateTime('tomorrow'));
            else
                $this->kycUrl['front'] = null;

        }
    }

    public function setKycBackUrl()
    {
        
        if ($this->getKycBack() != "") {
            global $bucket;
            $prefix = 'kyc/';
            $objectName = $prefix . $this->kyc['back'];
            $object = $bucket->object($objectName);

            if($object->exists())
                $this->kycUrl['back'] = $object->signedUrl(new DateTime('tomorrow'));
            else
                $this->kycUrl['back'] = null;

        }
    }

    public function getKycFrontUrl(){
        return $this->kycUrl['front'];
    }

    public function getKycBackUrl(){
        return $this->kycUrl['back'];
    }

    // fetch all users
    public function fetchAllUsers()
    {
        global $database;

        $list = array();

        // fetching all users
        $response = $database->getReference("users")->getSnapshot()->getValue();

        foreach($response as $key => $res){
            $temp = new User();
            $temp->fetch($key);    
            $list [] = $temp;
        }

        return $list;
    }
}