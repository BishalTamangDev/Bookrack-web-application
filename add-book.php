<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/home");
}

require_once __DIR__ . '/../bookrack/app/user-class.php';
require_once __DIR__ . '/../bookrack/app/functions.php';

$profileUser = new User();

// set user id
$profileUser->setUserId($_SESSION['bookrack-user-id']);

// get user details
$userFound = $profileUser->fetch($profileUser->getUserId());

if (!$userFound) {
    header("Location: /bookrack/signout");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> <?php echo ($task == "add") ? "Add Book" : "Edit Book" ?> </title>

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
        <section class="d-flex flex-column gap-4 section add-book-section">
            <!-- alert for unverified account -->
            <?php
            if ($profileUser->getAccountStatus() != "verified") {
                ?>
                <div class="alert alert-danger m-0" role="alert">
                    Your account is not verified yet in order to add book. Try updating your details inclusing documents.
                </div>
                <?php
            }
            ?>

            <!-- heading -->
            <div class="d-flex flex-row justify-content-between align-items-center">
                <p class="m-0 fs-3 fw-semibold text-secondary">
                    <?php echo ($task == "add") ? "Add Book" : "Edit Book" ?>
                </p>
                <div class="d-flex flex-row flex-wrap gap-2 action">
                    <button class="btn btn-danger" onclick="window.location.href='/bookrack/add-book/<?= $task; ?>'">
                        Reset </button>
                </div>
            </div>

            <!-- status message -->
            <?php
            if (isset($_SESSION['status'])) {
                ?>
                <p class="m-0 <?= $_SESSION['status'] ? 'text-success' : 'text-danger' ?>">
                    <?= $_SESSION['status-message'] ?>
                </p>
                <?php
            }
            ?>

            <!-- add book form -->
            <form action="/bookrack/app/add-book-code.php" method="POST"
                class="d-flex flex-column flex-md-row justify-content-between gap-3 gap-md-4 add-book-form"
                enctype="multipart/form-data" autocomplete="off">
                <div class="d-flex flex-column gap-3 left rounded p-0 p-md-3 book-details">
                    <!-- title -->
                    <div class="d-flex flex-column gap-2 title">
                        <label for="book-title" class="m-0 form-label"> Title </label>
                        <input type="text" class="form-control" id="book-title" name="book-title"
                            aria-describedby="book title" required>
                    </div>

                    <!-- description -->
                    <div class="d-flex flex-column gap-2 description">
                        <label for="book-description" class="m-0 form-label"> Description </label>
                        <textarea class="form-control" placeholder="" id="book-description" name="book-description"
                            minlength="20" required></textarea>
                    </div>

                    <!-- authors -->
                    <div class="d-flex flex-column gap-3 author">
                        <!-- top -->
                        <div class="d-flex flex-row justify-content-between align-items-center author-top">
                            <label for="book-author" class="m-0 form-label"> Authors </label>

                            <div class="d-flex flex-row gap-2 btn btn-secondary align-items-center add-another-div"
                                id="add-author-field">
                                <i class="fa fa-add"></i>
                                <p class="small m-0 small text-white"> Add more authors </p>
                            </div>
                        </div>

                        <!-- author inputs -->
                        <div class="d-flex flex-column gap-3 author-field-container" id="author-field-container">
                            <!-- author field 1 -->
                            <div class="d-flex flex-row align-items-center gap-2 author-div"
                                id="author-field-container-1">
                                <input type="text" name="book-author[]" class="form-control" id="book-author-1"
                                    placeholder="Enter author name" required>

                                <!-- delete author -->
                                <!-- <a class="btn btn-danger delete-author" id="delete-author-1" onclick="removeAuthorContainer('author-field-container-1')">
                                        <i class="fa fa-trash"></i>
                                    </a> -->
                            </div>
                        </div>
                    </div>

                    <!-- genre -->
                    <div class="d-flex flex-column w-100 gap-3 genre">
                        <!-- top -->
                        <div class="d-flex flex-row justify-content-between align-items-center genre-top">
                            <label for="book-genre" class="m-0 form-label"> Genre </label>

                            <!-- actions -->
                            <div class="d-flex flex-row gap-2 action">
                                <!-- reset -->
                                <abbr title="Reset genre">
                                    <p class="m-0 btn pointer" id="genre-reset">
                                        <i class="fa fa-undo"></i>
                                    </p>
                                </abbr>

                                <!-- show genre list -->
                                <div class="d-flex flex-row gap-2 btn btn-secondary align-items-center"
                                    id="genre-trigger">
                                    <i class="fa-solid fa-circle-chevron-down" id="genre-arrow-indicator"></i>
                                    <p class="m-0 small text-white"> Genre list </p>
                                </div>
                            </div>
                        </div>

                        <!-- genre label-->
                        <div class="bottom">
                            <input type="text" class="form-control" name="book-genre-label" itemid="book genre label"
                                placeholder="Choose atleast one genre" value="" id="book-genre-label" required>
                        </div>

                        <div class="gap-1 rounded px-2 genre-container">
                            <?php
                            $count = 0;
                            foreach ($genreArray as $genre) {
                                $count++;
                                $id = "genre-$count";
                                ?>
                                <div class="gap-2 form-check">
                                    <input class="form-check-input genre-option" type="checkbox" name="book-genre-array[]"
                                        value="<?php echo $genre; ?>" id="<?php echo $id; ?>">
                                    <label class="form-check-label" for="<?php echo $id; ?>"> <?= $genre ?> </label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- isbn && purpose -->
                    <div class="d-flex flex-row w-100 gap-3 purpose-isbn">
                        <!-- isbn -->
                        <div class="d-flex flex-column w-100 w-md-50 gap-2 isbn">
                            <label for="book-isbn" class="m-0 form-label"> ISBN </label>
                            <input type="text" class="form-control" id="book-isbn" name="book-isbn"
                                aria-describedby="book isbn" required>
                        </div>

                        <!-- purpose -->
                        <div class="d-flex flex-column gap-2 w-100 w-md-50 purpose ">
                            <label for="book-purpose" class="m-0 form-label"> Purpose </label>
                            <select class="form-select form-select" id="book-purpose" name="book-purpose"
                                aria-label="book purpose" required>
                                <option value="" selected hidden> Select the purpose </option>
                                <option value="renting"> Renting </option>
                                <option value="buy/sell"> Buy/Sell </option>
                                <option value="giveaway"> Giveaway </option>
                            </select>
                        </div>
                    </div>

                    <!-- publisher & publication -->
                    <div class="d-flex flex-column flex-md-row gap-3 publisher-publication">
                        <!-- publisher -->
                        <div class="d-flex flex-column gap-2 publisher ">
                            <label for="book-publisher" class="m-0 form-label"> Publisher </label>
                            <input type="text" class="form-control" id="book-publisher" name="book-publisher"
                                aria-describedby="publisher" required>
                        </div>

                        <!-- publication -->
                        <div class="d-flex flex-column gap-2 publication ">
                            <label for="book-publication" class="m-0 form-label"> Publication </label>
                            <input type="text" class="form-control" id="book-publication" name="book-publication"
                                aria-describedby="publication" required>
                        </div>
                    </div>

                    <!-- language & edition -->
                    <div class="d-flex flex-column flex-md-row gap-2 d-flex flex-row gap-3 genre-edition">
                        <!-- language -->
                        <div class="d-flex flex-column gap-2 w-100 w-md-50 language ">
                            <label for="book-language" class="m-0 form-label"> Language </label>
                            <select class="form-select form-select" id="book-language" name="book-language"
                                aria-label="book language" required>
                                <option value="" selected hidden> Select the language </option>
                                <option value="chinese"> Chinese </option>
                                <option value="english"> English </option>
                                <option value="hindi"> Hindi </option>
                                <option value="nepali"> Nepali </option>
                                <option value="urdu"> Urdu </option>
                            </select>
                        </div>

                        <!-- edition -->
                        <div class="d-flex flex-column gap-2 w-100 w-md-50 edition ">
                            <label for="book-edition" class="m-0 form-label"> Edition </label>
                            <input type="number" name="book-edition" id="book-edition" class="form-control" min="1"
                                required>
                        </div>
                    </div>

                    <!-- actual price & offer price -->
                    <div class="d-flex flex-column flex-md-row gap-3 price">
                        <!-- actual price -->
                        <div class="d-flex flex-column gap-2 actual-price ">
                            <label for="book-actual-price" class="m-0 form-label"> Actual price </label>
                            <input type="number" class="form-control" id="book-actual-price" name="book-actual-price"
                                aria-describedby="actual price" placeholder="" min="1" required>
                        </div>

                        <!-- offer price -->
                        <div class="d-flex flex-column gap-2 actual-price" id="offer-price-div">
                            <label for="book-offer-price" class="m-0 form-label"> Offer price </label>
                            <input type="number" class="form-control" id="book-offer-price" name="book-offer-price"
                                aria-describedby="offer price" placeholder="" min="1" value="0" required>
                        </div>
                    </div>
                </div>

                <!-- book photos -->
                <div class="d-flex flex-column rounded p-0 p-md-3 gap-3 right">
                    <div class="heading">
                        <p class="m-0 fw-semibold fs-5 text-secondary"> Images </p>
                        <hr>
                    </div>

                    <div class="d-flex flex-column gap-3 book-images">
                        <!-- cover photo -->
                        <div class="input-group">
                            <label for="cover-page">Cover page</label>
                            <input type="file" name="book-cover-photo" id="cover-page" class="form-control"
                                aria-label="cover page image" accept="image/*" aria-describedby="cover page image"
                                required>
                        </div>

                        <!-- price page -->
                        <div class="input-group">
                            <label for="price-page">Price page</label>
                            <input type="file" name="book-price-photo" id="price-page" class="form-control"
                                aria-label="price page image" accept="image/*" aria-describedby="price page image"
                                required>
                        </div>

                        <!-- isbn page -->
                        <div class="input-group">
                            <label for="isbn-page">ISBN page</label>
                            <input type="file" name="book-isbn-photo" id="isbn-page" class="form-control"
                                aria-label="isbn page image" accept="image/*" aria-describedby="isbn page image"
                                required>
                        </div>
                    </div>

                    <div class="mt-1 operation">
                        <?php
                        if ($task == "add") {
                            ?>
                            <button type="submit" name="add-book-btn" class="btn p-2" <?php if ($profileUser->getAccountStatus() != "verified")
                                echo "disabled"; ?>> Add Now </button>
                            <?php
                        } else {
                            ?>
                            <button type="submit" name="edit-book-btn" class="btn p-2"> Update Now </button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <!-- unset sesstion status & status message -->
    <?php
    unset($_SESSION['status']);
    unset($_SESSION['status-message']);
    ?>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- js :: current file -->
    <script>
        $('#book-isbn').keydown(function () {
            var asciiValue = event.keyCode || event.which;
            if (asciiValue == 32)
                event.preventDefault();
        });
    </script>

    <!-- genre script -->
    <script>
        var genreState = true;
        // genre trigger
        $('#genre-trigger').click(function () {
            genreState = !genreState;
            toggleGenre();
        });

        toggleGenre = () => {
            // hide
            if (genreState) {
                $('.genre-container').css({
                    "height": "0"
                });
                // arrow indicator
                $('#genre-arrow-indicator').css({
                    "rotate": "0deg",
                    "border": "none",
                });
            } else {
                // show
                $('.genre-container').css({
                    "height": "250px",
                    "border": "1px solid whitesmoke"
                });
                // arrow indicator
                $('#genre-arrow-indicator').css({
                    "rotate": "180deg"
                });
            }
        }

        toggleGenre();

        $('#book-genre-label').on('keydown', function () {
            event.preventDefault();
        }).on('focusin', function () {
            genreState = false;
            toggleGenre();
        });

        var genreList = [];

        $('.genre-option').on('click', function () {
            // finding the specific class object
            let clickedOption = $(this);

            // extract value
            let genreName = clickedOption.val();

            if (genreList.includes(genreName)) {
                let index = genreList.indexOf(genreName);
                genreList.splice(index, 1);
            } else {
                genreList.push(genreName);
            }

            // update genre label
            $('#book-genre-label').val(genreList);
        });

        // reset genre
        $('#genre-reset').on('click', function () {
            genreList.length = 0;
            $('#book-genre-label').val("");

            // uncheck selected checkbox
            $('.genre-option').prop('checked', false);
        });
    </script>

    <!-- author script -->
    <script>
        var inputCounter = 1; // Initialize a counter for unique input IDs

        $('#add-author-field').click(function () {
            // Increment the counter
            inputCounter++;

            let containerId = "author-field-container-" + inputCounter;

            // Create a new div to hold the input and its label
            var container = $('<div></div>').attr({
                class: 'd-flex flex-row align-items-center gap-2 author-div',
                id: containerId
            });

            // Create a new input element
            var inputField = $('<input></input>').attr({
                type: 'text',
                id: 'book-author-' + inputCounter,
                name: 'book-author[]',
                class: 'form-control',
                placeholder: 'Enter author name',
                required: true
            });

            // delete div
            var deleteBtn = $('<a></a>').attr({
                class: 'btn btn-danger delete-author pointer',
                id: 'delete-author-' + inputCounter
            });

            // delete icon
            let deleteIcon = $('<i></i>').attr({
                class: 'fa fa-trash'
            });

            deleteBtn.append(deleteIcon);

            // Append the label and input to the new div
            container.append(inputField);
            container.append(deleteBtn);

            // Append the new div to the container
            $('#author-field-container').append(container);

            deleteBtn.on('click', function () {
                removeAuthorContainer(containerId);
            })
        });

        // remove author container
        removeAuthorContainer = (fieldContainerId) => {
            $('#' + fieldContainerId).remove();
        }
    </script>

    <!-- book purpose script -->
    <script>
        const bookPurposeSelect = $('#book-purpose');
        const offerPriceField = $('#book-offer-price');
        const offerPriceDiv = $('#offer-price-div');
        
        offerPriceDiv.removeClass('d-flex').addClass('d-none');
        
        bookPurposeSelect.on('change', function(){
            var purpose =  bookPurposeSelect.val();
            
            console.clear();
            console.log("Purpose : ", purpose);
            
            if(bookPurposeSelect.val() == 'buy/sell'){
                offerPriceDiv.show();
                console.log("Show");
                offerPriceDiv.removeClass('d-none').addClass('d-flex');
            }else{
                offerPriceDiv.hide();
                console.log("Hide");
                offerPriceDiv.removeClass('d-flex').addClass('d-none');
            }
        });

        offerPriceField.on('focus', function(){
            if(offerPriceField.val() == 0){
                offerPriceField.val('');
            }
        }).on('focusout', function(){
            if(offerPriceField.val() == ''){
                offerPriceField.val('0');
            }
        });
    </script>
</body>

</html>