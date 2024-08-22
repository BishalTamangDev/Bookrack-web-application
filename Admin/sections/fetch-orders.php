<?php
require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/user.php';

$tempUser = new User();
$tempCart = new Cart();

$cartIdList = $tempCart->fetchAllCartIdExceptCurrent();

if (sizeof($cartIdList) == 0) {
    ?>
    <tr class="order-status-completed">
        <td colspan="7" class="text-danger"> No orders found! </td>
    </tr>
    <?php
} else {
    $serial = 1;
    foreach ($cartIdList as $cartId) {
        // fetch cart
        $tempCart->fetch($cartId);

        // fetch user detail
        $tempUser->fetch($tempCart->userId());

        $userName = $tempUser->getFullName();
        ?>
        <tr class="order-status-completed">
            <td> <?= $serial++ ?> </td>
            <td>
                <a href="/bookrack/admin/admin-order-summary/<?= $cartId ?>" class="text-primary small">
                    <?= $cartId ?>
                </a>
            </td>
            <td>
                <a href="/bookrack/admin/admin-user-details/<?= $tempCart->userId() ?>" class="text-primary">
                    <?= $userName ?>
                </a>
            </td>
            <td> <?= $tempCart->date['order_placed'] ?> </td>
            <td> <?= $tempCart->date['order_completed'] != "" ? $tempCart->date['order_completed'] : '-' ?> </td>
            <td> <?= ucwords($tempCart->status) ?> </td>
            <td>

            </td>
        </tr>
        <?php
    }
}
?>