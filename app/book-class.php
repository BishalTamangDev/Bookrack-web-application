<?php

require_once __DIR__ . '/../../bookrack/app/connection.php';

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
    public $publication;
    public $edition;
    public $price = [
        "actual" => "",
        "offer" => "",
    ];

    public $photo = [
        "cover" => "",
        "price" => "",
        "isbn" => "",
    ];

    public $photoUrl = [
        "cover" => "",
        "price" => "",
        "isbn" => "",
    ];

    public $date = [
        "offer" => "",
        "approval" => "",
    ];

    private $ownerId;
    public $status;

    public function __construct()
    {
        $this->bookId = "";
        $this->title = "";
        $this->description = "";
        $this->language = "";
        $this->genre = array();
        $this->author = array();
        $this->isbn = "";
        $this->purpose = "";
        $this->publisher = "";
        $this->publication = "";
        $this->edition = "";
        $this->price = [
            "actual" => "",
            "offer" => "",
        ];

        $this->photo = [
            "cover" => "",
            "price" => "",
            "isbn" => "",
        ];

        $photoUrl = [
            "cover" => "empty",
            "price" => "empty",
            "isbn" => "empty",
        ];

        $this->date = [
            "offer" => "",
            "approval" => "",
        ];

        $this->status = "verified";
        $this->ownerId = "";
    }

    public function setBook($bookId, $ownerId, $title, $description, $language, $genre, $author, $isbn, $purpose, $publisher, $publication, $edition, $price, $photo, $date, $status)
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
        $this->publication = $publication;
        $this->edition = $edition;
        $this->price = [
            "actual" => $price['actual'],
            "offer" => $price['offer']
        ];

        $this->photo = [
            "cover" => $photo['cover'],
            "price" => $photo['price'],
            "isbn" => $photo['isbn']
        ];

        $this->date = [
            "offer" => $date['offer'],
            "approval" => $date['approval']
        ];

        $this->status = $status;
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
    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function getPurpose()
    {
        return $this->purpose;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function getPublication()
    {
        return $this->publication;
    }

    public function getEdition()
    {
        return $this->edition;
    }

    public function getActualPrice()
    {
        return $this->price['actual'];
    }

    public function getOfferPrice()
    {
        return $this->price['offer'];

    }

    public function getCoverPhoto()
    {
        return $this->photo["cover"];
    }

    public function getPricePhoto()
    {
        return $this->photo["price"];
    }

    public function getIsbnPhoto()
    {
        return $this->photo["isbn"];
    }

    public function getOfferDate()
    {
        return $this->date["offer"];
    }

    public function getApprovalDate()
    {
        return $this->date["approval"];
    }

    public function getStatus()
    {
        return $this->status;
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

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
    }public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    public function setEdition($edition)
    {
        $this->edition = $edition;
    }

    public function setActualPrice($actualPrice)
    {
        $this->price["actual"] = $actualPrice;
    }

    public function setOfferPrice($offerPrice)
    {
        $this->price["offer"] = $offerPrice;

    }

    public function setCoverPhoto($coverPhoto)
    {
        $this->photo["cover"] = $coverPhoto;
    }

    public function setPricePhoto($pricePhoto)
    {
        $this->photo["price"] = $pricePhoto;
    }

    public function setIsbnPhoto($isbnPhoto)
    {
        $this->photo["isbn"] = $isbnPhoto;
    }

    public function setOfferDate($offerDate)
    {
        $this->date["offer"] = $offerDate;
    }

    public function setApprovalDate($approvalDate)
    {
        $this->date["approval"] = $approvalDate;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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
            'publication' => $this->publication,
            'edition' => $this->edition,
            'price' => [
                'actual' => $this->price['actual'],
                'offer' => $this->price['offer']
            ],
            'photo' => [
                'cover' => $this->photo['cover'],
                'price' => $this->photo['price'],
                'isbn' => $this->photo['isbn']
            ],
            'date' => [
                'offer' => date("Y:m:d H:i:s"),
                'approval' => $this->date['approval'],
            ],

            'status' => "unverified"
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
            $this->setBook($id, $response['owner_id'], $response['title'], $response['description'], $response['language'], $response['genre'], $response['author'], $response['isbn'], $response['purpose'], $response['publisher'], $response['publication'], $response['edition'], $response['price'], $response['photo'], $response['date'], $response['status']);
            $this->setPhotoUrls();
            return true;
        } else {
            return false;
        }
    }

    public function setPhotoUrls(){
        global $bucket;

        $prefix = 'books/';

        $objectName = $prefix . $this->photo['cover'];
        $object = $bucket->object($objectName);
        if($object->exists())
            $this->photoUrl["cover"] = $object->signedUrl(new DateTime('tomorrow'));
    
        $objectName = $prefix . $this->photo['price'];
        $object = $bucket->object($objectName);
        if($object->exists())
            $this->photoUrl["price"] = $object->signedUrl(new DateTime('tomorrow'));
        
        $objectName = $prefix . $this->photo['isbn'];
        $object = $bucket->object($objectName);
        if($object->exists())
            $this->photoUrl["isbn"] = $object->signedUrl(new DateTime('tomorrow'));
    }

    // get isbn photo url
    public function getIsbnPhotoUrl()
    {
        global $bucket;

        $isbnPhotoUrl = "/bookrack/assets/images/blank-user.jpg";

        $prefix = 'books/';
        $options = [
            'prefix' => $prefix,
            'delimiter' => '/'
        ];

        $objects = $bucket->objects($options);

        foreach ($objects as $object) {
            if ($object->name() === $prefix . $this->getIsbnPhoto()) {
                $isbnPhotoUrl = $object->signedUrl(new DateTime('tomorrow'));
            }
        }

        return $isbnPhotoUrl;
    }


    // fetch all books
    public function fetchAllBooks()
    {
        global $database;
        $list = array();
        $response = $database->getReference("books")->getSnapshot()->getValue();

        if($response != null){
            foreach($response as $key =>$res){
                $temp = new Book();
                $temp->fetch($key);
                $list [] = $temp;
            }
        }
        return $list;
    }

    public function fetchUserBookId($userId){
        global $database;
        $list = array();
        $query = $database->getReference('books')->orderByChild('owner_id')->equalTo($userId);
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();

        if($response)
            foreach($response as $key => $res)
                $list [] = $key;

        return $list;
    }

    // fetch user's books
    public function fetchBookByUserId($userId){
        global $database;
        $list = array();

        $query = $database->getReference('books')->orderByChild('owner_id')->equalTo($userId);
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();        

        
        foreach($response as $key =>$res){
            $temp = new Book();
            $temp->fetch($key);
            $list [] = $temp;
        }

        return $list;
    }
}