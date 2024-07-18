<?php

require_once __DIR__ . '/../../app/connection.php';

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
    public $name = [
        'first' => '',
        'last' => '',
    ];
    public $photo;
    private $dob;
    private $address = [
        'district' => '',
        'location' => ''
    ];
    private $kyc = [
        'document_type' => '',
        'front' => '',
        'back' => ''
    ];

    public $kycUrl = [
        'front' => '',
        'back' => ''
    ];

    public $gender;
    public $joinedDate;
    public $accountStatus;

    // Constructor
    public function __construct()
    {
        $this->adminId = "";
        $this->name = [
            "first" => "",
            "last" => ""
        ];
        $this->email = "";
        $this->emailVerified = false;
        $this->password = "";
        $this->phoneNumber = "";
        $this->dob = "";
        $this->gender = "";
        $this->address = [
            "district" => "",
            "location" => "",
        ];
        $this->photo = "";
        $this->kyc = [
            "document_type" => "",
            "front" => "",
            "back" => ""
        ];
        $this->joinedDate = "";
        $this->accountStatus = "";
    }

    public function setAdmin($adminId, $name, $dob, $gender, $address, $photo, $kyc, $joinedDate, $accountStatus)
    {
        if ($this->adminId == '')
            $this->adminId = $adminId;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->name = [
            "first" => $this->name['first'] != '' ? $name['first'] : '',
            "last" => $name['last'],
        ];
        $this->address = [
            "district" => $address['district'],
            "location" => $address['location'],
        ];
        if ($this->photo == '')
            $this->photo = $photo;
        $this->kyc = [
            "document_type" => $kyc['document_type'],
            "front" => $kyc['front'],
            "back" => $kyc['back']
        ];
        $this->joinedDate = $joinedDate;
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
        return ucWords($this->name['first'] . ' ' . $this->name["last"]);
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
        if ($this->address['district'] != '')
            return ucWords($this->address['location'] . ', ' . $districtArray[$this->address['district']]);
        else
            return '-';
    }

    public function getKycDocumentType()
    {
        return $this->kyc['document_type'];
    }

    public function getKycFront()
    {
        return $this->kyc['front'];
    }

    public function getKycBack()
    {
        return $this->kyc['back'];
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

    // admin registration
    public function register()
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
            'emailVerified' => $this->emailVerified,
            'password' => $this->password,
            'phoneNumber' => $this->phoneNumber,
            'address' => [
                'district' => $this->address['district'],
                'location' => $this->address['location']
            ],
            'photo' => $this->photo,
            'kyc' => [
                'document_type' => $this->kyc['document_type'],
                'front' => $this->kyc['front'],
                'back' => $this->kyc['back'],
            ],
            'joined_date' => date("Y:m:d H:i:s"),
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
            $this->adminId = $adminId;
            $this->name['first'] = $response['name']['first'];
            $this->accountStatus = $response['account_status'];
            $this->photo = $response['photo'];
            $status = true;
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
            $this->setAdmin($adminId, $response['name'], $response['dob'], $response['gender'], $response['address'], $response['photo'], $response['kyc'], $response['joined_date'], $response['account_status']);

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

            if ($object->exists())
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

            if ($object->exists())
                $this->kycUrl['back'] = $object->signedUrl(new DateTime('tomorrow'));
            else
                $this->kycUrl['back'] = null;
        }
    }

    // fetch all users
    public function fetchAllAdmins()
    {
        global $database;

        $list = array();

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
        return ($this->phoneNumber != '' && $this->photo != '' && $this->kyc["document_type"] != '' && $this->kyc["front"] != '' && $this->accountStatus == "pending") ? true : false;
    }
}