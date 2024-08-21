<?php
require_once __DIR__ . '/functions/genre-array.php';
require_once __DIR__ . '/functions/district-array.php';
require_once __DIR__ . '/classes/book.php';

$url = "profile";

$userExists = $profileUser->fetch($profileId);

$bookObj = new Book();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Book Requests </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/book.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <main class="d-flex flex-column gap-lg-3 pb-5 container main">
        <!-- heading -->
        <h2 class="m-0 fs-3 fw-semibold mb-4"> Requests </h2>

        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col"> # </th>
                        <th scope="col"> Title </th>
                        <th scope="col"> Price </th>
                        <th scope="col"> Request Date </th>
                        <th scope="col"> Served Date </th>
                        <th scope="col"> Status </th>
                        <th scope="col"> Action </th>
                    </tr>
                </thead>
                <tbody id="request-table-body">
                    <tr class="d-none invisible">
                        <th scope="row"> 1 </th>
                        <td> Title </td>
                        <td> Price </td>
                        <td> 0000-00-00 </td>
                        <td> 0000-00-00 </td>
                        <td> Accepted </td>
                        <td> <a href="" class="text-primary" data-bs-toggle="modal" data-bs-target="#request-modal"
                                data-request-id=""> Show detail </a> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="request-modal" tabindex="0" aria-labelledby="request-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <h2 class="m-0 mb-3"> If you accept this request, you must submit your book to out physical shop within
                    <span class="text-danger"> 3 days </span> .
                </h2>

                <p class="text-secondary mb-3"> Request left unresponded withing 3 days will be set as expired. </p>

                <div class="d-flex flex-row gap-2 action">
                    <button class="btn btn-success" id="accept-request-btn"> Accept </button>
                    <button class="btn btn-outline-danger" id="reject-request-btn"> Reject </button>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- script -->
    <script>
        $(document).ready(function () {
            function fetchRequestTable() {
                $.ajax({
                    url: '/bookrack/sections/fetch-users-requests.php',
                    type: 'POST',
                    data: { userId: '<?= $profileId ?>' },
                    success: function (data) {
                        $('#request-table-body').html(data);
                    }
                });
            }

            fetchRequestTable();
        });
    </script>
</body>

</html>