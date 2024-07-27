<?php

if (!isset($_POST['userId']) || !isset($_POST['tab'])) {
    exit;
} else {
    $userId = $_POST['userId'];
    $tab = $_POST['tab'];

    require_once __DIR__ . '/../classes/user.php';

    $userFound = false;

    if (!isset($profileUser)) {
        $profileUser = new User();
        $userFound = $profileUser->fetch($userId);
    }

    if ($userFound) {
        ?>
        <input type="hidden" name="user-id" id="user-id" value="<?= $userId ?>">

        <!-- profile picture -->
        <div class="d-none d-flex flex-column gap-1 w-100 w-md-50 flex-grow-1 profile-picture"
            id="edit-profile-form-photo-container">
            <label for="edit-profile-profile-picture" class="form-label text-secondary"> Change profile
                picture </label>
            <input type="file" name="edit-profile-profile-picture" class="border rounded form-control"
                id="edit-profile-profile-picture" accept="image/*">
        </div>

        <!-- name -->
        <div class="d-flex flex-column flex-md-row gap-3 flex-wrap">
            <div class="flex-grow-1 first-name-div">
                <label for="edit-profile-first-name" class="form-label">First name </label>
                <input type="text" class="form-control" id="edit-profile-first-name" value="<?php if ($profileUser->name['first'] != "")
                    echo ucfirst($profileUser->name['first']); ?>" name="edit-profile-first-name"
                    aria-describedby="first name" disabled>
            </div>

            <div class="flex-grow-1 last-name-div">
                <label for="edit-profile-last-name" class="form-label">Last name</label>
                <input type="text" class="form-control" id="edit-profile-last-name" value="<?php if ($profileUser->name['last'] != "")
                    echo ucfirst($profileUser->name['last']); ?>" name="edit-profile-last-name"
                    aria-describedby="last name" disabled>
            </div>
        </div>

        <!-- date of birth & gender -->
        <div class="d-flex flex-column flex-md-row gap-3 dob-gender">
            <!-- date of birth -->
            <div class="d-flex flex-column w-100 w-md-50 dob-div">
                <label for="edit-profile-dob" class="form-label"> Date of birth </label>
                <input type="date" class="p-2" value="<?php if ($profileUser->getDob() != "")
                    echo $profileUser->getDob(); ?>" name="edit-profile-dob" id="edit-profile-dob" disabled>
            </div>

            <!-- gender -->
            <div class="d-flex flex-column w-100 w-md-50 flex-grow-1">
                <label for="edit-profile-gender" class="form-label"> Gender </label>
                <select class="form-select" name="edit-profile-gender" id="edit-profile-gender"
                    aria-label="Default select example" disabled>
                    <?php
                    if ($profileUser->gender == "") {
                        ?>
                        <option value="" selected hidden>Select gender</option>
                        <?php
                    } else {
                        ?>
                        <option value="<?= $profileUser->gender ?>" selected hidden>
                            <?php
                            if ($profileUser->gender == 0) {
                                echo "Male";
                            } elseif ($profileUser->gender == 1) {
                                echo "Female";
                            } elseif ($profileUser->gender == 2) {
                                echo "Others";
                            } else {
                                echo "Select gender";
                            }
                            ?>
                        </option>
                        <?php
                    }
                    ?>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                    <option value="2">Others</option>
                </select>
            </div>
        </div>

        <!-- email & contact -->
        <div class="d-flex flex-column flex-md-row gap-3 email-contact-div">
            <!-- email -->
            <div class="w-100 w-md-50 email-div">
                <label for="edit-profile-email" class="form-label"> Email address </label>
                <input type="text" class="form-control" id="edit-profile-email" value="<?= $profileUser->email ?>"
                    name="edit-profile-email" aria-describedby="email" disabled>
            </div>

            <!-- phone number -->
            <div class="w-100 w-md-50 contact-div">
                <label for="edit-profile-contact" class="form-label"> Contact </label>
                <input type="text" class="form-control" id="edit-profile-contact" value="<?php if ($profileUser->getPhoneNumber() != "")
                    echo str_replace('+977', '', $profileUser->getPhoneNumber()); ?>" name="edit-profile-contact"
                    aria-describedby="contact" disabled>
            </div>
        </div>

        <!-- address -->
        <div class="d-flex flex-column flex-md-row gap-3 address-div">
            <!-- district -->
            <div class="w-100 w-md-50 district-div">
                <label for="edit-profile-district" class="form-label"> District </label>
                <select class="form-select" name="edit-profile-district" aria-label="district select">
                    <?php
                    // if value is already set
                    if ($profileUser->getAddressDistrict() != "") {
                        ?>
                        <option value="<?= $profileUser->getAddressDistrict() ?>" selected hidden>
                            <?= $districtArray[$profileUser->getAddressDistrict()] ?>
                        </option>
                        <?php
                    } else {
                        ?>
                        <option value="" selected hidden> Select district </option>
                        <?php
                    }

                    foreach ($districtArray as $district) {
                        ?>
                        <option value="<?php echo getDistrictIndexValue($district); ?>">
                            <?= $district ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <!-- location -->
            <div class="w-100 w-md-50 location-div">
                <label for="edit-profile-location" class="form-label"> Location </label>
                <input type="text" class="form-control" id="edit-profile-location" value="<?php if ($profileUser->getAddressLocation() != "")
                    echo ucfirst($profileUser->getAddressLocation()); ?>" name="edit-profile-location"
                    aria-describedby="location" disabled>
            </div>
        </div>

        <i class="m-0 small text-secondary"> Note:- This address will be used for dropshipping. </i>

        <button type="submit" class="btn rounded" id="update-profile-btn" name="update-profile-btn">
            Update </button>
        <?php
    } else {
        echo "Error in fetching user";
    }
}
