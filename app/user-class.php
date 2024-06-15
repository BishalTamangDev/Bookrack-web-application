<?php

require_once __DIR__ . '/../../bookrack/app/connection.php';

class User
{
    // user properties
    private $accountStatus;
    private $addressDistrict;
    private $addressLocation;
    private $contact;
    private $dob;
    private $email;
    private $firstName;
    private $gender;
    private $lastName;
    private $password;
    private $profilePicture;
    private $userId;

    // Constructor
    public function __construct() {
        $this->accountStatus  = "";
        $this->addressDistrict  = "";
        $this->addressLocation  = "";
        $this->contact  = "";
        $this->dob  = "";
        $this->email  = "";
        $this->firstName  = "";
        $this->gender  = "";
        $this->lastName  = "";
        $this->password  = "";
        $this->profilePicture  = "";
        $this->userId = "";
    }

    public function setUser($userId, $firstName, $lastName, $email, $password, $contact, $dob, $gender, $profilePicture, $addressDistrict, $addressLocation, $accountStatus) {
        $this->accountStatus  = $accountStatus;;
        $this->addressDistrict  = $addressDistrict;
        $this->addressLocation  = $addressLocation;
        $this->contact  = $contact;
        $this->dob  = $dob;
        $this->email  = $email;
        $this->firstName  = $firstName;
        $this->gender  = $gender;
        $this->lastName  = $lastName;
        $this->password  = $password;
        $this->profilePicture  = $profilePicture;
        $this->userId = $userId;
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


    // user registration
    public function registerUser(){
        global $database;       
        $postData = [
            'account_status' => 'active',
            'address_district' => $this->getAddressDistrict(),
            'address_location' => $this->getAddressLocation(),
            'contact' => $this->getContact(),
            'dob' => $this->getDob(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'gender' => $this->getGender(),
            'last_name' => $this->getLastName(),
            'password' => $this->getPassword(),
            'profile_picture' => $this->getProfilePicture(),
        ];

        $postRef = $database->getReference("users")->push($postData);

        return $postRef ? true : false;  
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

    public function verifyPassword()
    {
        global $database;
        $response = $database->getReference("users")->getChild($this->getUserId())->getSnapshot()->getValue();
        return password_verify($this->getPassword(), $response['password']) ? true : false;
    }
}
