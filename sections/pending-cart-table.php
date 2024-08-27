<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    exit;

require_once __DIR__ . '/../classes/book.php';
require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../classes/cart.php';

$userId = $_SESSION['bookrack-user-id'];
$user = new User();

$cart = new Cart();
$bookObj = new Book();

$user->fetch($userId);

$cart->setUserId($userId);
$cart->fetchCurrent();
$pendingCartIds = $cart->fetchPendingCartId();

if (sizeof($pendingCartIds) > 0) {
    foreach ($pendingCartIds as $pendingCartId) {
        $cart->fetch($pendingCartId);
        ?>
        <div class="d-flex flex-row bg-light fit-content p-2">
            <p class="mb-0"> Order ID : <?= $pendingCartId ?> </p>
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

            <?php
            if ($cart->checkoutOption != 'click-and-collect') {
                ?>
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
                <?php
            }
            ?>

            <div class="pending-cart-status">
                <div class="status-image-div">
                    <img class="status-image <?= $cart->date['order_completed'] != '' ? 'completed-status-image' : ''; ?>"
                        src="/bookrack/assets/icons/order-completed.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Completed </p>
            </div>
        </div>

        <!-- pending cart section -->
        <section class="d-flex flex-column-reverse flex-lg-row justify-content-between mb-3 pending-cart-section"
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
                        <?php
                        foreach ($cart->bookList as $list) {
                            $bookObj->fetch($list['id']);
                            $bookObj->setPhotoUrl();
                            ?>
                            <!-- book -->
                            <tr>
                                <td class="px-2">
                                    <div class="book-image">
                                        <img src="<?= $bookObj->photoUrl ?>" alt="">
                                    </div>
                                </td>
                                <td class="title pointer">
                                    <a href="/bookrack/book-details/<?= $list['id'] ?>">
                                        <?= ucwords($bookObj->title) ?>
                                    </a>
                                </td>
                                <td class="arrival-date">
                                    <?= isset($list['arrived_date']) && $list['arrived_date'] != '' ? $list['arrived_date'] : "Not-arrived" ?>
                                </td>
                                <td class="price text-success"> <?= "NPR. " . number_format($bookObj->getOfferPrice(), 2) ?> </td>
                            </tr>
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
                            <p class="m-0"> <?= $user->getFullAddress() ?> </p>
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