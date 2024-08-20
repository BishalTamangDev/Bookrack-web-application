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

    private $kycUrl = [
        'front' => '',
        'back' => ''
    ];

    public $gender;

    public $joinedDate;
    public $role;
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
        $this->role = "user";
    }

    public function setUser($userId, $name, $dob, $gender, $address, $photo, $kyc, $joinedDate, $accountStatus, $role)
    {
        $this->userId = $userId;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->name = [
            "first" => $name['first'],
            "last" => $name['last'],
        ];
        $this->address = [
            "district" => $address['district'],
            "location" => $address['location'],
        ];
        $this->photo = $photo;
        $this->kyc = [
            "document_type" => $kyc['document_type'],
            "front" => $kyc['front'],
            "back" => $kyc['back']
        ];
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
        return ucWords($this->name['first'] . ' ' . $this->name['last']);
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
        return $this->address["district"];
    }

    public function getAddressLocation()
    {
        return $this->address["location"];
    }

    public function getFullAddress()
    {
        global $districtArray;
        if ($this->address['district'] != '')
            return ucWords($this->address['location'] . ', ' . $districtArray[$this->address['district']]);
        else
            return '-';
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
        $this->address["district"] = $addressDistrict;
    }

    public function setAddressLocation($addressLocation)
    {
        $this->address["location"] = $addressLocation;
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
    // not needed
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
            $this->setUser($userId, $response['name'], $response['dob'], $response['gender'], $response['address'], $response['photo'], $response['kyc'], $response['joined_date'], $response['account_status'], $response['role']);

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

    public function getKycFrontUrl()
    {
        return $this->kycUrl['front'];
    }

    public function getKycBackUrl()
    {
        return $this->kycUrl['back'];
    }

    // count total users
    public function countTotalUsers(){
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
            $this->name['first'] == '' ||
            $this->name['last'] == '' ||
            $this->dob == '' ||
            $this->gender == '' ||
            $this->phoneNumber == '' ||
            $this->address['district'] == '' ||
            $this->address['location'] == '' ||
            $this->phoneNumber == '' ||
            $this->photo == '' ||
            $this->kyc["document_type"] == '' ||
            $this->kyc["front"] == '' ||
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
            $userName = $response['name']['first'] . ' ' . $response['name']['last'];

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
}