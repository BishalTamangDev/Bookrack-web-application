<?php
require_once __DIR__ . '/../functions/genre-array.php';
require_once __DIR__ . '/../classes/genre.php';

$genreObj = new Genre();
$genreList = [];
$genreList = $genreObj->fetchGenreList();
?>
<!-- fetch all the genres -->
<?php
if (sizeof($genreList) > 0) {
    foreach ($genreList as $genre) {
        ?>
        <div class="genre">
            <p class="m-0 text-secondary"> <?= $genre ?> </p>
        </div>
        <?php
    }
} else {
    ?>
    <div class="genre">
        <p class="m-0 text-secondary"> No trending genre yet! </p>
    </div>
    <?php
}
?>