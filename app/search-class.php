<?php

require_once __DIR__ . '/connection.php';

class Search
{
    private $userId;
    private $title;
    private $list = [];
    private $content = [
        'title' => '',
        'date' => '',
        'count' => ''
    ];

    // constructor
    public function __construct()
    {
        $userId = '';
        $title = '';
        $list = [];
        $content = [
            'title' => $title,
            'date' => '',
            'count' => ''
        ];
    }

    // getters
    public function getTitle()
    {
        return $this->content['title'];
    }

    public function getDate()
    {
        return $this->content['date'];
    }

    public function getCount()
    {
        return $this->content['count'];
    }

    // setter
    public function setTitle($title)
    {
        $this->content['title'] = $title;
    }

    public function setContentDate($date)
    {
        $this->content['date'] = $date;
    }

    public function setContentCount($count)
    {
        $this->content['count'] = $count;
    }

    // register
    public function register()
    {
        global $database;

        $this->userId = $_SESSION['bookrack-user-id'];

        $postData = [];

        $postRef = $database->getReference("searches/{$this->userId}/")->set($postData);
    }

    // add new search
    public function update($title)
    {
        global $database;
        $titleExists = false;
        $this->userId = $_SESSION['bookrack-user-id'];

        // check history for same content / title
        $response = $database->getReference("searches/{$this->userId}")->getSnapshot()->getValue();

        if ($response) {
            // fetch each search contents
            foreach ($response as $key => $res) {
                $content = [
                    'title' => $key,
                    'date' => $res['date'],
                    'count' => $res['count']
                ];
                $this->list[] = $content;
            }

            foreach ($this->list as $list) {
                if ($title == $list['title']) {
                    $titleExists = true;
                    // just update count and date
                    $postData = [
                        $title => [
                            'count' => ++$list['count'],
                            'date' => date("Y:m:d")
                        ],
                    ];
                    $postRef = $database->getReference("searches/{$this->userId}/")->update($postData);
                    break;
                }
            }

            if (!$titleExists) {
                // add new search
                $postData = [
                    $title => [
                        'count' => 1,
                        'date' => date("Y:m:d")
                    ]
                ];
                $postRef = $database->getReference("searches/{$this->userId}")->update($postData);
            }

        } else {
            $postData = [
                $title => [
                    'count' => 1,
                    'date' => date("Y:m:d")
                ],
            ];
            $postRef = $database->getReference("searches/{$this->userId}/")->set($postData);
        }
    }
}