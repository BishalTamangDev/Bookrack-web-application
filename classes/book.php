<?php

if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../app/connection.php';

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
    public $priceActual = "";
    public $priceOffer = "";

    public $photo;
    public $photoUrl;

    public $addedDate;
    private $ownerId;
    public $flag;

    // extra variable for storing fetched data for faster loading time 
    private $responseAllBooks;

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

        $this->priceActual = "";
        $this->priceOffer = "";

        $this->photo = "";
        $this->photoUrl = "empty";
        $this->addedDate = "";
        $this->ownerId = "";
        $this->flag = "verified";

        // extra variable
        $this->responseAllBooks = "";
    }

    public function setBook($bookId, $ownerId, $title, $description, $language, $genre, $author, $isbn, $purpose, $publisher, $edition, $priceActual, $priceOffer, $photo, $addedDate, $flag)
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

        $this->priceActual = $priceActual;
        $this->priceOffer = $priceOffer;

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
        return $this->priceActual;
    }

    public function getOfferPrice()
    {
        return $this->priceOffer;

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
        $this->priceActual = $actualPrice;
    }

    public function setOfferPrice($offerPrice)
    {
        $this->priceOffer = $offerPrice;

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

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

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
            'price_actual' => $this->priceActual,
            'price_offer' => $this->priceOffer,
            'photo' => $this->photo,
            'added_date' => $currentDate,
            'flag' => "verified"
        ];
        $postRef = $database->getReference("books")->push($postData);
        $immediateKey = $postRef->getKey();
        return $immediateKey;
    }

    // count total books
    function countTotalBooks()
    {
        global $database;
        $count = 0;

        $count = $database->getReference("books")->getSnapshot()->numChildren();

        return $count;
    }

    // count on hold books
    function countOnHoldBooks()
    {
        global $database;
        $count = 0;

        $response = $database->getReference("books")->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $res) {
                if ($res['flag'] == 'on-hold')
                    $count++;
            }
        }

        return $count;
    }

    // count on hold books
    function countSoldOutBooks()
    {
        global $database;
        $count = 0;

        $response = $database->getReference("books")->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $res) {
                if ($res['flag'] == 'sold-out')
                    $count++;
            }
        }

        return $count;
    }

    // fetch user details from the database
    function fetch($id)
    {
        global $database;
        $response = $database->getReference("books")->getChild($id)->getSnapshot()->getValue();
        if ($response) {
            $this->setBook($id, $response['owner_id'], $response['title'], $response['description'], $response['language'], $response['genre'], $response['author'], $response['isbn'], $response['purpose'], $response['publisher'], $response['edition'], $response['price_actual'], $response['price_offer'], $response['photo'], $response['added_date'], $response['flag']);
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
        $list = [];
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
        // $response = $database->getReference("books")->getSnapshot()->getValue();
        $response = $database->getReference("books")->orderByChild('added_date')->getSnapshot()->getValue();
        if ($response != null) {
            foreach ($response as $key => $res) {
                $idList[] = $key;
            }
        }
        return $idList;
    }

    // fetch latest 3 books
    public function fetchLatestBookId()
    {
        global $database;
        $list = [];
        $response = $database->getReference("books")->orderByChild('added_date')->limitToLast(3)->getValue();
        if ($response != null) {
            foreach ($response as $key => $res) {
                $list[] = $key;
            }
        }
        return array_reverse($list);
    }

    // fetch all books for home
    public function fetchAllBookIdForHome()
    {
        global $database;
        $idList = [];
        $response = $database->getReference("books")->orderByChild('added_date')->getSnapshot()->getValue();

        if ($response != null) {
            foreach ($response as $key => $res) {
                if ($res['flag'] == "verified") {
                    $idList[] = $key;
                }
            }
        }
        $_SESSION['book-id-list'] = array_reverse($idList);
        return $_SESSION['book-id-list'];
    }

    public function fetchAvailableBookIdList()
    {
        global $database;
        $idList = [];
        $response = $database->getReference("books")->getSnapshot()->getValue();
        if ($response != null) {
            foreach ($response as $key => $res) {
                if ($res['flag'] == "verified")
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

    // search book id
    public function searchBook($searchContent)
    {
        global $database;
        $bookIdList = [];
        $searchContent = strtolower($searchContent);
        $response = $database->getReference("books")->orderByChild('added_date')->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if (strpos($res['title'], $searchContent) !== false || strpos($res['isbn'], $searchContent) !== false) {
                    $bookIdList[] = $key;
                }
            }
        }
        return $bookIdList;
    }

    // search book for home
    // search content || min price || max-price || genre
    public function searchBookForHome($searchContent)
    {
        $searchContent = strtolower($searchContent);
        global $database;
        $bookIdList = [];
        $response = $database->getReference("books")->orderByChild('added_date')->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if (strpos($res['title'], $searchContent) !== false) {
                    if ($res['flag'] == "verified")
                        $bookIdList[] = $key;
                }
            }
        }

        $_SESSION['book-id-list'] = array_reverse($bookIdList);
        return $_SESSION['book-id-list'];
    }

    public function checkIfUserHasContributed($userId)
    {
        global $database;
        $hasContributed = false;
        if ($this->responseAllBooks == "") {
            $this->responseAllBooks = $database->getReference("books")->getSnapshot()->getValue();
        }

        foreach ($this->responseAllBooks as $res) {
            if ($res['owner_id'] == $userId)
                $hasContributed = true;
        }

        return $hasContributed;
    }

    // update book flag
    public function updateFlag($bookId, $flag)
    {
        global $database;
        $postData = [
            'flag' => $flag
        ];
        $response = $database->getReference("books/{$bookId}")->update($postData);
        return $response ? true : false;
    }

    // sell book
    public function sell($bookId) {
        global $database;

        $postData = [
            'flag' => 'sold-out'
        ];

        $response = $database->getReference("books/{$bookId}")->update($postData);
        return $response ? true : false;
    }
}