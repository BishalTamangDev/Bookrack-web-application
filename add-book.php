<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Add new book </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.min.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/navbar.css">
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/header.css">
    <link rel="stylesheet" href="/bookrack/assets/css/footer.css">
    <link rel="stylesheet" href="/bookrack/assets/css/add-book.css">
</head>

<body>
    <!-- header -->
    <?php
    include 'header.php';
    ?>

    <!-- main -->
    <main class="d-flex flex-column gap-3 container main pb-5">
        <section class="d-flex flex-column gap-5 gap-md-4 section add-book-section">
            <!-- heading -->
            <div class="d-flex flex-row justify-content-between align-items-center">
                <p class="f-reset fs-3 fw-semibold text-secondary"> Add New Book </p>
                <button class="btn btn-danger" onclick="window.location.href='/bookrack/add-book'"> Reset </button>
            </div>

            <div class="alert alert-danger" role="alert">
                Error message appears here...
            </div>

            <!-- add book form -->
            <form action="" method="POST"
                class="d-flex flex-column flex-md-row justify-content-between gap-3 gap-md-4 add-book-form">
                <div class="d-flex flex-column gap-3 left rounded p-0 p-md-3 book-details">
                    <!-- title -->
                    <div class="d-flex flex-column gap-2 title">
                        <label for="book-title" class="f-reset form-label"> Title </label>
                        <input type="text" class="form-control-lg" id="book-title" name="book-title"
                            aria-describedby="book title" required>
                    </div>

                    <!-- description -->
                    <div class="d-flex flex-column gap-2 description">
                        <label for="book-description" class="f-reset form-label"> Description </label>
                        <textarea class="form-control-lg" placeholder="" id="book-description" name="book-description"
                            required></textarea>
                    </div>

                    <!-- ISBN -->
                    <div class="d-flex flex-column gap-2 isbn">
                        <label for="book-isbn" class="f-reset form-label"> ISBN </label>
                        <input type="text" class="form-control-lg" id="book-isbn" name="book-isbn"
                            aria-describedby="book isbn" required>
                    </div>

                    <!-- publisher & publication -->
                    <div class="d-flex flex-column flex-md-row gap-3 publisher-publication">
                        <!-- publisher -->
                        <div class="d-flex flex-column gap-2 publisher ">
                            <label for="book-publisher" class="f-reset form-label"> Publisher </label>
                            <input type="text" class="form-control-lg" id="book-publisher" name="book-publisher"
                                aria-describedby="publisher" required>
                        </div>

                        <!-- publication -->
                        <div class="d-flex flex-column gap-2 publication ">
                            <label for="book-publication" class="f-reset form-label"> Publication </label>
                            <input type="text" class="form-control-lg" id="book-publication" name="book-publication"
                                aria-describedby="publication" required>
                        </div>
                    </div>

                    <!-- genre & edition -->
                    <div class="d-flex flex-column flex-md-row gap-2 d-flex flex-row gap-3 genre-edition">
                        <!-- genre -->
                        <div class="d-flex flex-column gap-2 genre ">
                            <label for="book-genre" class="f-reset f-reset form-label"> Genre </label>
                            <input type="text" class="form-control-lg" id="book-genre" name="book-genre"
                                aria-describedby="book genre" required>
                        </div>

                        <!-- language -->
                        <div class="d-flex flex-column gap-2 language ">
                            <label for="book-language" class="f-reset f-reset form-label"> language </label>
                            <select class="form-select form-select-lg" id="book-language" name="book-language"
                                aria-label="book language" required>
                                <option selected hidden value=""> Select the language </option>
                                <option value="1"> Language 1 </option>
                                <option value="2"> Language 2 </option>
                                <option value="3"> Language 3 </option>
                                <option value="4"> Language 4 </option>
                                <option value="5"> Language 5 </option>
                            </select>
                        </div>

                        <!-- edition -->
                        <div class="d-flex flex-column gap-2 edition ">
                            <label for="book-edition" class="f-reset f-reset form-label"> Edition </label>
                            <select class="form-select form-select-lg" id="book-edition" name="book-edition"
                                aria-label="book edition" required>
                                <option selected hidden value=""> Select the edition </option>
                                <option value="1"> 1st </option>
                                <option value="2"> 2nd </option>
                                <option value="3"> 3rd </option>
                                <option value="4"> 4th </option>
                                <option value="5"> 5th </option>
                                <option value="6"> 6th </option>
                                <option value="7"> 7th </option>
                            </select>
                        </div>
                    </div>

                    <!-- actual price & offer price -->
                    <div class="d-flex flex-column flex-md-row gap-3 price">
                        <div class="d-flex flex-column gap-2 actual-price ">
                            <label for="book-actual-price" class="f-reset form-label"> Actual price </label>
                            <input type="text" class="form-control-lg" id="book-actual-price" name="book-actual-price"
                                aria-describedby="actual price" placeholder="" required>
                        </div>

                        <div class="d-flex flex-column gap-2 actual-price ">
                            <label for="book-offer-price" class="f-reset form-label"> Offer price </label>
                            <input type="text" class="form-control-lg" id="book-offer-price" name="book-offer-price"
                                aria-describedby="offer price" placeholder="" required>
                        </div>
                    </div>
                </div>

                <!-- book photos -->
                <div class="d-flex flex-column rounded p-0 p-md-3 gap-3 right">
                    <div class="heading">
                        <p class="f-reset fw-semibold fs-5 text-secondary"> Images </p>
                        <hr>
                    </div>

                    <div class="d-flex flex-column gap-3 book-images">
                        <div class="input-group">
                            <label for="cover-page">Cover page</label>
                            <input type="file" name="cover-page" id="cover-page" class="form-control"
                                aria-label="cover page image" aria-describedby="cover page image" required>
                        </div>

                        <div class="input-group">
                            <label for="price-page">Price page</label>
                            <input type="file" name="price-page" id="cover-page" class="form-control"
                                aria-label="price page image" aria-describedby="price page image" required>
                        </div>

                        <div class="input-group">
                            <label for="isbn-page">ISBN page</label>
                            <input type="file" name="isbn-page" id="isbn-page" class="form-control"
                                aria-label="isbn page image" aria-describedby="isbn page image" required>
                        </div>
                    </div>

                    <div class="mt-1 operation">
                        <button type="submit" class="btn p-2"> Add Now </button>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- js :: current file -->
    <script> </script>
</body>

</html>