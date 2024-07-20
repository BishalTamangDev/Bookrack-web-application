<?php

require_once __DIR__ . '/connection.php';

class Book
{
    private $bookId;
    public $title;
    public $description;
    public $language;
    public $genre;
    public $author;
    public $isbn;
    public $purpose;
    public $publisher;
    public $edition;
    public $price = [
        "actual" => "",
        "offer" => "",
    ];

    public $photo;
    public $photoUrl;

    public $addedDate;
    private $ownerId;
    public $flag;

    public function __construct()
    {
        $this->bookId = "";
        $this->title = "";
        $this->description = "";
        $this->language = "";
        $this->genre = [];
        $this->author = [];
        $this->isbn = "";
        $this->purpose = "";
        $this->publisher = "";
        $this->edition = "";

        $this->price = [
            "actual" => "",
            "offer" => "",
        ];

        $this->photo = "";
        $this->photoUrl = "empty";
        $this->addedDate = "";
        $this->ownerId = "";
        $this->flag = "verified";
    }

    public function setBook($bookId, $ownerId, $title, $description, $language, $genre, $author, $isbn, $purpose, $publisher, $edition, $price, $photo, $addedDate, $flag)
    {
        $this->bookId = $bookId;
        $this->ownerId = $ownerId;
        $this->title = $title;
        $this->description = $description;
        $this->language = $language;
        $this->genre = $genre;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->purpose = $purpose;
        $this->publisher = $publisher;
        $this->edition = $edition;

        $this->price = [
            "actual" => $price['actual'],
            "offer" => $price['offer']
        ];

        $this->photo = $photo;
        $this->addedDate = $addedDate;
        $this->flag = $flag;
    }


    // Getters
    public function getId()
    {
        return $this->bookId;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getActualPrice()
    {
        return $this->price['actual'];
    }

    public function getOfferPrice()
    {
        return $this->price['offer'];

    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getAddedDate()
    {
        return $this->addedDate;
    }

    // Setter function
    public function setId($id)
    {
        $this->bookId = $id;
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    public function setActualPrice($actualPrice)
    {
        $this->price["actual"] = $actualPrice;
    }

    public function setOfferPrice($offerPrice)
    {
        $this->price["offer"] = $offerPrice;

    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function setApprovalDate($addedDate)
    {
        $this->addedDate = $addedDate;
    }

    // book registration
    public function register()
    {
        global $database;

        $postData = [
            'owner_id' => $this->ownerId,
            'title' => $this->title,
            'description' => $this->description,
            'language' => $this->language,
            'genre' => $this->genre,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'purpose' => $this->purpose,
            'publisher' => $this->publisher,
            'edition' => $this->edition,
            'price' => [
                'actual' => $this->price['actual'],
                'offer' => $this->price['offer']
            ],
            'photo' => $this->photo,
            'added_date' => date("Y:m:d H:i:s"),
            'flag' => "verified"
        ];
        $postRef = $database->getReference("books")->push($postData);
        $immediateKey = $postRef->getKey();
        return $immediateKey;
    }


    // fetch user details from the database
    function fetch($id)
    {
        global $database;
        $response = $database->getReference("books")->getChild($id)->getSnapshot()->getValue();
        if ($response) {
            $this->setBook($id, $response['owner_id'], $response['title'], $response['description'], $response['language'], $response['genre'], $response['author'], $response['isbn'], $response['purpose'], $response['publisher'], $response['edition'], $response['price'], $response['photo'], $response['added_date'], $response['flag']);
            // $this->setPhotoUrl();
            return true;
        } else {
            return false;
        }
    }

    // set cover photo url
    public function setPhotoUrl()
    {
        global $bucket;
        $prefix = 'books/';

        $objectName = $prefix . $this->photo;
        $object = $bucket->object($objectName);
        if ($object->exists())
            $this->photoUrl = $object->signedUrl(new DateTime('tomorrow'));
    }

    // fetch all books
    public function fetchAllBooks()
    {
        global $database;
        $list = array();
        $response = $database->getReference("books")->getSnapshot()->getValue();

        if ($response != null) {
            foreach ($response as $key => $res) {
                $temp = new Book();
                $temp->fetch($key);
                $list[] = $temp;
            }
        }
        return $list;
    }

    // fetch all books
    public function fetchAllBookId()
    {
        global $database;
        $idList = [];
        $response = $database->getReference("books")->getSnapshot()->getValue();
        if ($response != null) {
            foreach ($response as $key => $res) {
                $idList[] = $key;
            }
        }
        return $idList;
    }

    public function fetchUserBookId($userId)
    {
        global $database;
        $list = [];
        $query = $database->getReference('books')->orderByChild('owner_id')->equalTo($userId);
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();

        if ($response)
            foreach ($response as $key => $res)
                $list[] = $key;

        return $list;
    }

    // fetch user's books
    public function fetchBookByUserId($userId)
    {
        global $database;
        $list = [];

        $response = $database->getReference('books')->orderByChild('owner_id')->equalTo($userId)->getSnapshot()->getValue();

        foreach ($response as $key => $res)
            $list[] = $key;

        return $list;
    }

    // search book
    public function searchBook($searchContent)
    {
        global $database;
        $bookIdList = [];
        $response = $database->getReference("books")->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if (strpos($res['title'], $searchContent) !== false) {
                    $bookIdList[] = $key;
                }
            }
        }

        return $bookIdList;
    }
}