<?php

require_once __DIR__ . '/../../../bookrack/app/connection.php';

class Admin
{
    private $adminId;
    private $firstName;
    private $lastName;
    private $gender;
    private $email;
    private $password;
    private $contact;
    private $profilePicture;
    private $kycFront;
    private $kycBack;
    private $joinedDate;
    private $accountStatus;
    

    // Constructor
    public function __construct()
    {
        $this->adminId = 0;
        $this->firstName = "";
        $this->lastName = "";
        $this->gender = "";
        $this->email = "";
        $this->password = "";
        $this->contact = "";
        $this->profilePicture = "";
        $this->kycFront = "";
        $this->kycBack = "";
        $this->joinedDate = "";
        $this->accountStatus = "";
    }


    public function setAdmin($adminId, $firstName, $lastName, $gender, $email, $password, $contact, $profilePicture, $kycFront, $kycBack, $joinedDate, $accountStatus)
    {
        $this->adminId = $adminId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->email = $email;
        $this->password = $password;
        $this->contact = $contact;
        $this->profilePicture = $profilePicture;
        $this->kycFront = $kycFront;
        $this->kycBack = $kycBack;
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
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getGender()
    {
        return $this->gender;
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

    public function getKycFront(){
        return $this->kycFront;
    }

    public function getKycBack(){
        return $this->kycBack;
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
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setGender($gender){
        $this->gender = $gender;
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

    public function setKycFront($kycFront)
    {
        $this->kycFront = $kycFront;
    }

    public function setKycBack($kycBack)
    {
        $this->kycBack = $kycBack;
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
            'account_status' => 'pending',
            'contact' => $this->getContact(),
            'email' => $this->getEmail(),
            'gender' => $this->getGender(),
            'joined_date' => date("Y:m:d H:i:s"),
            'kyc' => [
                'front' => $this->getKycFront(),
                'back' => $this->getKycBack(),
            ],
            'name' => [
                'first' => $this->getFirstName(),
                'last' => $this->getLastName(),
            ],
            'password' => $this->getPassword(),
            'profile_picture' => $this->getProfilePicture(),
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
            $this->setAdmin($adminId, $response['name']['first'], $response['name']['last'], $response['gender'], $response['email'], $response['password'], $response['contact'], $response['profile_picture'], $response['kyc']['front'], $response['kyc']['back'], $response['joined_date'] , $response['account_status']);
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