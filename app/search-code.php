<?php
require_once __DIR__ . '/../classes/search.php';
require_once __DIR__ . '/../functions/check-search-to-store.php';

if (isset($_GET['search-content'])) {
    $search = new Search();
    $title = $_GET['search-content'];

    if (searchEligibleToStore($title))
        $search->update($title);

    header("Location: /bookrack/home?search-content=$title");
}

exit;