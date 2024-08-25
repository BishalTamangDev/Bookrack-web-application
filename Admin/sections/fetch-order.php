<?php

$cartId = $_POST['cartId'] ?? 0;

if ($cartId == 0) {
    echo "Order not found!";
    exit;
}

require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/cart.php';

$tempUser = new User();
$tempBook = new Book();
$tempCart = new Cart();

$exists = $tempCart->fetch($cartId);

if (!$exists) {
    echo "Order not found!";
    exit;
} else {
    $checkoutOption = $tempCart->checkoutOption;

    // dates
    $orderDate['placed'] = $tempCart->date['order_placed'] != '' ? $tempCart->date['order_placed'] : 0;
    $orderDate['confirmed'] = $tempCart->date['order_confirmed'] != '' ? $tempCart->date['order_confirmed'] : 0;
    $orderDate['arrived'] = $tempCart->date['order_arrived'] != '' ? $tempCart->date['order_arrived'] : 0;
    $orderDate['packed'] = $tempCart->date['order_packed'] != '' ? $tempCart->date['order_packed'] : 0;
    $orderDate['shipped'] = $tempCart->date['order_shipped'] != '' ? $tempCart->date['order_shipped'] : 0;
    $orderDate['delivered'] = $tempCart->date['order_delivered'] != '' ? $tempCart->date['order_delivered'] : 0;
    $orderDate['completed'] = $tempCart->date['order_completed'] != '' ? $tempCart->date['order_completed'] : 0;

    // check of all the books has arrived
    $allBooksArrived = true;
    foreach ($tempCart->bookList as $bookList) {
        if ($bookList['arrived_date'] == '') {
            $allBooksArrived = false;
        }
    }
    ?>

    <!-- order status -->
    <div class="d-flex flex-row flex-wrap mt-3 gap-4 column-gap-5 mb-2 order-status-div">
        <?php
        if ($checkoutOption == 'click-and-collect') {
            ?>
            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image <?= $orderDate['placed'] != 0 ? 'completed-status-image' : '' ?>"
                        src="/bookrack/assets/icons/order-received.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Received </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image <?= $orderDate['confirmed'] != 0 ? 'completed-status-image' : '' ?>"
                        src="/bookrack/assets/icons/order-confirmed.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Confirmed </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image <?= $orderDate['arrived'] != 0 ? 'completed-status-image' : '' ?>"
                        src="/bookrack/assets/icons/order-arrived.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Arrived </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image <?= $orderDate['packed'] != 0 ? 'completed-status-image' : '' ?>"
                        src="/bookrack/assets/icons/order-packed.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Packed </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image <?= $orderDate['completed'] != 0 ? 'completed-status-image' : '' ?>"
                        src="/bookrack/assets/icons/order-completed.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Completed </p>
            </div>
            <?php
        } else {
            ?>
            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image completed-status-image" src="/bookrack/assets/icons/order-received.png" alt=""
                        loading="lazy">
                </div>
                <p class="m-0"> Received </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image completed-status-image" src="/bookrack/assets/icons/order-confirmed.png" alt=""
                        loading="lazy">
                </div>
                <p class="m-0"> Confirmed </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image completed-status-image" src="/bookrack/assets/icons/order-arrived.png" alt=""
                        loading="lazy">
                </div>
                <p class="m-0"> Arrived </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image" src="/bookrack/assets/icons/order-packed.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Packed </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image" src="/bookrack/assets/icons/order-shipped.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Shipped </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image" src="/bookrack/assets/icons/order-delivered.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Delivered </p>
            </div>

            <div class="cart-status">
                <div class="status-image-div">
                    <img class="status-image" src="/bookrack/assets/icons/order-completed.png" alt="" loading="lazy">
                </div>
                <p class="m-0"> Completed </p>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- update order status -->
    <div class="d-flex flex-row flex-wrao gap-2 mt-4">
        <?php
        if ($tempCart->checkoutOption == 'click-and-collect') {
            $userId = $tempCart->getUserId();
            $cartTd = $tempCart->getId();

            if ($orderDate['placed'] != 0 && $orderDate['confirmed'] == 0) {
                ?>
                <button class="btn btn-brand" id="order-status-confirmed-btn"> <i class="fa fa-check"></i> Mark as Order Confirmed
                </button>
                <?php
            } elseif ($orderDate['confirmed'] != 0 && $orderDate['arrived'] == 0) {
                if ($allBooksArrived) {
                    ?>
                    <button class="btn btn-brand" id="order-status-arrived-btn" data-user-id="<?= $userId ?>"
                        data-cart-id="<?= $cartId ?>">
                        <i class="fa fa-check"></i> Mark as Order Arrived
                    </button>
                    <?php
                } else {
                    ?>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#order-not-arrived-modal"> <i
                            class="fa fa-check"></i> Mark as Order Arrived </button>
                    <?php
                }
                ?>
                <?php
            } elseif ($orderDate['arrived'] != 0 && $orderDate['packed'] == 0) {
                ?>
                <button class="btn btn-brand" id="order-status-packed-btn" data-user-id="<?= $userId ?>"
                    data-cart-id="<?= $cartId ?>">
                    <i class="fa fa-check"></i> Mark as Order Packed
                </button>
                <?php
            } elseif ($orderDate['packed'] != 0 && $orderDate['completed'] == 0) {
                ?>
                <button class="btn btn-brand" id="order-status-completed-btn" data-user-id="<?= $userId ?>"
                    data-cart-id="<?= $cartId ?>"> <i class="fa fa-check"></i> Mark as Order Completed
                </button>
                <?php
            }
            ?>
            <?php
        }
        ?>
    </div>

    <?php
    $tempUser->fetch($tempCart->getUserId());
    $customerName = $tempUser->getFullName();
    $phoneNumber = $tempUser->getPhoneNumber();
    ?>

    <!-- user info -->
    <div class="d-flex flex-row flex-wrap column-gap-4 row-gap-3">
        <!-- order information -->
        <div class="d-flex flex-column gap-2 table-container fit-content">
            <p class="fw-bold mt-5 fs-5"> Order Information </p>
            <table class="table fit-content">
                <!-- order id -->
                <tr class="border">
                    <td class="border"> Order Id </td>
                    <td class="border"> <?= $cartId ?> </td>
                </tr>

                <!-- payment option -->
                <tr class="border">
                    <td class="border"> Payment Options </td>
                    <td class="border"> <?= ucwords($tempCart->checkoutOption) ?> </td>
                </tr>

                <?php
                if ($tempCart->checkoutOption != 'click-and-collect') {
                    ?>
                    <!-- shipping charge -->
                    <tr class="border">
                        <td class="border"> Shipping Charge </td>
                        <td class="border"> <?= ucwords($tempCart->shippingCharge) ?> </td>
                    </tr>

                    <!-- shipping address -->
                    <tr class="border">
                        <td class="border"> Shipping Address </td>
                        <td class="border"> <?= $tempCart->getFullAddress() ?> </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>

        <!-- customer information -->
        <div class="d-flex flex-column gap-2 table-container fit-content">
            <p class="fw-bold mt-5 fs-5"> Customer Information </p>
            <table class="table fit-content">
                <!-- name -->
                <tr>
                    <td class="border"> Name </td>
                    <td class="border"> <?= $customerName ?> </td>
                </tr>

                <!-- contact -->
                <tr class="border">
                    <td class="border"> Phone Number </td>
                    <td class="border"> <?= $phoneNumber ?> </td>
                </tr>
            </table>
        </div>

        <!-- order tracking -->
        <div class="d-flex flex-column gap-2 table-container fit-content">
            <p class="fw-bold mt-5 fs-5"> Order Tracking </p>
            <table class="table fit-content">
                <!-- placed -->
                <tr>
                    <td class="border"> Placed </td>
                    <td class="border"> <?= $tempCart->date['order_placed'] ?> </td>
                </tr>

                <!-- confirmed -->
                <tr class="border">
                    <td class="border"> Confirmed </td>
                    <td class="border">
                        <?= $tempCart->date['order_confirmed'] != '' ? $tempCart->date['order_confirmed'] : '-' ?>
                    </td>
                </tr>

                <!-- arrived -->
                <tr class="border">
                    <td class="border"> Arrived </td>
                    <td class="border">
                        <?= $tempCart->date['order_arrived'] != '' ? $tempCart->date['order_arrived'] : '-' ?>
                    </td>
                </tr>

                <!-- packed -->
                <tr class="border">
                    <td class="border"> Packed </td>
                    <td class="border"> <?= $tempCart->date['order_packed'] != '' ? $tempCart->date['order_packed'] : '-' ?>
                    </td>
                </tr>

                <?php
                if ($tempCart->checkoutOption != 'click-and-collect') {
                    ?>
                    <!-- shipped -->
                    <tr class="border">
                        <td class="border"> Shipped </td>
                        <td class="border">
                            <?= $tempCart->date['order_shipped'] != '' ? $tempCart->date['order_shipped'] : '-' ?>
                        </td>
                    </tr>

                    <!-- delivered -->
                    <tr class="border">
                        <td class="border"> Delivered </td>
                        <td class="border">
                            <?= $tempCart->date['order_delivered'] != '' ? $tempCart->date['order_delivered'] : '-' ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <!-- completed -->
                <tr class="border">
                    <td class="border"> Completed </td>
                    <td class="border">
                        <?= $tempCart->date['order_completed'] != '' ? $tempCart->date['order_completed'] : '-' ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- books -->
    <p class="fw-bold mt-5 fs-3"> Books </p>
    <div class="table-container">
        <table class="table border table-hover">
            <thead>
                <tr>
                    <th scope="col" class="border"> SN </th>
                    <th scope="col" class="border"> Title </th>
                    <th scope="col" class="border"> Owner </th>
                    <th scope="col" class="border"> Price </th>
                    <th scope="col" class="border"> Return </th>
                    <th scope="col" class="border"> Comission </th>
                    <th scope="col" class="border"> Arrival Date </th>
                </tr>
            </thead>

            <tbody>
                <?php
                $totalPrice = 0;
                $totalComission = 0;
                $totalReturn = 0;
                $serial = 1;
                foreach ($tempCart->bookList as $book) {
                    $tempBook->fetch($book['id']);

                    // title
                    $title = ucwords($tempBook->title);

                    // price
                    $price = ucwords($tempBook->priceOffer);

                    $comission = 0.15 * $price;

                    $totalPrice += $price;

                    $totalComission += $comission;

                    $return = $price - $comission;

                    $totalReturn += $return;

                    // arrival date
                    $arrivalDate = $book['arrived_date'] != '' ? $book['arrived_date'] : '-';

                    // owner
                    $tempUser->fetch($tempBook->getOwnerId());

                    $ownerName = $tempUser->getFullName();
                    ?>
                    <tr>
                        <th scope="row" class="border"> <?= $serial++ ?> </th>
                        <td class="border"> <?= $title ?> </td>
                        <td class="border">
                            <a href="/bookrack/admin/admin-user-details/<?= $tempBook->getOwnerId() ?>" class="text-primary">
                                <?= $ownerName ?>
                        </td>
                        </a>
                        <td class="border text-success fw-semibold"> <?= "NPR. " . number_format($price, 2) ?> </td>
                        <td class="border text-success fw-semibold"> <?= "NPR. " . number_format($return, 2) ?> </td>
                        <td class="border text-success fw-semibold"> <?= "NPR. " . number_format($comission, 2) ?> </td>
                        <td class="border"> <?= $arrivalDate ?> </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>

            <tfoot>
                <tr>
                    <th scope="row" colspan="2"> </th>
                    <th scope="row" class="border"> Total </th>
                    <td class="border text-success fw-semibold"> <?= "NPR. " . number_format($totalPrice, 2) ?> </td>
                    <td class="border text-success fw-semibold"> <?= "NPR. " . number_format($totalReturn, 2) ?> </td>
                    <td class="border text-success fw-semibold"> <?= "NPR. " . number_format($totalComission, 2) ?> </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- order not arrived modal -->
    <div class="modal fade" id="order-not-arrived-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3 d-flex flex-column align-items-center">
                <p class="fs-3 fw-semibold"> All books has not arrived yet! </p>
                <img src="/bookrack/assets/icons/arrived.png" alt="" style="width:30%;" class="mb-3">
            </div>
        </div>
    </div>
    <?php
}
