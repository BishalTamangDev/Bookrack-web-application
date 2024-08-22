<?php
$url = "order-summary";
$page = "order-summary";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Orders </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/nav.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <main class="main">
        <!-- cards -->
        <section class="section mt-5 pt-3 card-container">
            <!-- total books -->
            <div class="card-v1">
                <p class="card-v1-title"> Total Orders </p>
                <p class="card-v1-detail" id="total-orders"> - </p>
            </div>

            <!-- pending -->
            <div class="card-v1">
                <p class="card-v1-title"> Pending </p>
                <p class="card-v1-detail" id="pending-orders"> - </p>
            </div>

            <!-- completed -->
            <div class="card-v1">
                <p class="card-v1-title"> Completed </p>
                <p class="card-v1-detail" id="completed-orders"> - </p>
            </div>

        </section>

        <!-- table to section -->
        <div class="section table-top-section">
            <!-- filter -->
            <div class="filter-div flex-wrap">
                <select class="form-select" aria-label="select" id="flag-select">
                    <option value="all" selected> Order status: all </option>
                    <option value="available"> Book status: available </option>
                    <option value="on-stock"> Book status: on stock </option>
                    <option value="sold-out"> Book status: sold out </option>
                </select>

                <!-- clear filter -->
                <div class="clear-filter-div" id="clear-sort">
                    <p class="f-reset"> Clear </p>
                    <i class="fa fa-multiply"></i>
                </div>
            </div>
        </div>

        <div class="table-container">
            <!-- book table -->
            <div class="table-container">
                <table class="table table-striped book-table">
                    <!-- header -->
                    <thead>
                        <tr>
                            <th scope="col"> SN </th>
                            <th scope="col"> Cart ID </th>
                            <th scope="col"> Customer Name </th>
                            <th scope="col"> Order Placed </th>
                            <th scope="col"> Order Completed </th>
                            <th scope="col"> Status </th>
                            <th scope="col"> </th>
                        </tr>
                    </thead>

                    <!-- body -->
                    <tbody id="order-table-body">
                        <tr class="d-none invisible order-status-completed">
                            <td> 1. </td>
                            <td> 123456 </td>
                            <td>
                                <a href="" class="text-primary">
                                    4567854
                                </a>
                            </td>
                            <td> 0000-00-00 </td>
                            <td> 0000-00-00 </td>
                            <td> Completed </td>
                            <td>
                                <a href="" class="text-primary small">
                                    Show detail
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9">
                                <div class="d-flex flex-row gap-2 table-loading-gif-container">
                                    <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="" style="width: 20px;">
                                    <p class="m-0 text-secondary"> Fetching orders </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button class="invisible btn btn-danger mt-2" id="clear-search-btn"> Clear search </button>
            </div>
        </div>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <script>
        $(document).ready(function () {
            // fetch orders
            function fetchOrders() {
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/sections/fetch-orders.php",
                    beforeSend: function () {
                        $('#order-table-body').html("<tr> <td colspan = '7'> <div class='d-flex flex-row gap-2 table-loading-gif-container'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width: 20px;'> <p class='m-0 text-secondary'> Fetching all orders... </p> </div> </td> </tr>");
                    },
                    success: function (data) {
                        $('#order-table-body').html(data);
                    }
                });
            }

            // count total orders except current
            function countCarts(){
                // all cart :: except current
                $.ajax({
                    url: "/bookrack/admin/app/count-all-carts.php",
                    success: function (data) {
                        $('#total-orders').html(data);
                    }
                });

                // pending cart
                $.ajax({
                    url: "/bookrack/admin/app/count-pending-carts.php",
                    success: function (data) {
                        $('#pending-orders').html(data);
                    }
                });

                // completed cart
                $.ajax({
                    url: "/bookrack/admin/app/count-completed-carts.php",
                    success: function (data) {
                        $('#completed-orders').html(data);
                    }
                });
            }
            
            countCarts();

            fetchOrders();
        });
    </script>

</body>

</html>