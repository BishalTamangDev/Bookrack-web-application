<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

$userId = $_SESSION['bookrack-user-id'];
$url = "cart";

require_once __DIR__ . '/classes/user.php';
$profileUser = new User();

$userExists = $profileUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/signin");

if (!isset($tab)) {
    $tab = "current";
}

require_once __DIR__ . '/classes/cart.php';
$cart = new Cart();
$cart->setUserId($userId);
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
            <i class="fa fa-shopping-cart fs-5"></i>
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
                        <a href="/bookrack/cart/checkout" class="btn w-100 text-light py-2 checkout-btn" data-bs-toggle="modal" data-bs-target="#exampleModal"> CHECKOUT NOW </a>
                        <p class="m-0 text-light mb-3"> Update your details first to proceed. </p>
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
                                    value="<?=$profileUser->getFullAddress()?>" id="" class="form-control">
                            </div>

                            <button type="submit" class="btn text-white" name="checkout-btn" id="place-order-btn"> Place
                                order </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        } elseif ($tab == "pending") {
            require_once __DIR__ . '/classes/cart.php';
            require_once __DIR__ . '/classes/book.php';
            $cart = new Cart();
            $bookObj = new Book();
            $pendingCartIds = $cart->fetchPendingCartId();

            if (sizeof($pendingCartIds) > 0) {
                foreach ($pendingCartIds as $pendingCartId) {
                    $cart->fetch($pendingCartId);
                    ?>
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

                    <!-- status details -->
                    <div class="d-flex flex-row flex-wrap mt-3 gap-5 mb-2 pending-cart-status-div">
                        <div class="pending-cart-status">
                            <div class="status-image-div">
                                <img class="status-image <?= $cart->date['order_placed'] != '' ? 'completed-status-image' : ''; ?>"
                                    src="/bookrack/assets/icons/order-received.png" alt="" loading="lazy">
                            </div>
                            <p class="m-0"> Order Received </p>
                        </div>

                        <div class="pending-cart-status">
                            <div class="status-image-div">
                                <img class="status-image <?= $cart->date['order_confirmed'] != '' ? 'completed-status-image' : ''; ?>"
                                    src="/bookrack/assets/icons/order-confirmed.png" alt="" loading="lazy">
                            </div>
                            <p class="m-0"> Order Confirmed </p>
                        </div>

                        <div class="pending-cart-status">
                            <div class="status-image-div">
                                <img class="status-image <?= $cart->date['order_arrived'] != '' ? 'completed-status-image' : ''; ?>"
                                    src="/bookrack/assets/icons/order-arrived.png" alt="" loading="lazy">
                            </div>
                            <p class="m-0"> Arrived </p>
                        </div>

                        <div class="pending-cart-status">
                            <div class="status-image-div">
                                <img class="status-image <?= $cart->date['order_packed'] != '' ? 'completed-status-image' : ''; ?>"
                                    src="/bookrack/assets/icons/order-packed.png" alt="" loading="lazy">
                            </div>
                            <p class="m-0"> Packed </p>
                        </div>

                        <div class="pending-cart-status">
                            <div class="status-image-div">
                                <img class="status-image <?= $cart->date['order_shipped'] != '' ? 'completed-status-image' : ''; ?>"
                                    src="/bookrack/assets/icons/order-shipped.png" alt="" loading="lazy">
                            </div>
                            <p class="m-0"> Shipped </p>
                        </div>


                        <div class="pending-cart-status">
                            <div class="status-image-div">
                                <img class="status-image <?= $cart->date['order_delivered'] != '' ? 'completed-status-image' : ''; ?>"
                                    src="/bookrack/assets/icons/order-delivered.png" alt="" loading="lazy">
                            </div>
                            <p class="m-0"> Delivered </p>
                        </div>

                        <div class="pending-cart-status">
                            <div class="status-image-div">
                                <img class="status-image <?= $cart->date['order_completed'] != '' ? 'completed-status-image' : ''; ?>"
                                    src="/bookrack/assets/icons/order-completed.png" alt="" loading="lazy">
                            </div>
                            <p class="m-0"> Completed </p>
                        </div>
                    </div>

                    <!-- pending cart section -->
                    <section class="d-flex flex-column-reverse flex-lg-row justify-content-between pending-cart-section">
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
                                    <?php
                                    foreach ($cart->bookList as $list) {
                                        $bookObj->fetch($list['id']);
                                        $bookObj->setPhotoUrl();
                                        ?>
                                        <!-- book -->
                                        <tr>
                                            <td>
                                                <div class="book-image">
                                                    <img src="<?= $bookObj->photoUrl ?>" alt="">
                                                </div>
                                            </td>
                                            <td class="title pointer"
                                                onclick="window.location.href='/bookrack/book-details/<?= $list['id'] ?>'">
                                                <?= ucwords($bookObj->title) ?>
                                            </td>
                                            <td class="arrival-date">
                                                <?= isset($list['arrived_date']) && $list['arrived_date'] != '' ? $list['arrived_date'] : "Not-arrived" ?>
                                            </td>
                                            <td class="price text-success"> <?= "NPR. " . number_format($list['price'], 2) ?> </td>
                                        </tr>
                                        <?php
                                        ?>
                                        <?php
                                    }
                                    ?>
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
                                <p class="m-0"> <?= ucwords($cart->checkoutOption) ?> </p>
                            </div>

                            <?php
                            if ($cart->checkoutOption == "cash-on-delivery" || $cart->checkoutOption == 'digital-wallt') {
                                ?>
                                <div class="d-flex flex-column gap-1 checkout-detail-div">
                                    <div class="d-flex flex-row justify-content-between checkout-detail">
                                        <p class="m-0"> Shipping address </p>
                                        <p class="m-0"> Bansbari, Kathmandu </p>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <?php $total = $cart->subTotal; ?>

                            <div class="d-flex flex-column gap-1 checkout-detail-div">
                                <div class="d-flex flex-row justify-content-between  checkout-detail">
                                    <p class="m-0"> Subtotal </p>
                                    <p class="m-0"> <?= "NPR. " . number_format($cart->subTotal, 2) ?> </p>
                                </div>

                                <?php
                                if ($cart->checkoutOption == "cash-on-delivery" || $cart->checkoutOption == 'digital-wallt') {
                                    $shippingCharge = 50;
                                    $total += $shippingCharge;
                                    ?>
                                    <div class="d-flex flex-row justify-content-between  checkout-detail">
                                        <p class="m-0"> Shipping Charge </p>
                                        <p class="m-0"> NRs. 50 </p>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="d-flex flex-row justify-content-between  checkout-detail">
                                    <p class="m-0"> Estimated Total </p>
                                    <p class="m-0"> <?= "NPR. " . number_format($total, 2) ?> </p>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
                }
            } else {
                ?>
                <p class="m-0 text-danger"> You haven't proceeded any cart yet! </p>
                <?php
            }
            ?>
            <?php
        } elseif ($tab == "completed") {
            ?>
            <!-- completed cart -->
            <section class="d-flex flex-column-reverse flex-lg-row justify-content-between completed-cart-section">
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

                <div class="rounded p-1 cart-detail">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">S.N.</th>
                                <th scope="col">Book</th>
                                <th scope="col">Title</th>
                                <th scope="col">Purpose</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>
                                    <div class="book-image">
                                        <img src="/bookrack/assets/images/book-1.jpg" alt="book photo" loading="lazy">
                                    </div>
                                </td>
                                <td class="title cursor"> The Black Universe </td>
                                <td> Rent </td>
                                <td class="price"> NRs. 140 </td>
                                <td class="action"> <i class="fa fa-multiply fs-4"></i> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column rounded p-3 gap-3 checkout">
                    <div class="heading">
                        <p class="m-0"> ORDER SUMMARY </p>
                    </div>

                    <div class="d-flex flex-column gap-1 checkout-detail-div">
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="m-0"> Shipping address </p>
                            <p class="m-0"> Bansbari, Kathmandu </p>
                        </div>
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="m-0"> Arrival day </p>
                            <p class="m-0"> 4 days </p>
                        </div>
                        <hr>
                    </div>

                    <div class="d-flex flex-column gap-1 checkout-detail-div">
                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="m-0"> Subtotal </p>
                            <p class="m-0"> NRs. 150 </p>
                        </div>

                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="m-0"> Shipping </p>
                            <p class="m-0"> NRs. 75 </p>
                        </div>

                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="m-0"> Total </p>
                            <p class="m-0"> NRs. 225 </p>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        }
        ?>
    </main>

    <!-- popup alert -->
    <p class="" id="custom-popup-alert"> Popup message appears here... </p>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- checkout modal script -->
    <script>
        $(document).ready(function () {
            const checkoutOption = $('#checkout-option');
            const shippingAddress = $('#shipping-address');
            const shippingAddressContainer = $('#shipping-address-container');

            // current cart
            const current_tab = "<?= $tab ?>";

            function showPopupAlert(msg) {
                $('#custom-popup-alert').removeClass('text-success').addClass('text-danger');
                $('#custom-popup-alert').html(msg).fadeIn();
                setTimeout(function () {
                    $('#custom-popup-alert').fadeOut("slow").html("");
                }, 4000);
            }

            if (current_tab == "current") {
                $('#custom-popup-alert').hide();
                // Generate CSRF token and set it to the forms
                function setCSRFToken() {
                    $.get('/bookrack/app/csrf-token.php', function (data) {
                        $('#csrf_token_cart').val(data);
                    });
                }

                function setCurrentCartId(){
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
                                showPopupAlert("Cart has been proceeded.");
                                window.location.href = '/bookrack/cart/pending';
                            } else {
                                // showPopupAlert("Cart couldn't be proceeded.");
                                showPopupAlert(response);
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