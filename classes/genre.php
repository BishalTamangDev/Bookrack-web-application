<?php

require_once __DIR__ . '/../app/connection.php';
class Genre
{
    private $id;
    public $title;

    public $genreArray;
    public $bookCount;
    public $clickCount;
    public $searchCount;

    // constructor
    public function __construct()
    {
        $this->id = "";
        $this->title = "";
        $this->genreArray = [];
        $this->bookCount = 0;
        $this->clickCount = 0;
        $this->searchCount = 0;
    }

    // setter
    function setId($id)
    {
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    // new book addition
    function newBook()
    {
        global $database;

        foreach ($this->genreArray as $genre) {
            $response = $database->getReference("genres/{$genre}")->getSnapshot()->getValue();

            if ($response) {
                // update genre count
                $postData = [
                    $genre => [
                        'book_count' => ++$response['book_count'],
                        'click_count' => 0,
                        'search_count' => 0,
                    ],
                ];
                $postRef = $database->getReference("genres/")->update($postData);
            } else {
                // new genre
                $postData = [
                    $genre => [
                        'book_count' => 1,
                        'click_count' => 0,
                        'search_count' => 0,
                    ],
                ];
                $postRef = $database->getReference("genres/")->update($postData);
            }
        }
    }

    function removeBook()
    {
        global $database;

        foreach ($this->genreArray as $genre) {
            $response = $database->getReference("genres/{$genre}")->getSnapshot()->getValue();

            if ($response) {
                // update genre count
                $newCount = 0;
                if ($response['book_count'] > 0) {
                    $newCount = --$response['book_count'];
                } else {
                    $newCount = 0;
                }
                $postData = [
                    $genre => [
                        'book_count' => $newCount,
                        'click_count' => $response['click_count'],
                        'search_count' => $response['search_count'],
                    ],
                ];
                $postRef = $database->getReference("genres/")->update($postData);
            }
        }
    }

    // fetch genre list
    function fetchGenreList()
    {
        global $database;
        $genreList = [];
        $response = $database->getReference("genres")->orderByChild('book_count')->getValue();

        $topGenreLimit = 3;

        if ($response != null) {
            $response = array_reverse($response);
            $count = 0;
            foreach ($response as $key => $res) {
                if ($count == $topGenreLimit)
                    break;
                $genreList[] = $key;
                $count++;
            }
        }

        return $genreList;
    }
}