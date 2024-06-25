<?php

require_once __DIR__ . '/../../bookrack/app/connection.php';

class Book
{
    private $bookId;
    private $title;
    private $description;
    private $language;
    private $genre;
    private $author;
    private $isbn;
    private $purpose;
    private $publisher;
    private $publication;
    private $edition;
    private $price = [
        "actual" => "",
        "offer" => "",
    ];

    private $photo = [
        "cover" => "",
        "price" => "",
        "isbn" => "",
    ];

    private $date = [
        "offer" => "",
        "approval" => "",
    ];

    private $ownerId;
    private $status;

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

        $this->date = [
            "offer" => "",
            "approval" => "",
        ];

        $this->status = "verified";
        $this->ownerId = "";
    }

    public function setBook($bookId, $ownerId, $title, $description, $language, $genre, $author, $isbn, $purpose, $publisher, $publication, $edition, $actualPrice, $offerPrice, $coverPhoto, $pricePhoto, $isbnPhoto, $offerDate, $approvalDate, $status)
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
            "actual" => $actualPrice,
            "offer" => $offerPrice
        ];

        $this->photo = [
            "cover" => $coverPhoto,
            "price" => $pricePhoto,
            "isbn" => $isbnPhoto
        ];

        $this->date = [
            "offer" => $offerDate,
            "approval" => $approvalDate
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
        $this->genre = $language;
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


    // ($bookId, $title, $description,$language, $genre, $author, $isbn, $purpose, $publisher, $publication, $edition, $actualPrice, $offerPrice, $coverPhoto, $pricePhoto, $isbnPhoto, $offerDate, $approvalDate, $status) {

    // book registration
    public function register()
    {
        global $database;

        $postData = [
            'owner_id' => $this->getOwnerId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'language' => $this->getLanguage(),
            'genre' => $this->getGenre(),
            'author' => $this->getAuthor(),
            'isbn' => $this->getIsbn(),
            'purpose' => $this->getPurpose(),
            'publisher' => $this->getPublisher(),
            'publication' => $this->getPublication(),
            'edition' => $this->getEdition(),
            'price' => [
                'actual' => $this->getActualPrice(),
                'offer' => $this->getOfferPrice()
            ],
            'photo' => [
                'cover' => $this->getCoverPhoto(),
                'price' => $this->getPricePhoto(),
                'isbn' => $this->getIsbnPhoto()
            ],
            'date' => [
                'offer' => date("Y:m:d H:i:s"),
                'approval' => $this->getApprovalDate(),
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
            $this->setBook($id, $response['owner_id'], $response['title'], $response['description'], $response['language'], $response['genre'], $response['author'], $response['isbn'], $response['purpose'], $response['publisher'], $response['publication'], $response['edition'], $response['price']['actual'], $response['price']['offer'], $response['photo']['cover'], $response['photo']['price'], $response['photo']['isbn'], $response['date']['offer'], $response['date']['approval'], $response['status']);
            return true;
        } else {
            return false;
        }
    }

    // get cover photo url
    public function getCoverPhotoUrl()
    {
        global $bucket;

        $coverPhotoUrl = "/bookrack/assets/images/blank-user.jpg";

        $prefix = 'books/';
        $options = [
            'prefix' => $prefix,
            'delimiter' => '/'
        ];

        $objects = $bucket->objects($options);

        foreach ($objects as $object) {
            // Check if the object's name (filename) matches the filename we are looking for
            if ($object->name() === $prefix . $this->getCoverPhoto()) {
                // Generate a signed URL valid until tomorrow for the matched object
                $coverPhotoUrl = $object->signedUrl(new DateTime('tomorrow'));
                break; // Exit the loop once we find the matching filename
            }
        }

        return $coverPhotoUrl;
    }


    // get price photo url
    public function getPricePhotoUrl()
    {
        global $bucket;

        $pricePhotoUrl = "/bookrack/assets/images/blank-user.jpg";

        $prefix = 'books/';
        $options = [
            'prefix' => $prefix,
            'delimiter' => '/'
        ];

        $objects = $bucket->objects($options);

        foreach ($objects as $object) {
            if ($object->name() === $prefix . $this->getPricePhoto()) {
                $pricePhotoUrl = $object->signedUrl(new DateTime('tomorrow'));
                break; 
            }
        }

        return $pricePhotoUrl;
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
        $query = $database->getReference("books");
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();
        return $response;
    }

    public function fetchUserBookId($userId){
        $list = array();
        global $database;
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
        // $query = $database->getReference("books");
        $query = $database->getReference('books')->orderByChild('owner_id')->equalTo($userId);
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();        
        return $response;
    }
}