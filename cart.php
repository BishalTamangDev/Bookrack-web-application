<?php
require_once __DIR__ . '/classes/cart.php';
require_once __DIR__ . '/classes/user.php';

$url = "cart";

$response = $profileUser->fetch($profileId);

if (!isset($tab)) {
    $tab = "current";
}

$cart = new Cart();
$cart->setUserId($profileId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Cart </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/cart.css">
    <link rel="stylesheet" href="/bookrack/css/checkout.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <!-- main -->
    <main class="d-flex flex-column gap-4 container main">
        <!-- heading section -->
        <section class="d-flex flex-row align-items-center gap-3 heading">
            <h4 class="m-0 fw-bold"> MY CART </h4>
        </section>

        <!-- tab select -->
        <div class="d-flex flex-row gap-2 cart-status-container">
            <button class="btn <?= ($tab == "current") ? "active-cart" : "inactive-cart"; ?>" id="current-cart-btn"
                onclick="window.location.href='/bookrack/cart/current'"> Current Cart </button>
            <button class="btn <?= ($tab == "pending") ? "active-cart" : "inactive-cart"; ?>" id="pending-cart-btn"
                onclick="window.location.href='/bookrack/cart/pending'"> Pending Cart </button>
            <button class="btn <?= ($tab == "completed") ? "active-cart" : "inactive-cart"; ?>" id="completed-cart-btn"
                onclick="window.location.href='/bookrack/cart/completed'"> Completed Cart </button>
        </div>

        <!-- cart section -->
        <!-- current cart section -->
        <?php
        if ($tab == "current") {
            ?>
            <!-- current cart section -->
            <section class="d-flex flex-column-reverse flex-lg-row justify-content-between current-cart-section"
                id="current-cart-section">

                <!-- skeleton cart -->
                <div class="cart-loading-container">
                    <div class="loading-table">
                        <div class="loading-heading">
                            <p class=""> </p>
                            <p class=""> </p>
                            <p class=""> </p>
                            <p class=""> </p>
                        </div>

                        <div class="loading-content">
                            <p class=""> </p>
                            <p class=""> </p>
                            <p class=""> </p>
                            <p class=""> </p>
                        </div>
                    </div>

                    <div class="loading-checkout"> </div>
                </div>

                <div class="d-none rounded p-1 cart-detail">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">S.N.</th>
                                <th scope="col">Book</th>
                                <th scope="col">Title</th>
                                <th scope="col">Availability</th>
                                <th scope="col">Price</th>
                                <th scope="col">Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <th scope="row"> 1 </th>
                                <td>
                                    <div class="book-image">
                                        <img src="/bookrack/assets/images/book-1.jpg" alt="" loading="lazy">
                                    </div>
                                </td>
                                <td class="title"> Title </td>
                                <td class="availability"> Available || Not-available </td>
                                <td class="price"> NPR. 00.00 </td>
                                <td class="remove">
                                    <i class="fa-solid fa-multiply fs-4" data-book-id=""></i>
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="2" colspan="8" class="text-danger pt-4" style="text-align:center"> Your cart is
                                    empty!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- order summary -->
                <div class="d-none d-flex flex-column rounded p-3 gap-3 checkout">
                    <div class="heading">
                        <p class="m-0"> ORDER SUMMARY </p>
                    </div>

                    <hr class="m-0">

                    <div class="d-flex flex-column gap-1 checkout-detail-div">
                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="m-0"> Total </p>
                            <p class="m-0"> NPR. 00.00 </p>
                        </div>
                    </div>

                    <div class="checkout-btn-div">
                        <a href="/bookrack/cart/checkout" class="btn w-100 text-light py-2 checkout-btn"
                            data-bs-toggle="modal" data-bs-target="#exampleModal"> CHECKOUT NOW </a>
                        <p class="m-0 text-light mb-3"> Verify you account first to proceed. </p>
                        <button class="btn w-100 text-secondary py-2 checkout-btn"> CHECKOUT NOW </button>
                        <p class="m-0 text-light mb-3"> Some books as now available. </p>
                        <button class="btn w-100 text-secondary py-2 checkout-btn"> CHECKOUT NOW </button>
                    </div>

                    <div class="payment-partner-div">
                        <div class="d-flex flex-row justify-content-between align-items-center payment-partner">
                            <p class="m-0 small"> Our payment partner </p>
                            <img src="/bookrack/assets/icons/esewa-logo.webp" alt="" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"> Checkout </h1>
                            <button type="button" class="btn-close" id="modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form method="POST" class="d-flex flex-column gap-3 p-3 form checkout-form" id="checkout-form">
                            <!-- csrf token -->
                            <input type="hidden" name="csrf_token_cart" class="form-control" id="csrf_token_cart" required>

                            <!-- cart id -->
                            <input type="hidden" name="cart-id" class="form-control" id="current-cart-id" required>

                            <!-- checkout option -->
                            <div class="d-flex flex-column gap-2">
                                <label for="checkout-select"> Checkout Option </label>
                                <select name="checkout-option" id="checkout-option" class="form-select" required>
                                    <option value="click-and-collect" selected> Click and Collect </option>
                                    <option value="cash-on-delivery"> Cash on Delivery </option>
                                    <option value="digital-wallet"> Digital Wallet </option>
                                </select>
                            </div>

                            <!-- shipping addesss -->
                            <div class="=gap-2" id="shipping-address-container">
                                <div class="d-flex flex-row justify-content-between top">
                                    <label for=""> Shipping Address </label>
                                    <a href="/bookrack/profile/" class="text-primary small"> Change </a>
                                </div>
                                <input type="text" name="shipping-address" id="shipping-address"
                                    onkeypress="event.preventDefault()" onkeydown="event.preventDefault()"
                                    value="<?= $profileUser->getFullAddress() ?>" id="" class="form-control">
                            </div>

                            <button type="submit" class="btn text-white" name="checkout-btn" id="place-order-btn"> Place
                                order </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        } elseif ($tab == "pending") {
            ?>
            <h4 class="m-0 fw-bold mb-1 mt-4"> Order Status </h4>

            <div class="cart-loading-container" id="pending-cart-skeleton-loading">
                <div class="loading-table">
                    <div class="loading-heading">
                        <p class=""> </p>
                        <p class=""> </p>
                        <p class=""> </p>
                        <p class=""> </p>
                    </div>

                    <div class="loading-content">
                        <p class=""> </p>
                        <p class=""> </p>
                        <p class=""> </p>
                        <p class=""> </p>
                    </div>
                </div>

                <div class="loading-checkout"> </div>
            </div>

            <!-- status details -->
            <div class="d-none d-flex flex-row flex-wrap mt-3 gap-5 mb-2 pending-cart-status-div">
                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image" src="/bookrack/assets/icons/order-received.png"
                            alt="" loading="lazy">
                    </div>
                    <p class="m-0"> Order Received </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image" src="/bookrack/assets/icons/order-confirmed.png"
                            alt="" loading="lazy">
                    </div>
                    <p class="m-0"> Order Confirmed </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image" src="/bookrack/assets/icons/order-arrived.png"
                            alt="" loading="lazy">
                    </div>
                    <p class="m-0"> Arrived </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image" src="/bookrack/assets/icons/order-packed.png" alt="" loading="lazy">
                    </div>
                    <p class="m-0"> Packed </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image" src="/bookrack/assets/icons/order-shipped.png" alt="" loading="lazy">
                    </div>
                    <p class="m-0"> Shipped </p>
                </div>


                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image" src="/bookrack/assets/icons/order-delivered.png" alt="" loading="lazy">
                    </div>
                    <p class="m-0"> Delivered </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image" src="/bookrack/assets/icons/order-completed.png" alt="" loading="lazy">
                    </div>
                    <p class="m-0"> Completed </p>
                </div>
            </div>

            <!-- pending cart section -->
            <section class="d-none d-flex flex-column-reverse flex-lg-row justify-content-between pending-cart-section"
                id="pending-cart-section">
                <!-- cart details -->
                <div class="rounded p-1 cart-detail">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">Book</th>
                                <th scope="col">Title</th>
                                <th scope="col">Arrival date</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- book -->
                            <tr>
                                <td>
                                    <div class="book-image">
                                        <img src="/bookrack/assets/images/book-1.jpg" alt="">
                                    </div>
                                </td>
                                <td class="title pointer"> Title </td>
                                <td class="arrival-date"> Arrived || Not-arrived </td>
                                <td class="price text-success"> NPR. 140.00 </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- checkout details -->
                <div class="d-flex flex-column rounded p-3 gap-3 checkout">
                    <div class="heading">
                        <p class="m-0"> ORDER SUMMARY </p>
                    </div>

                    <hr class="m-0">

                    <div class="d-flex flex-row justify-content-between">
                        <p class="m-0"> Checkout option </p>
                        <p class="m-0"> Click and Collect </p>
                    </div>

                    <div class="d-flex flex-column gap-1 checkout-detail-div">
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="m-0"> Shipping address </p>
                            <p class="m-0"> Bansbari, Kathmandu </p>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-1 checkout-detail-div">
                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="m-0"> Subtotal </p>
                            <p class="m-0"> NPR. 140.00 </p>
                        </div>

                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="m-0"> Shipping Charge </p>
                            <p class="m-0"> NPR. 50 </p>
                        </div>

                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="m-0"> Estimated Total </p>
                            <p class="m-0"> NPR. 190.00 </p>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        } elseif ($tab == "completed") {
            ?>
            <!-- completed cart -->
            <section class="d-flex flex-column-reverse flex-lg-row justify-content-between completed-cart-section"
                id="completed-cart-section">
                <div class="rounded p-1 cart-detail">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">S.N.</th>
                                <th scope="col">Cart ID</th>
                                <th scope="col">Books</th>
                                <th scope="col">Initiatiated Date</th>
                                <th scope="col">Completed Date</th>
                            </tr>
                        </thead>

                        <tbody id="completed-cart-body">
                            <tr class="d-none">
                                <td> 1. </td>
                                <td> 123456 </td>
                                <td> Book 1, Book 2, Book 3 </td>
                                <td> 0000-00-00 00:00:00 </td>
                                <td> 0000-00-00 00:00:00 </td>
                            </tr>

                            <tr>
                                <td colspan="7">
                                    <div class="d-flex flex-row gap-2 table-loading-gif-container">
                                        <img src="/bookrack/assets/gif/filled-fading-balls.gif" alt="" style="width: 20px;">
                                        <p class="m-0 text-secondary"> Fetching completed carts... </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php
        }
        ?>
    </main>

    <!-- popup alert -->
    <?php include 'sections/popup-alert.php'; ?>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- popup alert -->
    <script src="/bookrack/js/popup-alert.js"></script>

    <!-- checkout modal script -->
    <script>
        $(document).ready(function () {
            $('#custom-popup-alert').hide();
            const checkoutOption = $('#checkout-option');
            const shippingAddress = $('#shipping-address');
            const shippingAddressContainer = $('#shipping-address-container');

            // current cart
            const current_tab = "<?= $tab ?>";

            if (current_tab == "current") {
                // Generate CSRF token and set it to the forms
                function setCSRFToken() {
                    $.get('/bookrack/app/csrf-token.php', function (data) {
                        $('#csrf_token_cart').val(data);
                    });
                }

                function setCurrentCartId() {
                    $.post('/bookrack/app/current-cart-id.php', function (data) {
                        $('#current-cart-id').val(data);
                    });
                }

                // load current cart
                function loadCurrentCart() {
                    $.ajax({
                        url: '/bookrack/sections/current-cart-table.php',
                        type: "POST",
                        success: function (data) {
                            $('#current-cart-section').html(data);
                            setCSRFToken();
                            setCurrentCartId();
                        },
                        error: function () {
                            console.log("Current fetching failed...");
                        }
                    });
                }

                loadCurrentCart();

                // deleting the book from cart
                $(document).on('click', '.current-cart-remove-icon', function () {
                    let book_id = $(this).data('book-id');
                    let price = $(this).data("price");
                    let closestTr = $(this).closest("tr");

                    $.ajax({
                        url: '/bookrack/app/remove-book-current-cart.php',
                        type: "POST",
                        data: { bookId: book_id },
                        beforeSend: function () {
                            $('#current-cart-total').html("Calculating");
                            // $('#checkout-btn').prop('disabled', true);
                            $('#checkout-btn').html('Please wait');
                            $('#checkout-btn').removeAttr('data-bs-toggle');
                            if ($('.current-cart-tr').length == 1) {
                                output = "<tr> <td rowspan='2' colspan='8' class='text-danger pt-4' style='text-align:center'> Your cart is empty! </td> </tr>";
                                closestTr.replaceWith(output);
                            } else {
                                closestTr.remove();
                            }
                        },
                        success: function (response) {
                            loadCurrentCart();
                        }
                    });
                });
            } else if (current_tab == "pending") {
                // fetch pending cart
                $.ajax({
                    url: '/bookrack/sections/pending-cart-table.php',
                    type: "POST",

                    success: function (data) {
                        $('#pending-cart-skeleton-loading').remove();
                        $('#pending-cart-section').replaceWith(data);
                    }
                });
            } else if (current_tab == 'completed') {
                // fetch completed cart table
                function fetchCompletedCart() {
                    $.ajax({
                        type: "POST",
                        url: "/bookrack/sections/completed-cart-table.php",
                        data: { userId: '<?= $profileId ?>' },
                        success: function (data) {
                            $('#completed-cart-body').html(data);
                        }
                    });
                }

                fetchCompletedCart();
            }

            // checkout option
            shippingAddress.hide();
            shippingAddressContainer.hide();

            checkoutOption.on('change', function () {
                if (checkoutOption.val() != 'click-and-collect') {
                    shippingAddress.show();
                    shippingAddressContainer.show();
                } else {
                    shippingAddress.hide();
                    shippingAddressContainer.hide();
                }
            });

            // checkout form submission
            $('#checkout-form').submit(function (e) {
                e.preventDefault();
                let checkoutMethod = $('#checkout-option').val();

                let formData = $('#checkout-form').serialize();

                if (checkoutMethod == "click-and-collect" || checkoutMethod == "cash-on-delivery") {
                    // just update current cart
                    $.ajax({
                        url: '/bookrack/app/click-and-collect-cash-on-delivery.php',
                        method: "POST",
                        data: formData,
                        beforeSend: function () {
                            $('#place-order-btn').html("Placing order...").prop("disabled", true);
                        },
                        success: function (response) {
                            $('#modal-close-btn').click();
                            $('#place-order-btn').html("Place order").prop("disabled", false);
                            if (response == true) {
                                showPopupAlert("Order has been placed.");
                                window.location.href = '/bookrack/cart/pending';
                            } else {
                                // showPopupAlert("Cart couldn't be proceeded.");
                                showPopupAlert("An error occured.");
                            }
                        },
                        error: function () {
                            $('#place-order-btn').html("Place order").prop("disabled", false);
                            showPopupAlert("An error occured.");
                        }
                    });
                } else if (checkoutMethod == "digital-wallet") {
                    // redirect ro mobile wallet
                }
            });
        });
    </script>
</body>

</html>