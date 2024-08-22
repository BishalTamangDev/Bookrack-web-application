<?php
$url = "order-details";
$page = "order-details";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Order Details </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/nav.css">
    <link rel="stylesheet" href="/bookrack/css/admin/order-details.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <main class="main mt-4">
        <p class="fw-bold page-heading mt-5"> Order Details </p>

        <div id="order-container">
            <div class="mt-2 loading-div">
                <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="">
                <p> Fetching order details </p>
            </div>
        </div>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <script>
        $(document).ready(function () {
            function fetchOrder() {
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/sections/fetch-order.php",
                    data: { cartId: "<?= $cartId ?>" },
                    success: function (data) {
                        $('#order-container').html(data);
                    }
                });
            }

            fetchOrder();
        });
    </script>
</body>

</html>