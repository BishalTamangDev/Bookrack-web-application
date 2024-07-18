<?php

require_once __DIR__ . '/connection.php';
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
        $response = $database->getReference("genre")->getSnapshot()->getValue();

        if ($response != null) {
            $existingGenreList = [];
            foreach ($response as $res)
                $existingGenreList[] = $res['title'];

            foreach ($this->genreArray as $genre) {
                if (in_array($genre, $existingGenreList)) {
                    $query = $database->getReference("genre")->orderByChild('title')->equalTo($genre);
                    $snapshot = $query->getSnapshot();
                    $response = $snapshot->getValue();

                    // fetch genre document key
                    foreach ($response as $key => $res) {
                        // update book count
                        $properties = [
                            'book_count' => ++$res['book_count']
                        ];
                        $response = $database->getReference("genre/{$key}")->update($properties);
                    }
                } else {
                    $postData = [
                        'title' => $genre,
                        'book_count' => 1,
                        'click_count' => 0,
                        'search_count' => 0,
                    ];
                    $postRef = $database->getReference("genre")->push($postData);
                }
            }
        } else {
            foreach ($this->genreArray as $genre) {
                $postData = [
                    'title' => $genre,
                    'book_count' => 1,
                    'click_count' => 0,
                    'search_count' => 0,
                ];
                $postRef = $database->getReference("genre")->push($postData);
            }
        }
    }

    function removeBook()
    {
        global $database;
        $response = $database->getReference("genre")->getSnapshot()->getValue();

        $existingGenreList = [];
        foreach ($response as $res)
            $existingGenreList[] = $res['title'];

        foreach ($this->genreArray as $genre) {
            if (in_array($genre, $existingGenreList)) {
                $response = $database->getReference("genre")->orderByChild("title")->equalTo($genre)->getSnapshot()->getValue();
                foreach ($response as $key => $res) {
                    $properties = [
                        'book_count' => --$res['book_count']
                    ];
                    $response = $database->getReference("genre/{$key}")->update($properties);
                }
            }
        }
    }

    // fetch genre list
    function fetchGenreList()
    {
        global $database;
        $genreList = [];
        $response = $database->getReference("genre")->getSnapshot()->getValue();

        $keyToSortBy = 'book_count';

        if ($response != null) {
            // Sort data in descending order
            usort($response, function ($a, $b) use ($keyToSortBy) {
                return $b[$keyToSortBy] <=> $a[$keyToSortBy];
            });

            $topGenreLimit = 3;
            $count = 0;
            foreach ($response as $key => $res) {
                if ($count < $topGenreLimit) {
                    if ($res['book_count'] != 0) {
                        $genreList[] = $res['title'];
                        $count++;
                    }
                }
            }
        }

        return $genreList;
    }
}