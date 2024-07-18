<?php
require_once __DIR__ . '/search-class.php';

if (isset($_GET['search-content'])) {
    $search = new Search();
    $title = $_GET['search-content'];
    $search->update($title);

    header("Location: /bookrack/home?search-content=$title");
}

exit();