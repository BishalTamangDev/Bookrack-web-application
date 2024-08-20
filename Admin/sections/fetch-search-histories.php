<?php
require_once __DIR__ . '/../../classes/search.php';
$search = new Search();

$search->fetchAllSearches();

if (sizeof($search->getList()) > 0) {
    $searchList = $search->getList();
    foreach ($searchList as $list) {
        ?>
        <div class="d-flex flex-row border rounded p-2 px-3 align-items-center">
            <p class="m-0"> <?php echo $list['title'] . " : " . $list['count']; ?>
            </p>
        </div>
        <?php
    }
    ?>

    <?php
} else {
    ?>
    <p class="m-0 text-secondary"> No search data found! </p>
    <?php
}
?>