<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Book Details </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/nav.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/book-detail.css">
</head>

<body>
    <!-- aside :: nav -->
    <aside class="aside"> </aside>

    <!-- main content -->
    <main class="main">
        <!-- heading & rating -->
        <section class="d-flex flex-column heading-rating">
            <!-- heading -->
            <h1 class="fw-bold"> The Tales of Two Cities </h1>

            <!-- rating -->
            <div class="d-flex flex-row align-items-center gap-2 rating-count">
                <div class="d-flex flex-row align-items-center gap-1 rating">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon">
                    <img src="/bookrack/assets/icons/full-rating.png" alt="rating-star" class="rating-icon">
                    <img src="/bookrack/assets/icons/half-rating.png" alt="rating-star" class="rating-icon">
                </div>

                <div class="count">
                    <p class="f-reset"> (89) </p>
                </div>
            </div>
        </section>

        <!-- book details -->
        <section class="d-flex flex-column flex-lg-row gap-4 section book-detail-container">
            <!-- book photos -->
            <div class="d-flex flex-row flex-lg-column gap-2 book-photo-div">
                <div class="d-flex flex-row top">
                    <img src="/bookrack/assets/images/cover-1.jpeg" alt="">
                </div>

                <div class="d-flex flex-column flex-lg-row gap-2 bottom">
                    <img src="/bookrack/assets/images/cover-2.png" alt="">
                    <img src="/bookrack/assets/images/cover-3.jpg" alt="">
                    <img src="/bookrack/assets/images/isbn-1.jpg" alt="">
                </div>
            </div>

            <!-- book all details -->
            <div class="d-flex flex-column flex-lg-row gap-3 book-detail">
                <!-- book details -->
                <div class="d-flex flex-column gap-4 book-core-detail">
                    <!-- description -->
                    <div class="d-flex flex-column desciption-div">
                        <p class="f-reset fw-bold fs-5"> Description </p>
                        <p class="f-reset fs-6">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat distinctio veritatis qui
                            voluptatum cumque quo recusandae et sapiente vel laudantium dolorum, asperiores ut
                            illum! Adipisci commodi voluptatum id maiores natus doloremque incidunt est eum, placeat
                            debitis accusantium, sint sapiente. Tempore quidem doloribus iure nihil autem, nulla,
                            laborum excepturi dicta molestias in consequuntur reprehenderit placeat quaerat aut
                            distinctio est voluptatibus necessitatibus numquam voluptate veniam iste. Suscipit fugit
                            voluptatibus similique veritatis ab doloribus nostrum, eligendi magni perspiciatis sunt
                            sit velit? Deleniti in perspiciatis est culpa soluta voluptates dolores quis asperiores
                            cumque, vitae ea tenetur laborum voluptate voluptas, fugiat odit! Illo laudantium est
                            explicabo, velit rerum porro deleniti odio incidunt impedit fugiat, quaerat quidem nam
                            quas ducimus dignissimos cupiditate eveniet. Laboriosam repudiandae commodi harum
                            repellendus facilis dolor corporis saepe vel ducimus neque nisi, ratione nemo sint
                            dolore officiis ipsum nihil optio eaque reprehenderit ipsa beatae explicabo a voluptatum
                            dolorum. Ratione cupiditate culpa temporibus.
                        </p>
                    </div>

                    <!-- genre -->
                    <div class="d-flex flex-column gap-2 genre-div">
                        <p class="f-reset fw-bold fs-5"> Genre </p>

                        <div class="d-flex flex-row gap-2 genre-list">
                            <div class="genre">
                                <p class="f-reset"> Science-Fiction </p>
                            </div>

                            <div class="genre">
                                <p class="f-reset"> Thriller </p>
                            </div>
                        </div>
                    </div>

                    <!-- author -->
                    <div class="d-flex flex-column gap-2 author-div">
                        <p class="f-reset fw-bold fs-5"> Author[s] </p>

                        <div class="d-flex flex-row gap-2 author-list">
                            <div class="author">
                                <p class="f-reset"> Bishal Tamang </p>
                            </div>

                            <div class="author">
                                <p class="f-reset"> Rupak Dangi </p>
                            </div>

                            <div class="author">
                                <p class="f-reset"> Shristi Pradhan </p>
                            </div>
                        </div>
                    </div>

                    <!-- edition -->
                    <div class="d-flex flex-row gap-3 align-items-center edition-div">
                        <p class="f-reset fw-bold fs-5"> Edition </p>
                        <p class="f-reset fs-6"> 5 <sup>th</sup> </p>
                    </div>

                    <!-- publisher -->
                    <div class="d-flex flex-row gap-3 align-items-center edition-div">
                        <p class="f-reset fw-bold fs-5"> Publisher </p>
                        <p class="f-reset fs-6"> Gibraltar's Mediterranean Restaurant </p>
                    </div>

                    <!-- publication-date -->
                    <div class="d-flex flex-row d-none gap-3 edition-div">
                        <p class="f-reset fw-bold fs-5"> Publication Date </p>
                        <p class="f-reset fs-6"> May 9, 2017 </p>
                    </div>
                </div>

                <!-- book surface details -->
                <div class="d-flex flex-column book-surface-detail">
                    <!-- top section - book availability for rent -->
                    <div class="d-flex flex-row book-availability py-2">
                        <p class="f-reset text-light m-auto fs-6"> Available for Rent </p>
                    </div>

                    <div class="d-flex flex-column gap-3 px-3 pt-3 surface-bottom">
                        <!-- book id -->
                        <p class="f-reset fw-bold"> #8457145236 </p>

                        <!-- language -->
                        <div class="d-flex flex-row language">
                            <div class="left">
                                <p class="f-reset fw-bold"> Language </p>
                            </div>

                            <div class="right">
                                <p class="f-reset fw-bold"> English </p>
                            </div>
                        </div>

                        <!-- price -->
                        <div class="d-flex flex-row price">
                            <div class="left">
                                <p class="f-reset fw-bold"> Price </p>
                            </div>

                            <div class="right">
                                <p class="f-reset fw-bold text-success"> NRs. 170.00 </p>
                            </div>
                        </div>

                        <!-- owner -->
                        <div class="d-flex flex-row owner">
                            <div class="left">
                                <p class="f-reset fw-bold"> Owner </p>
                            </div>

                            <div class="right">
                                <abbr title="Show owner details">
                                    <p class="f-reset fw-bold pointer"> Rupak Dangi </p>
                                </abbr>
                            </div>
                        </div>
                    </div>

                    <!-- operation: edit/ remove -->
                    <div class="d-flex flex-row justify-content-between mt-2 gap-2 align-items-center book-operation">
                        <!-- delete operation -->
                        <div class="d-flex flex-row gap-2 align-items-center delete">
                            <i class="fa fa-trash"></i>
                            <p class="f-reset fs-6"> Delete </p>
                        </div>

                        <!-- editi operation -->
                        <div class="d-flex flex-row gap-2 align-items-center edit">
                            <i class="fa fa-edit"></i>
                            <p class="f-reset fs-6"> Edit </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- book rent history -->
        <section class="section book-rent-history">
            <p class="f-reset fw-bold fs-5 text-danger"> Book Rent History </p>

            <table class="table mt-2 book-rent-history-table">
                <!-- header -->
                <thead>
                    <tr>
                        <th scope="col"> S.N. </th>
                        <th scope="col"> Rented By </th>
                        <th scope="col"> Issued Date </th>
                        <th scope="col"> Returned Date </th>
                        <th scope="col"> Fine </th>
                        <th scope="col"> Rent Status </th>
                    </tr>
                </thead>

                <!-- body -->
                <tbody>
                    <!-- dummy data -->
                    <tr class="book-row on-rent-row on-stock-row">
                        <td> 1. </td>
                        <td> Rupak Dangi </td>
                        <td> 2222-22-22 </td>
                        <td> 2222-22-33 </td>
                        <td> NRs. 120 </td>
                        <td> Completed </td>
                    </tr>

                    <tr class="book-row on-rent-row on-stock-row">
                        <td> 2. </td>
                        <td> Shristi Pradhan </td>
                        <td> 3333-33-33 </td>
                        <td> - </td>
                        <td> - </td>
                        <td> Active </td>
                    </tr>
                </tbody>

                <!-- footer -->
                <tfoot id="table-foot">
                    <tr>
                        <td colspan="9"> No book rent history found! </td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </main>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"> </script>

    <!-- local bootstrap js -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.js"> </script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap local file :: backup-->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>
</body>

</html>