<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/home");
    exit;
}

$url = "add-book";

$userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/classes/user.php';
require_once __DIR__ . '/functions/genre-array.php';

$profileUser = new User();
$userExists = $profileUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/signout");

if ($task == 'edit') {
    require_once __DIR__ . '/classes/book.php';
    $book = new Book();
    $bookExists = $book->fetch($bookId);

    if (!$bookExists) {
        header("Location: /bookrack/profile/my-books");
        exit;
    }

    // check if the user is owner or not
    if ($book->getOwnerId() != $userId) {
        header("Location: /bookrack/home");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> <?php echo ($task == "add") ? "Add Book" : "Edit Book" ?> </title>

    <?php require_once __DIR__ . '/includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/add-book.css">
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/sections/header.php'; ?>

    <!-- main -->
    <main class="d-flex flex-column gap-3 container main pb-5">
        <section class="d-flex flex-column gap-4 section add-book-section">
            <!-- alert for unverified account -->
            <?php
            if ($profileUser->accountStatus != "verified") {
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
                    <?php if ($task == "add") {
                        ?>
                        <button class="btn btn-danger" onclick="window.location.href='/bookrack/add-book/<?= $task ?>'">
                            Reset </button>
                        <?php
                    } else {
                        ?>
                        <button class="btn btn-danger"
                            onclick="window.location.href='/bookrack/add-book/edit/<?= $bookId ?>'"> Reset </button>
                        <?php
                    } ?>
                </div>
            </div>

            <!-- add book form -->
            <form method="POST"
                class="d-flex flex-column flex-md-row justify-content-between gap-3 gap-md-4 add-book-form"
                id="add-book-form" enctype="multipart/form-data" autocomplete="on">
                <div class="d-flex flex-column gap-3 left rounded p-0 p-md-3 book-details">
                    <input type="hidden" name="csrf_token_add_book" class="form-control" id="csrf_token_add_book" value="<?php if ($task == "edit")
                        echo $book->getId(); ?>">

                    <!-- id -->
                    <input type="hidden" name="book-id" value="<?php if ($task == "edit")
                        echo $book->getId(); ?>">

                    <!-- title -->
                    <div class="d-flex flex-column gap-2 title">
                        <label for="book-title" class="m-0 form-label"> Title </label>
                        <input type="text" class="form-control" id="book-title" name="book-title" value="<?php if ($task == 'edit')
                            echo $book->title; ?>" aria-describedby="book title" required>
                    </div>

                    <!-- description -->
                    <div class="d-flex flex-column gap-2 description">
                        <label for="book-description" class="m-0 form-label"> Description </label>
                        <textarea class="form-control" id="book-description" name="book-description" minlength="5"
                            required><?php if ($task == 'edit')
                                echo $book->description; ?></textarea>
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
                            <?php
                            if ($task == 'add') {
                                ?>
                                <div class="d-flex flex-row align-items-center gap-2 author-div"
                                    id="author-field-container-1">
                                    <input type="text" name="book-author[]" class="form-control" id="book-author-1"
                                        placeholder="Enter author name" required>

                                    <!-- delete author -->
                                    <!-- <a class="btn btn-danger delete-author" id="delete-author-1" onclick="removeAuthorContainer('author-field-container-1')">
                                            <i class="fa fa-trash"></i>
                                        </a> -->
                                </div>
                                <?php
                            } else {
                                $count = 1;
                                foreach ($book->author as $author) {
                                    ?>
                                    <div class="d-flex flex-row align-items-center gap-2 author-div"
                                        id="author-field-container-<?= $count ?>">
                                        <input type="text" name="book-author[]" class="form-control"
                                            id="book-author-<?= $count ?>" value="<?= $author ?>"
                                            placeholder="Enter author name" required>

                                        <!-- delete author -->
                                        <?php
                                        if ($count != 1) {
                                            ?>
                                            <a class="btn btn-danger delete-author" id="delete-author-<?= $count ?>"
                                                onclick="removeAuthorContainer('author-field-container-<?= $count ?>')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    $count++;
                                }
                            }
                            ?>
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
                                value="<?php if ($task == 'edit') {
                                    $count = 1;
                                    foreach ($book->genre as $genre) {
                                        if ($count == sizeof($book->genre)) {
                                            echo $genre;
                                        } else {
                                            echo $genre . ', ';
                                        }
                                        $count++;
                                    }
                                } ?>" placeholder="Choose atleast one genre" value="" id="book-genre-label" required>
                        </div>

                        <div class="gap-1 rounded px-2 genre-container">
                            <?php
                            $count = 0;
                            foreach ($genreArray as $genre) {
                                $count++;
                                $id = "genre-$count";
                                ?>
                                <div class="gap-2 form-check">
                                    <input class="form-check-input genre-option" type="checkbox"
                                        value="<?php echo $genre; ?>" id="<?php echo $id; ?>" <?php if ($task == "edit") {
                                                  if (in_array($genre, $book->genre))
                                                      echo "checked";
                                              } ?>>
                                    <label class="form-check-label" for="<?php echo $id; ?>"> <?= $genre ?> </label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- isbn & publisher -->
                    <div class="d-flex flex-column flex-md-row gap-3 purpose-isbn">
                        <!-- isbn -->
                        <div class="d-flex flex-column w-100 w-md-50 gap-2 isbn">
                            <label for="book-isbn" class="m-0 form-label"> ISBN </label>
                            <input type="text" class="form-control" id="book-isbn" name="book-isbn" value="<?php if ($task == 'edit')
                                echo $book->isbn; ?>" aria-describedby="book isbn" required>
                        </div>

                        <!-- publisher -->
                        <div class="d-flex flex-column w-100 w-md-50 gap-2 publisher ">
                            <label for="book-publisher" class="m-0 form-label"> Publisher </label>
                            <input type="text" class="form-control" id="book-publisher" name="book-publisher" value="<?php if ($task == 'edit')
                                echo $book->publisher; ?>" aria-describedby="publisher" required>
                        </div>
                    </div>

                    <!-- language & edition -->
                    <div class="d-flex flex-column flex-md-row gap-2 d-flex flex-row gap-3 genre-edition">
                        <!-- language -->
                        <div class="d-flex flex-column gap-2 w-100 w-md-50 language ">
                            <label for="book-language" class="m-0 form-label"> Language </label>
                            <select class="form-select form-select" id="book-language" name="book-language"
                                aria-label="book language" required>
                                <?php
                                if ($task == 'edit') {
                                    ?>
                                    <option value="<?= $book->language ?>" selected hidden> <?= ucfirst($book->language) ?>
                                    </option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="" selected hidden> Select the language </option>
                                    <?php
                                }
                                ?>
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
                            <input type="text" name="book-edition" id="book-edition" class="form-control" value="<?php if ($task == 'edit')
                                echo $book->edition; ?>" required>
                        </div>
                    </div>

                    <!-- actual price & offer price -->
                    <div class="d-flex flex-column flex-md-row gap-3 price">
                        <!-- actual price -->
                        <div class="d-flex flex-column gap-2 actual-price ">
                            <label for="book-actual-price" class="m-0 form-label"> Actual price </label>
                            <input type="number" class="form-control" id="book-actual-price" name="book-actual-price"
                                value="<?php if ($task == 'edit')
                                    echo $book->price['actual']; ?>" aria-describedby="actual price" placeholder=""
                                min="0" required>
                        </div>

                        <!-- offer price -->
                        <div class="d-flex flex-column gap-2 actual-price" id="offer-price-div">
                            <label for="book-offer-price" class="m-0 form-label"> Offer price </label>
                            <input type="number" class="form-control" id="book-offer-price" name="book-offer-price"
                                value="<?php if ($task == 'edit')
                                    echo $book->price['offer']; ?>" aria-describedby="offer price" min="0" required>
                        </div>
                    </div>
                </div>

                <!-- book photos -->
                <div class="d-flex flex-column rounded p-0 p-md-3 gap-3 right">
                    <div class="heading">
                        <p class="m-0 fw-semibold fs-5 text-secondary"> Images </p>
                        <hr />
                    </div>

                    <?php
                    if ($task == "edit") {
                        $book->setPhotoUrl();
                        ?>
                        <div class="d-flex flex-column gap-2 existing-photo-container">
                            <div class="existing-photo">
                                <img src="<?= $book->photoUrl ?>" alt="">
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="d-flex flex-column gap-3 book-images">
                        <!-- photo -->
                        <div class="input-group">
                            <!-- <label for="cover-page">Cover page</label> -->
                            <input type="file" name="book-photo" id="cover-page" class="form-control"
                                aria-label="book photo" accept="image/*" aria-describedby="book photo" <?php if ($task != "edit")
                                    echo "required"; ?>>
                        </div>
                    </div>

                    <div class="mt-1 operation">
                        <?php
                        if ($task == "add") {
                            ?>
                            <button type="submit" name="submit-btn" class="btn p-2" id="submit-btn" data-task="add" <?php if ($profileUser->accountStatus != "verified")
                                echo "disabled"; ?>> Add Now </button>
                            <?php
                        } else {
                            ?>
                            <button type="submit" name="submit-btn" class="btn p-2" id="submit-btn" data-task="edit">
                                Update Now </button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <!-- popup alert -->
    <p class="" id="custom-popup-alert"> Popup message appears here... </p>

    <!-- footer -->
    <?php require_once __DIR__ . '/sections/footer.php'; ?>


    <?php require_once __DIR__ . '/includes/script.php'; ?>

    <script>
        $(document).ready(function () {
            var genreState = true;

            $('#custom-popup-alert').hide();

            function showPopupAlert(msg) {
                $('#custom-popup-alert').removeClass('text-success').addClass('text-danger');
                $('#custom-popup-alert').html(msg).fadeIn();
                setTimeout(function () {
                    $('#custom-popup-alert').fadeOut("slow").html("");
                }, 4000);
            }

            function setCsrfTokenForAddBook() {
                $.get('/bookrack/app/csrf-token.php', function (data) {
                    $('#csrf_token_add_book').val(data);
                });
            }

            setCsrfTokenForAddBook();

            // add book
            $('#add-book-form').submit(function (e) {
                e.preventDefault();
                let formData = new FormData($('#add-book-form')[0]);
                let submit_task = $('#submit-btn').data("task");

                if (submit_task == "add") {
                    $.ajax({
                        url: '/bookrack/app/add-book.php',
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#submit-btn').html("Adding book...").prop('disabled', true);
                        },
                        success: function (response) {
                            $('#submit-btn').html("Add Now").prop('disabled', false);
                            // reset form
                            if (response == "true") {
                                showPopupAlert("Book added successfully.");
                                $('#add-book-form').trigger("reset");
                                // redirect to another page after successful book addition
                            } else {
                                if (response == "false") {
                                    showPopupAlert("Book couldn't be added.");
                                } else {
                                    showPopupAlert(response);
                                }
                            }
                        },
                        error: function () {
                            $('#submit-btn').html("Add Now").prop('disabled', false);
                            showPopupAlert("An error occurred while adding the book.");
                        }
                    });
                } else {
                    $.ajax({
                        url: '/bookrack/app/update-book.php',
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#submit-btn').html("Updating...").prop('disabled', true);
                        },
                        success: function (response) {
                            $('#submit-btn').html("Update Now").prop('disabled', false);
                            // reset form
                            if (response == "true") {
                                showPopupAlert("Book detail updated successfully.");
                                $('#add-book-form').trigger("reset");

                                // redirect to another page after successful book addition
                                let link = "/bookrack/book-details/" + "<?= $bookId ?>";
                                window.location.href = link;
                            } else {
                                if (response == "false") {
                                    showPopupAlert("Book couldn't be added.");
                                } else {
                                    showPopupAlert(response);
                                }
                            }
                        },
                        error: function () {
                            $('#submit-btn').html("UPdate Now").prop('disabled', false);
                            showPopupAlert("An error occurred while updating the book detail.");
                        }
                    });

                }
            });

            $('#book-isbn').keydown(function () {
                var asciiValue = event.keyCode || event.which;
                if (asciiValue == 32)
                    event.preventDefault();
            });

            // genre trigger
            $('#genre-trigger').click(function () {
                genreState = !genreState;
                toggleGenre();
            });

            function toggleGenre() {
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

            <?php
            if ($task == "edit") {
                foreach ($book->genre as $genre) {
                    ?>
                    genreList.push("<?= $genre ?>");
                    <?php
                }
            }
            ?>

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


            // author script
            <?php
            if ($task == "edit") {
                ?>
                var inputCounter = <?= sizeof($book->author) ?>; // Initialize a counter for unique input IDs
                <?php
            } else {
                ?>
                var inputCounter = 1; // Initialize a counter for unique input IDs
                <?php
            }
            ?>

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
        });
    </script>
</body>

</html>