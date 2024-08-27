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
$user->fetch($userId);
$bookObj = new Book();
$cart = new Cart();

$userId = $_SESSION['bookrack-user-id'];
$cart->setUserId($userId);
$cart->fetchCurrent();
$total = 0.0;
?>

<div class="rounded p-1 cart-detail">
    <table class="table cart-table" id="current-cart-table">
        <thead>
            <tr>
                <th scope="col">S.N.</th>
                <th scope="col">Book</th>
                <th scope="col">Detail</th>
                <th scope="col">Availability</th>
                <th scope="col">Price</th>
                <th scope="col">Remove</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $serial = 1;
            $allBooksAvailable = true;
            if (sizeof($cart->bookList) != 0) {
                foreach ($cart->bookList as $book) {
                    // fetch book details
                    $bookObj->fetch($book['id']);
                    $bookObj->setPhotoUrl();
                    $available = $bookObj->flag == "verified" ? true : false;

                    if (!$available)
                        $allBooksAvailable = false;
                    ?>
                    <tr class="current-cart-tr">
                        <th scope="row"> <?= $serial++ ?> </th>
                        <td>
                            <div class="book-image">
                                <img src="<?= $bookObj->photoUrl ?>" alt="" loading="lazy">
                            </div>
                        </td>
                        <td class="title">
                            <a href="/bookrack/book-details/<?= $book['id'] ?>">
                                <?= ucwords($bookObj->title) ?>
                            </a>
                        </td>

                        <td class="availability <?= $available ? '' : 'text-danger' ?>">
                            <?= $available ? "Available" : "Not-available" ?>
                        </td>

                        <td class="price">
                            <?php
                            echo "NPR." . number_format($bookObj->priceOffer, 2);
                            $total += $bookObj->priceOffer;
                            ?>
                        </td>

                        <td class="remove">
                            <i class="fa-solid fa-trash pointer current-cart-remove-icon" data-book-id="<?= $book['id'] ?>"
                                data-price="<?= $bookObj->priceOffer ?>"></i>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td rowspan="2" colspan="8" class="text-danger pt-4" style="text-align:center"> Your current cart is
                        empty!
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

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
                <p class="m-0" id="current-cart-total"> <?= "NPR. " . number_format($total, 2) ?> </p>
            </div>
        </div>

        <div class="checkout-btn-div">
            <?php
            if ($allBooksAvailable) {
                if ($user->accountStatus == 'verified') {
                    ?>
                    <button type="submit" class="btn w-100 text-light py-2 checkout-btn" id="checkout-btn" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        CHECKOUT NOW
                    </button>
                    <?php
                } else {
                    ?>
                    <p class="m-0 text-light mb-3"> Update your details first to proceed. </p>
                    <button type="submit" class="btn w-100 text-secondary py-2 checkout-btn">
                        CHECKOUT NOW
                    </button>
                    <?php
                }
            } else {
                ?>
                <p class="m-0 text-light mb-3"> Some books are not available. Remove them from cart then try checking out. </p>
                <button type="submit" class="btn w-100 text-secondary py-2 checkout-btn">
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