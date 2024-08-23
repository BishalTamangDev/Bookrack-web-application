<?php
$url = "profile";

$userExists = $profileUser->fetch($profileId);
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
                        <th scope="col"> Submitted Date </th>
                        <th scope="col"> Status </th>
                    </tr>
                </thead>

                <tbody id="request-table-body">
                    <tr>
                        <td colspan="7">
                            <div class="d-flex flex-row gap-2 table-loading-gif-container">
                                <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="" style="width: 20px;">
                                <p class="m-0 text-secondary"> Fetching requests </p>
                            </div>
                        </td>
                    </tr>

                    <tr class="d-none invisible">
                        <th scope="row"> 1 </th>
                        <td> Title </td>
                        <td> Price </td>
                        <td> 0000-00-00 </td>
                        <td> 0000-00-00 </td>
                        <td> Accepted </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

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
                    beforeSend: function () {
                        $('#request-table-body').html('<tr> <td colspan="7"> <div class="d-flex flex-row gap-2 table-loading-gif-container"> <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="" style="width: 20px;"> <p class="m-0 text-secondary"> Fetching requests... </p> </div> </td> </tr>');
                    },
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