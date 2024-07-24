<?php

require_once __DIR__ . '/../app/connection.php';

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

    public function getList()
    {
        return $this->list;
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

    // fetch searches for admin
    public function fetchAllSearches()
    {
        global $database;
        $reference = $database->getReference("searches");

        $searchList = [];
        $search = [
            'title' => '',
            'count' => 0
        ];

        if ($reference) {
            $response = $reference->getSnapshot()->getValue();

            // fetching all searched
            if ($response) {
                foreach ($response as $key => $res) {
                    foreach ($res as $innerKey => $r) {
                        $search = [
                            'title' => $innerKey,
                            'count' => $r['count']
                        ];
                        $searchList[] = $search;
                    }
                }

                // grouping search
                $finalList = [];

                foreach ($searchList as $list) {
                    // print_r($list);
                    if (sizeof($finalList) > 0) {
                        $searchExists = false;
                        $index = 0;
                        foreach ($finalList as $final) {
                            if ($list['title'] == $final['title']) {
                                $searchExists = true;
                                $search = [
                                    'title' => $list['title'],
                                    'count' => $finalList[$index]['count'] + $list['count']
                                ];
                                $finalList[$index] = $search;
                            }
                            $index++;
                        }

                        if (!$searchExists) {
                            $search = [
                                'title' => $list['title'],
                                'count' => $list['count']
                            ];
                            $finalList[] = $search;
                        }
                    } else {
                        $search = [
                            'title' => $list['title'],
                            'count' => $list['count']
                        ];
                        $finalList[] = $search;
                    }
                }
                // sorting search based on search
                usort($finalList, function ($a, $b) {
                    return $b['count'] <=> $a['count'];
                });

                $this->list = $finalList;
            }
        }
    }
}