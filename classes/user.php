<?php
require_once __DIR__ . '/../functions/district-array.php';
require_once __DIR__ . '/../app/connection.php';

class User
{
    // auth
    private $password;
    private $userId;
    public $displayName;
    private $phoneNumber;
    public $photoUrl;
    public $email;
    public $disabled;
    public $emailVerified;

    // realtime database
    public $nameFirst = "";
    public $nameLast = "";
    public $photo;
    private $dob;
    private $addressDistrict = "";
    private $addressWard = "";
    private $addressMunicipality = '';
    private $addressToleVillage = '';
    private $documentType = "";
    private $documentFront = "";
    private $documentBack = "";
    public $documentUrlFront = '';
    public $documentUrlBack = '';
    public $gender;
    public $joinedDate;
    public $role;
    public $accountStatus;

    // Constructor
    public function __construct()
    {
        $this->userId = "";
        $this->nameFirst = "";
        $this->nameLast = "";
        $this->email = "";
        $this->emailVerified = false;
        $this->password = "";
        $this->phoneNumber = "";
        $this->dob = "";
        $this->gender = "";
        $this->addressDistrict = "";
        $this->addressMunicipality = "";
        $this->addressWard = "";
        $this->addressToleVillage = "";
        $this->photo = "";
        $this->documentType = "";
        $this->documentFront = "";
        $this->documentBack = "";
        $this->joinedDate = "";
        $this->role = "user";
    }

    public function setUser($userId, $nameFirst, $nameLast, $dob, $gender, $addressDistrict, $addressMunicipality, $addressWard, $addressToleVillage, $photo, $documentType, $documentFront, $documentBack, $joinedDate, $accountStatus, $role)
    {
        $this->userId = $userId;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->nameFirst = $nameFirst;
        $this->nameLast = $nameLast;
        $this->addressDistrict = $addressDistrict;
        $this->addressMunicipality = $addressMunicipality;
        $this->addressWard = $addressWard;
        $this->addressToleVillage = $addressToleVillage;
        $this->photo = $photo;
        $this->documentType = $documentType;
        $this->documentFront = $documentFront;
        $this->documentBack = $documentBack;
        $this->role = $role;
        $this->joinedDate = $joinedDate;
        $this->accountStatus = $accountStatus;
    }

    public function setUserAuth($userId, $email, $emailVerified, $phoneNumber, $displayName, $photoUrl, $disabled)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->emailVerified = $emailVerified;
        $this->phoneNumber = $phoneNumber;
        $this->displayName = $displayName;
        $this->photoUrl = $photoUrl;
        $this->disabled = $disabled;
    }

    // Getters
    public function getUserId()
    {
        return $this->userId;
    }

    public function getFullName()
    {
        return ucWords($this->nameFirst . ' ' . $this->nameLast);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getAddressDistrict()
    {
        return $this->addressDistrict;
    }

    public function getAddressMunicipality()
    {
        return $this->addressMunicipality;
    }

    public function getAddressWard()
    {
        return $this->addressWard;
    }

    public function getAddressToleVillage()
    {
        return $this->addressToleVillage;
    }

    public function getFullAddress()
    {
        global $districtArray;
        if ($this->addressDistrict != '')
            return ucWords($this->addressMunicipality) . '-' . $this->addressWard . ', ' . ucfirst($this->addressToleVillage) . ', ' . ucwords($this->addressDistrict);
        else
            return '-';
    }

    public function getDocumentType()
    {
        return $this->documentType;
    }

    public function getDocumentFront()
    {
        return $this->documentFront;
    }

    public function getDocumentBack()
    {
        return $this->documentBack;
    }


    // Setters
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setFirstName($firstName)
    {
        $this->nameFirst = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->nameLast = $lastName;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    public function setAddressDistrict($addressDistrict)
    {
        $this->addressDistrict = $addressDistrict;
    }

    public function setAddressMunicipality($addressMunicipality)
    {
        $this->addressMunicipality = $addressMunicipality;
    }


    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
    }

    public function setDocumentFront($kycFront)
    {
        $this->documentFront = $kycFront;
    }

    public function setDocumentBack($kycBack)
    {
        $this->documentBack = $kycBack;
    }


    // user registration
    // not needed
    public function registerUser()
    {
        global $database;

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        $postData = [
            'name_first' => $this->nameFirst,
            'name_last' => $this->nameLast,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'email' => $this->email,
            'emailVerified' => $this->emailVerified,
            'password' => $this->password,
            'phone_number' => $this->phoneNumber,
            'address_district' => $this->addressDistrict,
            'address_municipality' => $this->addressMunicipality,
            'address_ward' => $this->addressWard,
            'address_tole_village' => $this->addressToleVillage,
            'photo' => $this->photo,
            'document_type' => $this->documentType,
            'document_front' => $this->documentFront,
            'document_back' => $this->documentBack,
            'joined_date' => $currentDate,
            'account_status' => 'pending',
            'role' => 'user'
        ];

        $postRef = $database->getReference("users")->push($postData);

        return $postRef ? true : false;
    }

    // fetch user details from the database
    public function fetch($userId)
    {
        $status = false;
        global $auth;
        global $database;

        try {
            // auth
            $authResponse = $auth->getUser($userId);
            $this->setUserAuth($userId, $authResponse->email, $authResponse->emailVerified, $authResponse->phoneNumber, $authResponse->displayName, $authResponse->photoUrl, $authResponse->disabled);

            // realtime db
            $response = $database->getReference("users")->getChild($userId)->getSnapshot()->getValue();
            $this->setUser($userId, $response['name_first'], $response['name_last'], $response['dob'], $response['gender'], $response['address_district'], $response['address_municipality'], $response['address_ward'], $response['address_tole_village'], $response['photo'], $response['document_type'], $response['document_front'], $response['document_back'], $response['joined_date'], $response['account_status'], $response['role']);

            // set profile picture
            // $this->setPhotoUrl();
            $status = true;
        } catch (Exception $e) {

        }
        return $status;
    }

    public function checkUserExistenceById($userId)
    {
        $status = false;
        global $auth;

        try {
            // auth
            $authResponse = $auth->getUser($userId);
            $status = true;
        } catch (Exception $e) {

        }
        return $status;
    }

    public function fetchUserPhotoUrl()
    {
        global $database;
        try {
            $response = $database->getReference("users/" . $this->userId)->getSnapshot()->getValue();
            $this->photo = $response['photo'];
            $this->setPhotoUrl();
        } catch (Exception $e) {

        }
    }

    public function setPhotoUrl()
    {
        if ($this->photo != '') {
            global $bucket;

            $prefix = 'users/';
            $objectName = $prefix . $this->photo;

            $object = $bucket->object($objectName);

            if ($object->exists())
                $this->photoUrl = $object->signedUrl(new DateTime('tomorrow'));
            else
                $this->photoUrl = null;
        } else {
            $this->photoUrl = null;
        }
    }

    public function setDocumentUrl()
    {
        global $bucket;

        $kycFrontFound = false;
        $kycBackFound = false;

        $prefix = 'document/';
        $options = [
            'prefix' => $prefix,
            'delimiter' => '/'
        ];

        $objects = $bucket->objects($options);

        foreach ($objects as $object) {
            if (!$kycFrontFound && $object->name() === $prefix . $this->documentFront) {
                $this->documentUrlFront = $object->signedUrl(new DateTime('tomorrow'));
                $kycFrontFound = true;
            } elseif (!$kycBackFound && $object->name() === $prefix . $this->documentBack) {
                $this->documentUrlBack = $object->signedUrl(new DateTime('tomorrow'));
                $kycBackFound = true;
            }

            if ($kycFrontFound && $kycBackFound)
                break;
        }
    }

    public function setDocumentFrontUrl()
    {
        global $bucket;

        if ($this->getDocumentFront() != "") {
            $prefix = 'document/';
            $objectName = $prefix . $this->documentFront;
            $object = $bucket->object($objectName);

            if ($object->exists())
                $this->documentUrlFront = $object->signedUrl(new DateTime('tomorrow'));
            else
                $this->documentUrlFront = null;
        }
    }

    public function setDocumentBackUrl()
    {
        if ($this->getDocumentBack() != "") {
            global $bucket;
            $prefix = 'document/';
            $objectName = $prefix . $this->documentBack;
            $object = $bucket->object($objectName);

            if ($object->exists())
                $this->documentUrlBack = $object->signedUrl(new DateTime('tomorrow'));
            else
                $this->documentUrlBack = null;
        }
    }

    public function getDocumentFrontUrl()
    {
        return $this->documentUrlFront;
    }

    public function getDocumentBackUrl()
    {
        return $this->documentUrlBack;
    }

    // count total users
    public function countTotalUsers()
    {
        global $database;
        $count = $database->getReference("users")->getSnapshot()->numChildren();
        return $count;
    }

    // fetch all users
    public function fetchAllUsers()
    {
        global $database;

        $list = [];

        // fetching all users
        $response = $database->getReference("users")->getSnapshot()->getValue();

        foreach ($response as $key => $res) {
            $temp = new User();
            $temp->fetch($key);
            $list[] = $temp;
        }
        return $list;
    }

    // fet all user id
    public function fetchAllUserId()
    {
        global $database;
        $list = array();
        // fetching all users
        $response = $database->getReference("users")->getSnapshot()->getValue();
        if ($response) {
            foreach ($response as $key => $res)
                $list[] = $key;
        }
        return $list;
    }

    // check if the account is eligible to be verified
    public function checkAccountVerificationEligibility()
    {
        if (
            $this->nameFirst == '' ||
            $this->nameLast == '' ||
            $this->dob == '' ||
            $this->gender == '' ||
            $this->phoneNumber == '' ||
            $this->addressDistrict == '' ||
            $this->addressMunicipality == '' ||
            $this->addressWard == '' ||
            $this->addressToleVillage == '' ||
            $this->phoneNumber == '' ||
            $this->photo == '' ||
            $this->documentType == '' ||
            $this->documentFront == '' ||
            $this->accountStatus != "pending"
        ) {
            return false;
        } else {
            return true;
        }
    }

    // function to check if the id belong to the user
    public function checkIfUser($id)
    {
        global $database;
        $isUser = false;
        $reference = $database->getReference('users')->getChild($id);

        if ($reference) {
            $response = $reference->getSnapshot()->getValue();
            if (isset($response['role'])) {
                if ($response['role'] == "user") {
                    $isUser = true;
                }
            }
        }

        return $isUser;
    }

    // fetch user name
    public function fetchUserName($userId)
    {
        global $database;
        $userName = "-";
        $response = $database->getReference("users")->getChild($userId)->getSnapshot()->getValue();

        if ($response)
            $userName = $response['name_first'] . ' ' . $response['name_last'];

        return $userName;
    }

    // account verification
    public function accountVerification($userId)
    {
        global $database;
        $properties['account_status'] = "verified";
        $response = $database->getReference("users/{$userId}")->update($properties);
        return $response ? true : false;
    }

    // apply for account verification
    public function applyForVerification($userId)
    {
        global $database;
        $properties['account_status'] = "on-hold";
        $response = $database->getReference("users/{$userId}")->update($properties);
        return $response ? true : false;
    }

    // verify account
    public function verifyAccount($userId)
    {
        global $database;
        $properties['account_status'] = "verified";
        $response = $database->getReference("users/{$userId}")->update($properties);
        return $response ? true : false;
    }

    // unverify account
    public function unverifyAccount($userId)
    {
        global $database;
        $properties['account_status'] = "unverified";
        $response = $database->getReference("users/{$userId}")->update($properties);
        return $response ? true : false;
    }
}