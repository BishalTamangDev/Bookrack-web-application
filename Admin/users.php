<?php
$url = "users";
$page = "users";

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Users </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/css/admin/users.css">
</head>

<body>
    <!-- aside :: nav -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <!-- main content -->
    <main class="main">
        <!-- cards -->
        <?php
        if (!$search) {
            ?>
            <section class="section mt-5 pt-3 card-container">
                <!-- number of users -->
                <div class="card-v1">
                    <p class="card-v1-title"> Number of Users </p>
                    <p class="card-v1-detail" id="total-user-counter"> - </p>
                </div>
            </section>
            <?php
        } else {
            // clear search
            ?>
            <div class="mt-5 pt-3 d-flex flex-row">
                <a href="/bookrack/admin/admin-users" class="btn btn-danger d-flex flex-row gap-2 align-items-center">
                    <p class="m-0"> Clear Search </p>
                    <i class="fa fa-multiply text-white fs-5"></i>
                </a>
            </div>
            <?php
        }
        ?>

        <!-- table to section -->
        <section class="section d-flex flex-column flex-lg-row gap-2 table-top-section">
            <div class="filter-div width-fit-content">
                <i class="fa fa-filter" id="filter-icon"></i>

                <!-- account state -->
                <select class="form-select" id="user-account-state-select" aria-label="Default select example">
                    <option value="all" selected> All account state </option>
                    <option value="verified"> Verified </option>
                    <option value="unverified"> Unverified </option>
                </select>

                <!-- clear filter -->
                <div class="clear-filter-div" id="clear-filter">
                    <p class="f-reset"> Clear </p>
                    <i class="fa fa-multiply"></i>
                </div>
            </div>
        </section>

        <!-- user table -->
        <div class="table-container">
            <table class="table user-table">
                <!-- table heading -->
                <thead>
                    <tr>
                        <th scope="col"> S.N. </th>
                        <th scope="col"> Name </th>
                        <th scope="col"> Email Address </th>
                        <th scope="col"> Phone Number </th>
                        <th scope="col"> Address </th>
                        <th scope="col"> Account State </th>
                        <th scope="col"> Action </th>
                    </tr>
                </thead>

                <tbody id="user-table-body">
                    <tr>
                        <td colspan="7">
                            <div class="d-flex flex-row gap-2 table-loading-gif-container">
                                <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="" style="width: 20px;">
                                <p class="m-0 text-secondary"> Fetching users </p>
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

    <!-- current file script -->
    <script>
        $(document).ready(function () {
            function fetchUserTable() {
                $.ajax({
                    url: '/bookrack/admin/sections/user-table.php',
                    beforeSend: function () {
                        $('#user-table-body').html("<tr> <td colspan = '7'> <div class='d-flex flex-row gap-2 table-loading-gif-container'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width: 20px;'> <p class='m-0 text-secondary'> Fetching all users... </p> </div> </td> </tr>");
                    },
                    success: function (data) {
                        $('#user-table-body').html(data);
                    }
                });
            }

            function countTotalUsers() {
                $.ajax({
                    url: '/bookrack/admin/app/count-total-users.php',
                    success: function (data) {
                        $('#total-user-counter').html(data);
                    }
                });
            }

            countTotalUsers();

            fetchUserTable();

            var accountStateSelect = $("#user-account-state-select");
            const clearFilter = $("#clear-filter");

            clearFilter.hide();

            clearFilter.click(function () {
                accountStateSelect.val("all");
                filter();
            });

            accountStateSelect.change(function () {
                var accountType = accountStateSelect.val();
                filter();
            });

            filter = () => {
                $('.user-tr').show();

                if (accountStateSelect.val() == "verified") {
                    $('.unverified-user-tr').hide();
                } else if (accountStateSelect.val() == "unverified") {
                    $('.verified-user-tr').hide();
                }

                if (accountStateSelect.val() != "all") {
                    clearFilter.show();
                } else {
                    clearFilter.hide();
                }
                toggleEmptyRow()
            }

            // show empty row
            toggleEmptyRow = () => {
                if ($('.user-tr').is(':visible'))
                    $('#table-foot').hide();
                else
                    $('#table-foot').show();
            }

            toggleEmptyRow();

            // search user
            $('#search-form').submit(function (e) {
                e.preventDefault();
                var search_content = $('#admin-search-content').val();

                search_content = $.trim(search_content).toLowerCase();

                searchUser(search_content);
            });

            function searchUser(search_content) {
                $('#clear-search-btn').removeClass('d-none');
                $.ajax({
                    type: "POST",
                    url: "/bookrack/admin/sections/search-user.php",
                    data: { content: search_content },
                    beforeSend: function () {
                        $('#user-table-body').html("<tr> <td colspan = '7'> <div class='d-flex flex-row gap-2 table-loading-gif-container'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width: 20px;'> <p class='m-0 text-secondary'> Searching user... </p> </div> </td> </tr>");
                    },
                    success: function (data) {
                        $('#user-table-body').html(data);
                        $('#clear-search-btn').removeClass('invisible');
                    }
                });
            }

            // clear search
            $('#clear-search-btn').click(function () {
                fetchUserTable();
                $('#clear-search-btn').addClass('invisible');
                $('#search-form').trigger("reset");
            });
        });
    </script>
</body>

</html>