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
    // header("Location: /bookrack/cart/current");
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
            require_once __DIR__ . '/classes/book.php';
            $bookObj = new Book();
            $cart->fetchCurrent();

            $total = 0;
            ?>

            <!-- staus message -->
            <?php
            if (isset($_SESSION['status']) && isset($_SESSION['status-message'])) {
                ?>
                <p class="m-0 text-danger"> <?= $_SESSION['status-message'] ?> </p>
                <?php
            }
            ?>
            <!-- current cart section -->
            <?php $allBooksAvailable = true; ?>
            <section class="d-flex flex-column-reverse flex-lg-row justify-content-between current-cart-section">
                <div class="rounded p-1 cart-detail">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">S.N.</th>
                                <th scope="col">Book</th>
                                <th scope="col">Title</th>
                                <th scope="col">Availability</th>
                                <!-- <th scope="col">Starting date </th> -->
                                <!-- <th scope="col">Ending date</th> -->
                                <th scope="col">Price</th>
                                <th scope="col">Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- book 1 -->
                            <?php
                            $serial = 1;
                            if (sizeof($cart->bookList) != 0) {
                                foreach ($cart->bookList as $book) {
                                    // fetch book details
                                    $bookObj->fetch($book['id']);
                                    $bookObj->setPhotoUrl();
                                    $available = $bookObj->flag == "verified" ? true : false;

                                    if (!$available)
                                        $allBooksAvailable = false;
                                    ?>
                                    <tr>
                                        <th scope="row"> <?= $serial++ ?> </th>
                                        <td>
                                            <div class="book-image">
                                                <img src="<?= $bookObj->photoUrl ?>" alt="" loading="lazy">
                                            </div>
                                        </td>
                                        <td class="title" onclick="window.location.href='/bookrack/book-details'">
                                            <?= ucwords($bookObj->title) ?>
                                        </td>

                                        <td class="availability <?= $available ? '' : 'text-danger' ?>">
                                            <?= $available ? "Available" : "Not-available" ?>
                                        </td>

                                        <td class="price">
                                            <?php
                                            if ($bookObj->purpose == "renting") {
                                                $rent = $bookObj->price['actual'] * 0.20;
                                                echo "NPR." . number_format($rent, 2) . "/week";
                                            } elseif ($bookObj->purpose == "buy/sell") {
                                                echo "NPR." . number_format($bookObj->price['offer'], 2);
                                                $total += $bookObj->price['offer'];
                                            }
                                            ?>
                                        </td>

                                        <td class="remove">
                                            <a
                                                href="/bookrack/app/cart-code.php?task=remove&bookId=<?= $book['id'] ?>&url=<?= $url ?>">
                                                <i class="fa-solid fa-multiply fs-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td rowspan="2" colspan="8" class="text-danger pt-4" style="text-align:center"> Your
                                        cart is empty! </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- order summary -->
                <?php
                if ($total != 0) {
                    ?>
                    <div class="d-flex flex-column rounded p-3 gap-3 checkout">
                        <div class="heading">
                            <p class="m-0"> ORDER SUMMARY </p>
                        </div>

                        <hr class="m-0">

                        <div class="d-flex flex-column gap-1 checkout-detail-div">
                            <div class="d-flex flex-row justify-content-between  checkout-detail">
                                <p class="m-0"> Total </p>
                                <p class="m-0"> <?= "NPR. " . number_format($total, 2) ?> </p>
                            </div>
                        </div>

                        <div class="checkout-btn-div">
                            <?php
                            if ($allBooksAvailable) {
                                if ($profileUser->accountStatus == 'verified') {
                                    ?>
                                    <a href="/bookrack/cart/checkout" class="btn w-100 text-light py-2 checkout-btn"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        CHECKOUT NOW
                                    </a>
                                    <?php
                                } else {
                                    ?>
                                    <p class="m-0 text-light mb-3"> Update your details first to proceed. </p>
                                    <button class="btn w-100 text-secondary py-2 checkout-btn">
                                        CHECKOUT NOW
                                    </button>
                                    <?php
                                }
                            } else {
                                ?>
                                <p class="m-0 text-light mb-3"> Some books as now available. </p>
                                <button class="btn w-100 text-secondary py-2 checkout-btn">
                                    CHECKOUT NOW
                                </button>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="payment-partner-div">
                            <div class="d-flex flex-row justify-content-between align-items-center payment-partner">
                                <p class="m-0 small"> Our payment partner </p>
                                <img src="/bookrack/assets/icons/esewa-logo.webp" alt="" loading="lazy">
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </section>

            <?php if ($total != 0) {
                ?>
                <div class="mt-3 note-section">
                    <p class="m-0 fst-italic text-danger"> Note: The order arrival day might differ eachtime as we need to
                        receive books from different providers. </p>
                </div>
                <?php
            } ?>
            <?php
        } elseif ($tab == "pending") {
            require_once __DIR__ . '/classes/book.php';
            $bookObj = new Book();
            $pendingCartIds = $cart->fetchPendingCartId();

            if (sizeof($pendingCartIds) > 0) {
                foreach ($pendingCartIds as $pendingCartId) {
                    $cart->fetch($pendingCartId);
                    ?>
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
                            <!-- book 1 -->
                            <tr>
                                <th scope="row">1</th>
                                <td>
                                    <div class="book-image">
                                        <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy">
                                    </div>
                                </td>
                                <td class="title cursor" onclick="window.location.href='/bookrack/book-details'"> The Black
                                    Universe </td>
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Checkout </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="/bookrack/app/checkout-code.php" method="POST"
                    class="d-flex flex-column gap-3 p-3 form checkout-form">
                    <!-- cart id -->
                    <input type="hidden" name="cart-id" value="<?= $cart->getId() ?>" id="" class="form-control"
                        required>

                    <!-- url -->
                    <input type="hidden" name="url" value="cart" id="" class="form-control" required>

                    <!-- checkout option -->
                    <div class="d-flex flex-column gap-2">
                        <label for="checkout-select"> Checkout Option </label>
                        <select name="checkout-option" id="checkout-option" class="form-select" required>
                            <option value="click-and-collect" selected> Click and Collect </option>
                            <option value="cash-on-delivery"> Cash on Delivery </option>
                            <option value="digita-wallet"> Digital Wallet </option>
                        </select>
                    </div>

                    <!-- shipping addesss -->
                    <div class="=gap-2" id="shipping-address-container">
                        <div class="d-flex flex-row justify-content-between top">
                            <label for=""> Shipping Address </label>
                            <a href="/bookrack/profile/edit-profile" class="text-primary small"> Change </a>
                        </div>
                        <input type="text" name="shipping-address" id="shipping-address"
                            onkeypress="event.preventDefault()" onkeydown="event.preventDefault()"
                            value="district, location" id="" class="form-control">
                    </div>

                    <button type="submit" class="btn text-white" name="checkout-btn"> Place order </button>
                </form>
            </div>
        </div>
    </div>

    <!-- unset the session status and message -->
    <?php
    unset($_SESSION['status']);
    unset($_SESSION['status-message']);
    ?>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <!-- checkout modal script -->
    <script>
        const checkoutOption = $('#checkout-option');
        const shippingAddress = $('#shipping-address');
        const shippingAddressContainer = $('#shipping-address-container');

        checkoutOption.on('change', function () {
            if (checkoutOption.val() != 'click-and-collect') {
                shippingAddress.show();
                shippingAddressContainer.show();
            } else {
                shippingAddress.hide();
                shippingAddressContainer.hide();
            }
        });

        shippingAddress.hide();
        shippingAddressContainer.hide();
    </script>
</body>

</html>