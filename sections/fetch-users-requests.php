<?php
$userId = $_POST['userId'] ?? 0;

if ($userId == 0) {
    ?>
    <tr>
        <td colspan="7" style="text-align:center;" class="text-danger"> No request found! </td>
    </tr>
    <?php
    exit;
}

require_once __DIR__ . '/../classes/book.php';
require_once __DIR__ . '/../classes/request.php';

$tempBook = new Book();
$tempRequest = new Request();

$bookIdList = $tempBook->fetchBookByUserId($userId);

$requestCount = 0;

$serial = 1;

$requestIdList = $tempRequest->fetchRequestForUser($bookIdList);

if (sizeof($requestIdList) == 0) {
    ?>
    <tr>
        <td colspan="7" style="text-align:center;" class="text-danger"> No request found! </td>
    </tr>
    <?php
} else {
    foreach ($requestIdList as $requestId) {
        $requestExists = $tempRequest->fetch($requestId);

        $bookId = $tempRequest->getBookId();

        $tempBook->fetch($bookId);

        $title = ucwords($tempBook->title);
        $price = $tempRequest->price;
        ?>

        <tr>
            <th scope="row"> <?= $serial++ ?></th>
            <td> <?= $title ?> </td>
            <td class="text-success fw-semibold"> <?= "NPR. " . number_format($price, 2) ?> </td>
            <td> <?= $tempRequest->date['requested'] ?> </td>
            <td> <?= $tempRequest->date['submitted'] != '' ? $tempRequest->date['submitted'] : '-' ?> </td>
            <td> <?= ucfirst($tempRequest->flag) ?> </td>
        </tr>

        <?php
    }
}

exit;