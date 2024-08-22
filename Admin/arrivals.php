<?php
$url = "arrivals";
$page = "arrivals";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Dashboard </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/dashboard.css">
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>

    <main class="main pt-5 mt-5">

        <div class="d-flex flex-row gap-3 heading">
            <p class="f-reset fs-3 fw-bold"> Arrivals </p>
            <button class="d-none btn btn-danger fit-content" id="clear-search"> Clear Search </button>
        </div>

        <div class="mt-4 table-container">
            <table class="table">
                <thead>
                    <th scope="col" class="border"> SN. </th>
                    <th scope="col" class="border"> Title </th>
                    <th scope="col" class="border"> ISBN </th>
                    <th scope="col" class="border"> Owner </th>
                    <th scope="col" class="border"> Action </th>
                </thead>

                <tbody id="arrival-table-body">
                    <tr>
                        <td colspan="5" class="border">
                            <!-- loading -->
                            <div class="loading-div">
                                <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="">
                                <p> Fetching new arrivals </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <script>
        $(document).ready(function () {
            // fetch on hold-books
            function fetchOnHoldBooks() {
                $.ajax({
                    url: "/bookrack/admin/sections/fetch-on-hold-books.php",
                    beforeSend: function () {
                        $('#arrival-table-body').html("<tr> <td colspan='5' class='border'> <div class='loading-div'> <img src='/bookrack/assets/gif/filled-fading-balls.gif'> <p> Fetching on-hold books... </p> </div> </td> </tr>");
                    },
                    success: function (data) {
                        $('#arrival-table-body').html(data);
                    }
                });
            }

            fetchOnHoldBooks();

            $('#search-form').submit(function (e) {
                e.preventDefault();

                var search_content = $('#admin-search-content').val();

                search_content = $.trim(search_content).toLowerCase();

                if (search_content != '') {

                    $.ajax({
                        type: "POST",
                        url: "/bookrack/admin/sections/search-arrival.php",
                        data: { content: search_content },
                        beforeSend: function () {
                            $('#arrival-table-body').html("<tr> <td colspan='5' class='border'> <div class='loading-div'> <img src='/bookrack/assets/gif/filled-fading-balls.gif'> <p> Fetching new arrivals </p> </div> </td> </tr>");
                        },
                        success: function (data) {
                            $('#arrival-table-body').html(data);
                            $('#clear-search').addClass('d-flex').removeClass('d-none');
                        }
                    });
                }
            });

            $('#clear-search').click(function () {
                fetchOnHoldBooks();
                $('#search-form').trigger('reset');
                $('#clear-search').removeClass('d-flex').addClass('d-none');
            });
        });
    </script>
</body>