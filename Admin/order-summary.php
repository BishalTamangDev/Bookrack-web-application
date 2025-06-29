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

    <!-- popup alert -->
    <?php require_once __DIR__ . '/../sections/popup-alert.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <!-- popup script -->
    <script type="text/javascript" src="/bookrack/js/popup-alert.js"> </script>

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

            // confirm order
            $(document).on('click', '#order-status-confirmed-btn', function () {
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/app/mark-as-order-confirmed.php",
                    data: { cartId: "<?= $cartId ?>" },
                    beforeSend: function () {
                        $('#order-status-confirmed-btn').html("Confirming order...").prop('disabled', true);
                    },
                    success: function (response) {
                        if (response) {
                            msg = "Order confirmed";
                            $('#order-status-confirmed-btn').html("<i class='fa-solid fa-check-double'></i> Order Confirmed");

                            setTimeout(function () {
                                fetchOrder();
                            }, 1000);
                        } else {
                            msg = "Order couldn't be confirmed due to an error.";
                            $('#order-status-confirmed-btn').html("<i class='fa fa-check'></i> Mark as Order Confirmed").prop('disabled', false);
                        }
                        showPopupAlert(msg);
                    }
                });
            });

            // mark as order arrived
            $(document).on('click', '#order-status-arrived-btn', function () {
                var user_id = $(this).data('user-id');
                var cart_id = $(this).data('cart-id');
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/app/mark-as-order-arrived.php",
                    data: { cartId: cart_id, userId: user_id },
                    beforeSend: function () {
                        $('#order-status-arrived-btn').html('Marking as arrived...').prop('disabled', true);
                    },
                    success: function (response) {
                        msg = "";

                        if (response) {
                            msg = "Order marked as arrived";
                            $('#order-status-arrived-btn').html("<i class='fa-solid fa-check-double'></i> Marked as Arrived");

                            setTimeout(function () {
                                fetchOrder();
                            }, 1000);
                        } else {
                            msg = "Order couldn't be updated due to an error.";
                            $('#order-status-arrived-btn').html("<i class='fa fa-check'></i> Mark as Order Arrived").prop('disabled', false);
                        }
                        showPopupAlert(msg);
                    }
                });
            });

            // mark as order packed
            $(document).on('click', '#order-status-packed-btn', function () {
                console.clear();
                var user_id = $(this).data('user-id');
                var cart_id = $(this).data('cart-id');
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/app/mark-order-as-packed.php",
                    data: { cartId: cart_id, userId: user_id },
                    beforeSend: function () {
                        $('#order-status-packed-btn').html('Marking as packed...').prop('disabled', true);
                    },
                    success: function (response) {
                        msg = "";

                        if (response) {
                            msg = "Order marked as packed";
                            $('#order-status-packed-btn').html("<i class='fa-solid fa-check-double'></i> Marked as Packed");

                            setTimeout(function () {
                                fetchOrder();
                            }, 1000);
                        } else {
                            msg = "Order couldn't be updated due to an error.";
                            $('#order-status-packed-btn').html("<i class='fa fa-check'></i> Mark as Order Packed").prop('disabled', false);
                        }
                        showPopupAlert(msg);
                    }
                })
            });

            // mark as order completed
            $(document).on('click', '#order-status-completed-btn', function () {
                console.clear();
                var user_id = $(this).data('user-id');
                var cart_id = $(this).data('cart-id');
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/app/mark-order-as-completed.php",
                    data: { cartId: cart_id, userId: user_id },
                    beforeSend: function () {
                        $('#order-status-completed-btn').html('Marking order as completed...').prop('disabled', true);
                    },
                    success: function (response) {
                        msg = "";

                        if (response) {
                            msg = "Order marked as completed";
                            $('#order-status-completed-btn').html("<i class='fa-solid fa-check-double'></i> Marked as completed");

                            setTimeout(function () {
                                fetchOrder();
                            }, 1000);
                        } else {
                            msg = "Order couldn't be updated due to an error.";
                            $('#order-status-completed-btn').html("<i class='fa fa-check'></i> Mark as Order completed").prop('disabled', false);
                        }
                        showPopupAlert(msg);
                    }
                })
            });
        });
    </script>
</body>

</html>