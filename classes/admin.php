<?php
require_once __DIR__ . '/../app/connection.php';
require_once __DIR__ . '/../functions/district-array.php';

class Admin
{
    // auth
    private $adminId;
    private $displayName;
    public $email;
    public $emailVerified;
    private $password;
    private $phoneNumber;
    public $photoUrl;
    public $disabled;

    // realtime db
    public $nameFirst = '';
    public $nameLast = '';
    public $photo;
    private $dob;
    private $addressDistrict = '';
    private $addressLocation = '';
    private $documentType = '';
    private $documentFront = '';
    private $documentBack = '';

    public $documentUrlFront = "";
    public $documentUrlBack = "";

    public $gender;
    public $joinedDate;
    public $role;
    public $accountStatus;

    // Constructor
    public function __construct()
    {
        $this->adminId = "";
        $this->nameFirst = "";
        $this->nameLast = "";
        $this->email = "";
        $this->emailVerified = false;
        $this->password = "";
        $this->phoneNumber = "";
        $this->dob = "";
        $this->gender = "";
        $this->addressDistrict = "";
        $this->addressLocation = "";
        $this->photo = "";
        $this->documentType = "";
        $this->documentFront = "";
        $this->documentBack = "";
        $this->joinedDate = "";
        $this->role = "admin";
        $this->accountStatus = "";
    }

    public function setAdmin($adminId, $nameFirst, $nameLast, $dob, $gender, $addressDistrict, $addressLocation, $photo, $documentType, $documentFront, $documentBack, $joinedDate, $role, $accountStatus)
    {
        if ($this->adminId == '')
            $this->adminId = $adminId;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->nameFirst != '' ? $nameFirst : '';
        $this->nameLast != '' ? $nameLast : '';
        $this->addressDistrict = $addressDistrict;
        $this->addressLocation = $addressLocation;
        if ($this->photo == '')
            $this->photo = $photo;
        $this->documentType = $documentType;
        $this->documentFront = $documentFront;
        $this->documentBack = $documentBack;
        $this->joinedDate = $joinedDate;
        $this->role = $role;
        if ($this->accountStatus == "")
            $this->accountStatus = $accountStatus;
    }

    public function setAdminAuth($adminId, $email, $emailVerified, $phoneNumber, $displayName, $photoUrl, $disabled)
    {
        if ($this->adminId == '')
            $this->adminId = $adminId;
        if ($this->email == '')
            $this->email = $email;
        $this->emailVerified = $emailVerified;
        $this->phoneNumber = $phoneNumber;
        $this->displayName = $displayName;
        $this->photoUrl = $photoUrl;
        $this->disabled = $disabled;
    }

    // Getters
    public function getId()
    {
        return $this->adminId;
    }

    public function getFullName()
    {
        return ucWords($this->nameFirst . ' ' . $this->nameLast);
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getFullAddress()
    {
        global $districtArray;
        if ($this->addressDistrict != '')
            return ucWords($this->addressLocation) . ', ' . ucWords($this->addressDistrict);
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
    public function setId($adminId)
    {
        $this->adminId = $adminId;
    }

    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setDocumentType($kycDocumentType)
    {
        $this->documentType = $kycDocumentType;
    }
    public function setDocumentFront($kycFront)
    {
        $this->documentFront = $kycFront;
    }

    public function setDocumentBack($kycBack)
    {
        $this->documentBack = $kycBack;
    }

    // admin registration
    // not needed
    public function register()
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
            'phoneNumber' => $this->phoneNumber,
            'address_district' => $this->addressDistrict,
            'address_location' => $this->addressLocation,
            'photo' => $this->photo,
            'document_type' => $this->documentType,
            'document_front' => $this->documentFront,
            'document_back' => $this->documentBack,
            'joined_date' => $currentDate,
            'role' => 'admin',
            'account_status' => 'pending'
        ];

        $postRef = $database->getReference("admins")->push($postData);

        return $postRef ? true : false;
    }

    public function checkAdminExistenceById($adminId)
    {
        $status = false;
        global $auth;
        global $database;

        try {
            $authResponse = $auth->getUser($adminId);
            $this->email = $authResponse->email;

            // id, name, email, account status
            $response = $database->getReference("admins")->getChild($adminId)->getSnapshot()->getValue();

            if (!is_null($response)) {
                $this->adminId = $adminId;
                $this->nameFirst = $response['name_first'];
                $this->nameLast = $response['name_last'];
                $this->accountStatus = $response['account_status'];
                $this->photo = $response['photo'];
                $status = true;
            }
        } catch (Exception $e) {
        }
        return $status;
    }

    // fetch admin details from the database
    public function fetch($adminId)
    {
        $status = false;
        global $auth;
        global $database;

        try {
            // auth
            $authResponse = $auth->getUser($adminId);
            $this->setAdminAuth($adminId, $authResponse->email, $authResponse->emailVerified, $authResponse->phoneNumber, $authResponse->displayName, $authResponse->photoUrl, $authResponse->disabled);

            // realtime db
            $response = $database->getReference("admins")->getChild($adminId)->getSnapshot()->getValue();
            $this->setAdmin($adminId, $response['name_first'], $response['name_last'], $response['dob'], $response['gender'], $response['address_district'], $response['address_location'], $response['photo'], $response['document_type'], $response['document_front'], $response['document_back'], $response['joined_date'], $response['role'], $response['account_status']);

            $status = true;
        } catch (Exception $e) {

        }
        return $status;
    }

    public function setPhotoUrl()
    {
        if ($this->photo != '') {

            global $bucket;

            $prefix = 'admins/';
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

    public function setdocumentUrl()
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
            $prefix = 'kyc/';
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
            $prefix = 'kyc/';
            $objectName = $prefix . $this->documentBack;
            $object = $bucket->object($objectName);

            if ($object->exists())
                $this->documentUrlBack = $object->signedUrl(new DateTime('tomorrow'));
            else
                $this->documentUrlBack = null;
        }
    }

    // fetch all users
    public function fetchAllAdmins()
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

    // check if the account is eligible to be verified
    public function checkAccountVerificationEligibility()
    {
        if (
            $this->nameFirst == '' ||
            $this->nameLast == '' ||
            $this->dob == '' ||
            $this->gender == '' ||
            $this->photo == '' ||
            $this->documentType == '' ||
            $this->documentFront == '' ||
            $this->documentBack == '' ||
            $this->accountStatus != "pending"
        ) {
            return false;
        } else {
            return true;
        }
    }

    // function to check if the id belong to the admin
    public function checkIfAdmin($id)
    {
        global $database;
        $isAdmin = false;

        $response = $database->getReference("admins")->getChild($id)->getSnapshot()->getValue();
        if (!is_null($response)) {
            if (isset($response['role'])) {
                if ($response['role'] == "admin") {
                    $isAdmin = true;
                }
            }
        }

        return $isAdmin;
    }


    // verify admin account
    function verifyAdminAccount($adminId)
    {
        global $database;
        $properties['account_status'] = "verified";
        $response = $database->getReference("admins/{$adminId}")->update($properties);
        return $response ? true : false;
    }
}