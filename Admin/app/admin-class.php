<?php

require_once __DIR__ . '/../../../bookrack/app/connection.php';

class Admin
{
    private $adminId;

    public $name = [
        "first" => "",
        "last" => ""
    ];

    public $gender;
    public $email;
    private $dob;
    private $password;
    private $phoneNumber;
    public $photo;
    public $photoUrl;

    private $kyc = [
        "docyment_type" => "",
        "front" => "",
        "back" => "",
    ];

    public $kycUrl = [
        "front" => "",
        "back" => "",
    ];

    private $joinedDate;
    private $accountStatus;

    // Constructor
    public function __construct()
    {
        $this->adminId = 0;
        $this->name = [
            "first" => "",
            "last" => ""
        ];
        $this->gender = "";
        $this->dob = "";
        $this->email = "";
        $this->password = "";
        $this->phoneNumber = "";
        $this->photo = "";
        $this->photoUrl = "";

        $this->kyc = [
            "document_type" => "",
            "front" => "",
            "back" => ""
        ];
        $this->joinedDate = "";
        $this->accountStatus = "";
    }


    public function setAdmin($adminId, $name, $gender, $dob, $email, $password, $phoneNumber, $photo, $kyc, $joinedDate, $accountStatus)
    {
        $this->adminId = $adminId;
        $this->name = [
            "first" => $name['first'],
            "last" => $name['last'],
        ];
        $this->gender = $gender;
        $this->dob = $dob;
        $this->email = $email;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->photo = $photo;
        $this->kyc = [
            "document_type" => $kyc['document_type'],
            "front" => $kyc['front'],
            "back" => $kyc['back']
        ];
        $this->joinedDate = $joinedDate;
        $this->accountStatus = $accountStatus;
    }


    // Getters
    public function getId()
    {
        return $this->adminId;
    }

    public function getFirstName()
    {
        return ucfirst($this->name['first']);
    }

    public function getLastName()
    {
        return ucfirst($this->name["last"]);
    }

    public function getFullName()
    {
        return ucWords($this->name['first'].' '.$this->name["last"]);
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    public function getKycDocumentType(){
        return $this->kyc['document_type'];
    }

    public function getKycFront(){
        return $this->kyc['front'];
    }

    public function getKycBack(){
        return $this->kyc['back'];
    }

    public function getAccountStatus()
    {
        return $this->accountStatus;
    }

    public function getJoinedDate()
    {
        return $this->joinedDate;
    }


    // Setters
    public function setId($adminId)
    {
        $this->adminId = $adminId;
    }

    public function setFirstName($firstName)
    {
        $this->name["first"] = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->name["last"] = $lastName;
    }

    public function setGender($gender){
        $this->gender = $gender;
    }
    
    public function setDob($dob){
        $this->dob = $dob;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function setKycDocumentType($kycDocumentType)
    {
        $this->kyc["document_type"] = $kycDocumentType;
    }
    public function setKycFront($kycFront)
    {
        $this->kyc["front"] = $kycFront;
    }

    public function setKycBack($kycBack)
    {
        $this->kyc["back"] = $kycBack;
    }

    public function setAccountStatus($accountStatus)
    {
        $this->accountStatus = $accountStatus;
    }

    public function setJoinedDate($joinedDate)
    {
        $this->joinedDate = $joinedDate;
    }

    // admin registration
    public function register()
    {
        global $database;

        $adminData = [
            'name' => [
                'first' => $this->name['first'],
                'last' => $this->name['last'],
            ],
            'gender' => $this->gender,
            'dob' => $this->dob,
            'email' => $this->email,
            'password' => $this->password,
            'phoneNumber' => $this->phoneNumber,
            'profile_picture' => $this->photo,
            'kyc' => [
                'document_type' => $this->kyc['document_type'],
                'front' => $this->kyc['first'],
                'back' => $this->kyc['last'],
            ],
            'joined_date' => date("Y:m:d H:i:s"),
            'account_status' => 'pending',
        ];

        $status = $database->getReference("admins")->push($adminData);

        return $status ? true : false;
    }


    // fetch admin details from the database
    public function fetch($adminId)
    {
        global $database;
        $response = $database->getReference("admins")->getChild($adminId)->getSnapshot()->getValue();

        if ($response) {
            $this->setAdmin($adminId, $response['name'], $response['gender'], $response['dob'], $response['email'], $response['password'], $response['phoneNumber'], $response['profile_picture'], $response['kyc'], $response['joined_date'] , $response['account_status']);
            // set profile picture url
            $this->setPhotoUrl();
            return true;
        } else {
            return false;
        }
    }

    // authentication
    public function checkEmailExistence()
    {
        global $database;

        // fetching all admins
        $response = $database->getReference('admins')->getSnapshot()->getValue();

        // checking for the existence of email address
        $emailExists = false;
        foreach ($response as $key => $row) {
            if ($row['email'] == $this->email) {
                $this->setId($key);
                $emailExists = true;
            }
        }

        return $emailExists;
    }

    // verify password
    public function verifyPassword()
    {
        global $database;
        $response = $database->getReference("admins")->getChild($this->adminId)->getSnapshot()->getValue();
        return password_verify($this->getPassword(), $response['password']) ? true : false;
    }

    public function setPhotoUrl()
    {
        global $bucket;

        $prefix = 'admins/';
        $objectName = $prefix . $this->photo;

        $object = $bucket->object($objectName);

        if($object->exists())
            $this->photoUrl = $object->signedUrl(new DateTime('tomorrow'));
        else
            $this->photoUrl = null;
    }

    public function setKycUrl()
    {
        global $bucket;

        if ($this->getKycFront() != "") {
            $prefix = 'kyc/';

            $objectName = $prefix . $this->kyc['front'];
            $object = $bucket->object($objectName);

            if($object->exists())
                $this->kycUrl['front'] = $object->signedUrl(new DateTime('tomorrow'));

            $objectName = $prefix . $this->kyc['back'];
            $object = $bucket->object($objectName);

            if($object->exists())
                $this->kycUrl['back'] = $object->signedUrl(new DateTime('tomorrow'));
        }
    }
}