<?php
require_once __DIR__ . '/../app/connection.php';

class Click
{
    private $id;
    private $genreList = [];
    private $newGenreList = [];
    private $genre = [
        'title',
        'click_count',
        'date'
    ];
    // getter function
    public function getId()
    {
        return $this->id;
    }

    public function getGenreList()
    {
        return $this->genreList;
    }

    public function getNewGenreList()
    {
        return $this->newGenreList;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    // setter function
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setGenreList($genreList)
    {
        $this->genreList = $genreList;
    }

    public function setNewGenreList($newGenreList)
    {
        $this->newGenreList = $newGenreList;
    }


    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    // add data
    public function add()
    {
        global $database;
        $response = $database->getReference("clicks/{$this->id}/genre")->getSnapshot()->getValue();

        if ($response) {
            foreach ($this->newGenreList as $newGenre) {
                $response = $database->getReference("clicks/{$this->id}/genre")->getSnapshot()->getValue();
                $postData = [];
                $genreExists = false;

                foreach ($response as $key => $res) {
                    if ($key == $newGenre) {
                        // genre already exists
                        $genreExists = true;
                        $oldData = [
                            'click_count' => ++$res['click_count'],
                            'date' => $res['date']
                        ];
                        $postData['genre'][$newGenre] = $oldData;
                    } else {
                        $oldData = [
                            'click_count' => $res['click_count'],
                            'date' => $res['date']
                        ];
                        $postData['genre'][$key] = $oldData;
                    }
                }

                if (!$genreExists) {
                    // new genre
                    $newData = [
                        'click_count' => 1,
                        'date' => date('Y:m:d')
                    ];
                    $postData['genre'][$newGenre] = $newData;
                }
                $postRef = $database->getReference("clicks/{$this->id}")->update($postData);
            }
        } else {
            $count = 1;

            foreach ($this->newGenreList as $newGenre) {
                // fetch existing data
                if ($count == 1) {
                    $count++;
                    $postData = [
                        'genre' => [
                            $newGenre => [
                                'click_count' => 1,
                                'date' => date("Y:m:d")
                            ]
                        ],
                    ];
                    $postRef = $database->getReference("clicks/{$this->id}/")->set($postData);
                } else {
                    $response = $database->getReference("clicks/{$this->id}/genre")->getSnapshot()->getValue();

                    $postData = [];
                    foreach ($response as $key => $res) {
                        $oldData = [
                            'click_count' => $res['click_count'],
                            'date' => $res['date']
                        ];
                        $postData['genre'][$key] = $oldData;
                    }


                    // // new data
                    $newData = [
                        'click_count' => 1,
                        'date' => date('Y:m:d')
                    ];

                    $postData['genre'][$newGenre] = $newData;

                    $postRef = $database->getReference("clicks/{$this->id}/")->set($postData);
                }
            }
        }
    }
}