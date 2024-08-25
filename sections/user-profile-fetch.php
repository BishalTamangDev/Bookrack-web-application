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
                <label for="edit-profile-first-name" class="form-label"> First name </label>
                <input type="text" class="form-control" id="edit-profile-first-name" value="<?php if ($profileUser->name['first'] != "")
                    echo ucfirst($profileUser->name['first']); ?>" name="edit-profile-first-name"
                    aria-describedby="first name" disabled>
            </div>

            <div class="flex-grow-1 last-name-div">
                <label for="edit-profile-last-name" class="form-label"> Last name</label>
                <input type="text" class="form-control" id="edit-profile-last-name" value="<?php if ($profileUser->name['last'] != "")
                    echo ucfirst($profileUser->name['last']); ?>" name="edit-profile-last-name"
                    aria-describedby="last name" disabled>
            </div>
        </div>

        <!-- date of birth & gender -->
        <div class="d-flex flex-column flex-md-row gap-3 dob-gender">
            <!-- date of birth -->
            <div class="d-flex flex-column w-100 w-md-50 flex-grow-1 dob-div">
                <label for="edit-profile-dob" class="form-label"> Date of birth </label>
                <input type="date" class="p-2 form-control" value="<?php if ($profileUser->getDob() != "")
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
                        <option value="" selected hidden> Select gender </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?= $profileUser->gender ?>" selected hidden>
                            <?= ucfirst($profileUser->gender) ?>
                        </option>
                        <?php
                    }
                    ?>
                    <option value="male"> Male </option>
                    <option value="female"> Female </option>
                    <option value="others"> Others </option>
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
                <label for="edit-profile-contact" class="form-label"> Phone Number </label>
                <input type="text" class="form-control" id="edit-profile-contact" value="<?php if ($profileUser->getPhoneNumber() != "")
                    echo str_replace('+977', '', $profileUser->getPhoneNumber()); ?>" name="edit-profile-contact"
                    aria-describedby="contact" minlength="10" maxlength="10" disabled>
            </div>
        </div>

        <!-- district && municipality -->
        <div class="d-flex flex-column flex-md-row gap-3 address-div">
            <!-- district -->
            <div class="w-100 w-md-50 district-div">
                <label for="edit-profile-district" class="form-label"> District </label>
                <select class="form-select" name="edit-profile-district" id="edit-profile-district" aria-label="district select"
                    disabled>
                    <?php
                    // if value is already set
                    if ($profileUser->getAddressDistrict() != "") {
                        ?>
                        <option value="<?= $profileUser->getAddressDistrict() ?>" selected hidden>
                            <?= ucwords($profileUser->getAddressDistrict()) ?>
                        </option>
                        <?php
                    } else {
                        ?>
                        <option value="" selected hidden> Select District </option>
                        <?php
                    }

                    foreach ($districtArray as $district) {
                        ?>
                        <option value="<?= strtolower($district); ?>">
                            <?= $district ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <!-- municipality -->
            <div class="w-100 w-md-50 municipality-div">
                <label for="edit-profile-municipality" class="form-label"> Municipality </label>
                <input type="text" class="form-control" id="edit-profile-municipality" value="<?php if ($profileUser->getAddressMunicipality() != "")
                    echo ucfirst($profileUser->getAddressMunicipality()); ?>" name="edit-profile-municipality"
                    aria-describedby="municipality" disabled>
            </div>
        </div>

        <!-- ward && tole/ village -->
        <div class="d-flex flex-column flex-md-row w-100 w-md-50 gap-3">
            <!-- ward -->
            <div class="w-100 w-md-50">
                <label for="edit-profile-ward" class="form-label"> Ward </label>
                <select name="edit-profile-ward" id="edit-profile-ward" class="form-control w-100" disabled>
                    <?php
                    // if value is already set
                    if ($profileUser->getAddressWard() != "") {
                        ?>
                        <option value="<?= $profileUser->getAddressWard() ?>" selected hidden>
                            <?= $profileUser->getAddressWard() ?>
                        </option>
                        <?php
                    } else {
                        ?>
                        <option value="" selected hidden> Select Ward </option>
                        <?php
                    }
                    ?>
                    <option value="1"> 1 </option>
                    <option value="2"> 2 </option>
                    <option value="3"> 3 </option>
                    <option value="4"> 4 </option>
                    <option value="5"> 5 </option>
                    <option value="6"> 6 </option>
                    <option value="7"> 7 </option>
                    <option value="8"> 8 </option>
                    <option value="9"> 9 </option>
                    <option value="10"> 10 </option>
                </select>
            </div>

            <!-- tole/ village -->
            <div class="w-100 w-md-50 ">
                <label for="edit-profile-tole-village" class="form-label"> Tole/ Village </label>
                <input type="text" class="form-control" id="edit-profile-tole-village" value="<?php if ($profileUser->getAddressToleVillage() != "")
                    echo ucfirst($profileUser->getAddressToleVillage()); ?>" name="edit-profile-tole-village"
                    aria-describedby="tole-village" disabled>
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
