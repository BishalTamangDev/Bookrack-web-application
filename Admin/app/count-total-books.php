<?php

require_once __DIR__ . '/../../classes/book.php';

$tempBook = new Book();

$count = $tempBook->countTotalBooks();

echo $count;