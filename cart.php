<?php

if (!isset($tab)) {
    $tab = "current";
    header("Location: /bookrack/cart/current");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Cart </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/Assets/Brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.min.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/Assets/css/navbar.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/header.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/footer.css">
    <link rel="stylesheet" href="/bookrack/Assets/css/cart.css">
</head>

<body>
    <!-- header -->
    <?php include 'header.php'; ?>

    <!-- main -->
    <main class="d-flex flex-column gap-4 container main">
        <!-- heading section -->
        <section class="d-flex flex-row align-items-center gap-3 heading">
            <i class="fa fa-shopping-cart fs-5"></i>
            <h4 class="f-reset fw-bold"> MY CART </h4>
        </section>

        <div class="d-flex flex-row gap-2 cart-status-container">
            <button class="btn active-cart" id="current-cart-btn"
                onclick="window.location.href='/bookrack/cart/current'"> Current Cart </button>
            <button class="btn inactive-cart" id="pending-cart-btn"
                onclick="window.location.href='/bookrack/cart/pending'"> Pending Cart </button>
            <button class="btn inactive-cart" id="completed-cart-btn"
                onclick="window.location.href='/bookrack/cart/completed'"> Completed Cart </button>
        </div>

        <!-- cart section -->
        <!-- pending cart section -->
        <section class="<?php if ($tab != "current") {
            echo "d-none";
        } ?> pending-cart-status-container">
            <h5 class="f-reset text-secondary"> Order Status </h5>

            <!-- book details -->
            <div class="d-flex flex-row flex-wrap mt-3 gap-5 mb-2 pending-cart-status-div">
                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image" src="/bookrack/assets/Icons/order-received.png"
                            alt="">
                    </div>
                    <p class="f-reset"> Order Received </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image"
                            src="/bookrack/assets/Icons/order-confirmed.png" alt="">
                    </div>
                    <p class="f-reset"> Order Confirmed </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image" src="/bookrack/assets/Icons/order-picked.png"
                            alt="">
                    </div>
                    <p class="f-reset"> Picked </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image" src="/bookrack/assets/Icons/order-arrived.png"
                            alt="">
                    </div>
                    <p class="f-reset"> Arrived </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image completed-status-image" src="/bookrack/assets/Icons/order-packed.png"
                            alt="">
                    </div>
                    <p class="f-reset"> Packed </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image" src="/bookrack/assets/Icons/order-shipped.png" alt="">
                    </div>
                    <p class="f-reset"> Shipped </p>
                </div>


                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image" src="/bookrack/assets/Icons/order-Delivered.png" alt="">
                    </div>
                    <p class="f-reset"> Delivered </p>
                </div>

                <div class="pending-cart-status">
                    <div class="status-image-div">
                        <img class="status-image" src="/bookrack/assets/Icons/order-Completed.png" alt="">
                    </div>
                    <p class="f-reset"> Completed </p>
                </div>
            </div>

            <!-- checkout details -->
            <section class="d-flex flex-column-reverse flex-lg-row mt-3 justify-content-between current-cart-section">
                <div class="rounded p-1 cart-detail">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">S.N.</th>
                                <th scope="col">Book</th>
                                <th scope="col">Title</th>
                                <th scope="col">Condition</th>
                                <th scope="col">Purpose</th>
                                <th scope="col">Starting date </th>
                                <th scope="col">Ending date</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- book 1 -->
                            <tr>
                                <th scope="row">1</th>
                                <td>
                                    <div class="book-image">
                                        <img src="/bookrack/assets/Images/cover-1.jpeg" alt="">
                                    </div>
                                </td>
                                <td class="title" onclick="window.location.href='/bookrack/book-details'"> The Black
                                    Universe
                                </td>
                                <td> unused </td>
                                <td> Rent </td>
                                <td> 0000-00-00 </td>
                                <td> 0000-00-00 </td>
                                <td class="price"> NRs. 140 </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column rounded p-3 gap-3 checkout">
                    <div class="heading">
                        <p class="f-reset"> ORDER SUMMARY </p>
                    </div>

                    <div class="d-flex flex-column gap-2 checkout-detail-div">
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="f-reset"> Order No. </p>
                            <p class="f-reset"> #145-4526-7894 </p>
                        </div>
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="f-reset"> Order Date </p>
                            <p class="f-reset"> 2024-12-08 </p>
                        </div>
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="f-reset"> Shipping address </p>
                            <p class="f-reset"> Bansbari, Kathmandu </p>
                        </div>
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="f-reset"> Est. arrival day </p>
                            <p class="f-reset"> 4 days </p>
                        </div>
                        <div class="d-flex flex-row justify-content-between checkout-detail">
                            <p class="f-reset"> Remaining day </p>
                            <p class="f-reset"> 2 </p>
                        </div>
                        <hr>
                    </div>

                    <div class="d-flex flex-column gap-1 checkout-detail-div">
                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="f-reset"> Subtotal </p>
                            <p class="f-reset"> NRs. 150 </p>
                        </div>

                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="f-reset"> Estimated Shipping </p>
                            <p class="f-reset"> NRs. 75 </p>
                        </div>

                        <div class="d-flex flex-row justify-content-between  checkout-detail">
                            <p class="f-reset"> Estimated Total </p>
                            <p class="f-reset"> NRs. 225 </p>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <!-- pending cart section -->
        <section
            class="<?php if ($tab != "pending") {
                echo "d-none";
            } ?> d-flex flex-column-reverse flex-lg-row justify-content-between current-cart-section">
            <!-- cart details -->
            <div class="rounded p-1 cart-detail">
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th scope="col">S.N.</th>
                            <th scope="col">Book</th>
                            <th scope="col">Title</th>
                            <th scope="col">Condition</th>
                            <th scope="col">Purpose</th>
                            <th scope="col">Starting date </th>
                            <th scope="col">Ending date</th>
                            <th scope="col">Price</th>
                            <th scope="col">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- book 1 -->
                        <tr>
                            <th scope="row">1</th>
                            <td>
                                <div class="book-image">
                                    <img src="/bookrack/assets/Images/cover-1.jpeg" alt="">
                                </div>
                            </td>
                            <td class="title" onclick="window.location.href='/bookrack/book-details'"> The Black
                                Universe
                            </td>
                            <td> unused </td>
                            <td> Rent </td>
                            <td> 0000-00-00 </td>
                            <td> 0000-00-00 </td>
                            <td class="price"> NRs. 140 </td>
                            <td>
                                <div class="remove" id="current-cart-product-1">
                                    <i class="fa fa-multiply"></i>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- checkut details -->
            <div class="d-flex flex-column rounded p-3 gap-3 checkout">
                <div class="heading">
                    <p class="f-reset"> ORDER SUMMARY </p>
                </div>

                <div class="d-flex flex-column gap-1 checkout-detail-div">
                    <div class="d-flex flex-row justify-content-between checkout-detail">
                        <p class="f-reset"> Shipping address </p>
                        <p class="f-reset"> Bansbari, Kathmandu </p>
                    </div>
                    <div class="d-flex flex-row justify-content-between checkout-detail">
                        <p class="f-reset"> Est. arrival day </p>
                        <p class="f-reset"> 4 days </p>
                    </div>
                    <hr>
                </div>

                <div class="d-flex flex-column gap-1 checkout-detail-div">
                    <div class="d-flex flex-row justify-content-between  checkout-detail">
                        <p class="f-reset"> Subtotal </p>
                        <p class="f-reset"> NRs. 150 </p>
                    </div>

                    <div class="d-flex flex-row justify-content-between  checkout-detail">
                        <p class="f-reset"> Estimated Shipping </p>
                        <p class="f-reset"> NRs. 75 </p>
                    </div>

                    <div class="d-flex flex-row justify-content-between  checkout-detail">
                        <p class="f-reset"> Estimated Total </p>
                        <p class="f-reset"> NRs. 225 </p>
                    </div>
                </div>

                <div class="checkout-btn-div">
                    <button class="btn w-100 text-light py-2 checkout-btn">
                        CHECKOUT NOW
                    </button>
                </div>

                <div class="payment-partner-div">
                    <div class="d-flex flex-row justify-content-between align-items-center payment-partner">
                        <p class="f-reset small"> Our payment partner </p>
                        <img src="/bookrack/assets/Icons/esewa-logo.webp" alt="">
                    </div>
                </div>
            </div>
        </section>

        <!-- completed cart -->
        <section
            class="<?php if ($tab != "completed") {
                echo "d-none";
            } ?> d-flex flex-column-reverse flex-lg-row justify-content-between current-cart-section">
            <div class="rounded p-1 cart-detail">
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th scope="col">S.N.</th>
                            <th scope="col">Book</th>
                            <th scope="col">Title</th>
                            <th scope="col">Condition</th>
                            <th scope="col">Purpose</th>
                            <th scope="col">Starting date </th>
                            <th scope="col">Ending date</th>
                            <th scope="col">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- book 1 -->
                        <tr>
                            <th scope="row">1</th>
                            <td>
                                <div class="book-image">
                                    <img src="/bookrack/assets/Images/cover-1.jpeg" alt="">
                                </div>
                            </td>
                            <td class="title cursor" onclick="window.location.href='/bookrack/book-details'"> The Black
                                Universe </td>
                            <td> unused </td>
                            <td> Rent </td>
                            <td> 0000-00-00 </td>
                            <td> 0000-00-00 </td>
                            <td class="price"> NRs. 140 </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column rounded p-3 gap-3 checkout">
                <div class="heading">
                    <p class="f-reset"> ORDER SUMMARY </p>
                </div>

                <div class="d-flex flex-column gap-1 checkout-detail-div">
                    <div class="d-flex flex-row justify-content-between checkout-detail">
                        <p class="f-reset"> Shipping address </p>
                        <p class="f-reset"> Bansbari, Kathmandu </p>
                    </div>
                    <div class="d-flex flex-row justify-content-between checkout-detail">
                        <p class="f-reset"> Arrival day </p>
                        <p class="f-reset"> 4 days </p>
                    </div>
                    <hr>
                </div>

                <div class="d-flex flex-column gap-1 checkout-detail-div">
                    <div class="d-flex flex-row justify-content-between  checkout-detail">
                        <p class="f-reset"> Subtotal </p>
                        <p class="f-reset"> NRs. 150 </p>
                    </div>

                    <div class="d-flex flex-row justify-content-between  checkout-detail">
                        <p class="f-reset"> Shipping </p>
                        <p class="f-reset"> NRs. 75 </p>
                    </div>

                    <div class="d-flex flex-row justify-content-between  checkout-detail">
                        <p class="f-reset"> Total </p>
                        <p class="f-reset"> NRs. 225 </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- modal -->

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- js :: current file -->
    <script></script>
</body>

</html>