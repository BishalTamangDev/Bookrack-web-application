<?php
$url = "books";
$page = "books";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Books </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>

    <!-- main content -->
    <main class="main">
        <!-- cards -->
        <section class="section mt-5 pt-3 card-container">
            <!-- total books -->
            <div class="card-v1">
                <p class="card-v1-title"> Number of Books </p>
                <p class="card-v1-detail" id="total-book-count"> - </p>
            </div>

            <!-- on-hold -->
            <div class="card-v1">
                <p class="card-v1-title"> On-hold </p>
                <p class="card-v1-detail" id="on-hold-book-count"> - </p>
            </div>

            <!-- sold-out -->
            <div class="card-v1">
                <p class="card-v1-title"> Sold out </p>
                <p class="card-v1-detail" id="sold-out-book-count"> - </p>
            </div>
        </section>

        <!-- table to section -->
        <div class="section table-top-section">
            <!-- filter -->
            <div class="filter-div flex-wrap">
                <i class="fa fa-filter" id="filter-icon"></i>

                <!-- rent / stock -->
                <select class="form-select" aria-label="select" id="flag-select">
                    <option value="all" selected> Book status: all </option>
                    <option value="available"> Book status: available </option>
                    <option value="on-stock"> Book status: on stock </option>
                    <option value="sold-out"> Book status: sold out </option>
                </select>

                <!-- genre -->
                <select class="d-none form-select" aria-label="select" id="genre-select">
                    <option value="all" selected> All genre </option>
                    <?php
                    foreach ($genreArray as $genre) {
                        ?>
                        <option value="<?= $genre ?>"> <?= $genre ?> </option>
                        <?php
                    }
                    ?>
                </select>

                <!-- language -->
                <select class="form-select" aria-label="select" id="language-select">
                    <option value="all" selected> Language: all </option>
                    <option value="chinese"> Language: chinese </option>
                    <option value="english"> Language: english </option>
                    <option value="hindi"> Language: hindi </option>
                    <option value="nepali"> Language: nepali </option>
                </select>

                <!-- clear filter -->
                <div class="clear-filter-div" id="clear-sort">
                    <p class="f-reset"> Clear </p>
                    <i class="fa fa-multiply"></i>
                </div>
            </div>
        </div>

        <!-- book table -->
        <div class="table-container">
            <table class="table table-striped book-table">
                <!-- header -->
                <thead>
                    <tr>
                        <th scope="col"> SN </th>
                        <th scope="col"> Title </th>
                        <th scope="col"> ISBN </th>
                        <th scope="col"> Genre </th>
                        <th scope="col"> Author[s] </th>
                        <th scope="col"> Language </th>
                        <th scope="col"> Book State </th>
                        <th scope="col"> Action </th>
                    </tr>
                </thead>

                <!-- body -->
                <tbody id="book-table-body">
                    <tr>
                        <td colspan="9">
                            <div class="d-flex flex-row gap-2 table-loading-gif-container">
                                <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="" style="width: 20px;">
                                <p class="m-0 text-secondary"> Fetching books </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button class="invisible btn btn-danger mt-2" id="clear-search-btn"> Clear search </button>
        </div>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <script>
        $(document).ready(function () {
            // fetch books
            function fetchBooks() {
                $.ajax({
                    url: "/bookrack/admin/sections/book-table.php",
                    beforeSend: function () {
                        $('#book-table-body').html("<tr> <td colspan = '8'> <div class='d-flex flex-row gap-2 table-loading-gif-container'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width: 20px;'> <p class='m-0 text-secondary'> Fetching all books... </p> </div> </td> </tr>");
                    },
                    success: function (data) {
                        $('#book-table-body').html(data);
                    }
                });
            }

            // count total books
            function countBooks() {
                // total books
                $.ajax({
                    url: "/bookrack/admin/app/count-total-books.php",
                    success: function (data) {
                        $('#total-book-count').html(data);
                    }
                });

                // on-hold
                $.ajax({
                    url: "/bookrack/admin/app/count-on-hold-books.php",
                    success: function (data) {
                        $('#on-hold-book-count').html(data);
                    }
                });

                // sold out
                $.ajax({
                    url: "/bookrack/admin/app/count-sold-out-books.php",
                    success: function (data) {
                        $('#sold-out-book-count').html(data);
                    }
                });
            }

            countBooks();

            fetchBooks();

            // filtering
            var sort = false;

            const clearSort = $('#clear-sort');

            const flagSelect = $('#flag-select');
            const flagAvailable = $('.available-tr');
            const flagOnStock = $('.on-stock-tr');
            const flagSoldOut = $('.sold-out-tr');

            const languageSelect = $('#language-select');
            const languageChinese = $('.chinese-tr');
            const languageEnglish = $('.english-tr');
            const languageHindi = $('.hindi-tr');
            const languageNepali = $('.nepali-tr');

            var flag = "all";
            var language = "all";

            // flag select
            flagSelect.on('change', function () {
                flag = flagSelect.val();
                filterBook();
            });

            // language select
            languageSelect.on('change', function () {
                language = languageSelect.val();
                filterBook();
            });

            // clear sort
            clearSort.on('click', function () {
                sort = false;
                flag = "all";
                language = "all";
                flagSelect.val("all");
                languageSelect.val('all');
                filterBook();
            });


            // show empty row
            toggleEmptyRow = () => {
                if ($('.book-tr').is(':visible'))
                    $('#table-foot').hide();
                else
                    $('#table-foot').show();
            }

            filterBook = () => {
                $('.book-tr').show();

                sort = false;
                // flag
                if (flag != "all") {
                    sort = true;
                    if (flag == 'available-tr') {
                        flagOnStock.hide();
                        flagSoldOut.hide();
                    } else if (flag == 'on-stock') {
                        flagAvailable.hide();
                        flagSoldOut.hide();
                    } else if (flag == 'sold-out') {
                        flagAvailable.hide();
                        flagSoldOut.hide();
                    }
                }

                // language
                if (language != "all") {
                    sort = true;
                    if (language == "chinese") {
                        languageEnglish.hide();
                        languageHindi.hide();
                        languageNepali.hide();
                    } else if (language == "english") {
                        languageChinese.hide();
                        languageHindi.hide();
                        languageNepali.hide();
                    } else if (language == "hindi") {
                        languageChinese.hide();
                        languageEnglish.hide();
                        languageNepali.hide();
                    } else if (language == "nepali") {
                        languageChinese.hide();
                        languageEnglish.hide();
                        languageHindi.hide();
                    }
                }

                toggleClearSort();
                toggleEmptyRow();
            }

            // clear filtering
            toggleClearSort = () => {
                if (sort == true)
                    clearSort.show();
                else
                    clearSort.hide();
            }

            filterBook();
            toggleEmptyRow();

            // search books
            $('#search-form').submit(function (e) {
                e.preventDefault();
                var search_content = $('#admin-search-content').val();

                search_content = $.trim(search_content).toLowerCase();

                searchBook(search_content);
            });

            function searchBook(search_content) {
                $('#clear-search-btn').removeClass('d-none');
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/sections/search-book.php",
                    data: { content: search_content },
                    beforeSend: function () {
                        $('#book-table-body').html("<tr> <td colspan = '8'> <div class='d-flex flex-row gap-2 table-loading-gif-container'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width: 20px;'> <p class='m-0 text-secondary'> Searching book... </p> </div> </td> </tr>");
                    },
                    success: function (data) {
                        $('#book-table-body').html(data);
                        $('#clear-search-btn').removeClass('invisible');
                    }
                });
            }

            // clear search
            $('#clear-search-btn').click(function () {
                fetchBooks();
                $('#clear-search-btn').addClass('invisible');
                $('#search-form').trigger("reset");
            });
        });
    </script>
</body>

</html>