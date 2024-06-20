<?php

require_once __DIR__ . '/../../../bookrack/app/connection.php';

class Admin
{
    private $adminId;

    private $name = [
        "first" => "",
        "last" => ""
    ];

    private $gender;
    private $dob;
    private $email;
    private $password;
    private $contact;
    private $profilePicture;

    private $kyc = [
        "docyment_type" => "",
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
        $this->contact = "";
        $this->profilePicture = "";

        $this->kyc = [
            "document_type" => "",
            "front" => "",
            "back" => ""
        ];
        $this->joinedDate = "";
        $this->accountStatus = "";
    }


    public function setAdmin($adminId, $firstName, $lastName, $gender, $dob, $email, $password, $contact, $profilePicture, $documentType, $kycFront, $kycBack, $joinedDate, $accountStatus)
    {
        $this->adminId = $adminId;
        $this->name = [
            "first" => $firstName,
            "last" => $lastName,
        ];
        $this->gender = $gender;
        $this->dob = $dob;
        $this->email = $email;
        $this->password = $password;
        $this->contact = $contact;
        $this->profilePicture = $profilePicture;
        $this->kyc = [
            "document_type" => $documentType,
            "front" => $kycFront,
            "back" => $kycBack
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
        return $this->name["first"];
    }

    public function getLastName()
    {
        return $this->name["last"];
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

    public function getContact()
    {
        return $this->contact;
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
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

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
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
                'first' => $this->getFirstName(),
                'last' => $this->getLastName(),
            ],
            'gender' => $this->getGender(),
            'dob' => $this->getDob(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'contact' => $this->getContact(),
            'profile_picture' => $this->getProfilePicture(),
            'kyc' => [
                'document_type' => $this->getKycDocumentType(),
                'front' => $this->getKycFront(),
                'back' => $this->getKycBack(),
            ],
            'joined_date' => date("Y:m:d H:i:s"),
            'account_status' => 'pending',
        ];

        $status = $database->getReference("admins")->push($adminData);

        return $status ? true : false;
    }


    // fetch admin details from the database
    function fetch($adminId)
    {
        global $database;

        $response = $database->getReference("admins")->getChild($adminId)->getSnapshot()->getValue();

        if ($response) {
            $this->setAdmin($adminId, $response['name']['first'], $response['name']['last'], $response['gender'], $response['dob'], $response['email'], $response['password'], $response['contact'], $response['profile_picture'], $response['kyc']['document_type'], $response['kyc']['front'], $response['kyc']['back'], $response['joined_date'] , $response['account_status']);
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
        $response = $database->getReference("admins")->getSnapshot()->getValue();

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
        $response = $database->getReference("admins")->getChild($this->getId())->getSnapshot()->getValue();
        return password_verify($this->getPassword(), $response['password']) ? true : false;
    }

    public function getProfilePictureImageUrl()
    {
        global $bucket;

        $profilePictureUrl = "/bookrack/assets/images/blank-user.jpg";

        $prefix = 'admins/';
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

    // update profile details
    public function updateProfile()
    {

    }
}