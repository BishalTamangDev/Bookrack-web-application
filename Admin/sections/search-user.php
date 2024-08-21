<?php

$content = $_POST['content'] ?? '';

if ($content == '') {
    echo "No user found!";
    exit;
}

require_once __DIR__ . '/../../classes/user.php';

$tempUser = new User();

// fetch all users
$userIdList = $tempUser->fetchAllUserId();
$searchCount = 0;

$serial = 1;
foreach ($userIdList as $userId) {
    $tempUser->fetch($userId);

    if ($content == $tempUser->getAddressMunicipality() || $content == $tempUser->getAddressToleVillage() || $content == $tempUser->name['first'] || $content == $tempUser->name['last']) {
        $searchCount++;
        ?>
        <tr class="user-tr <?= ($tempUser->accountStatus == "verified") ? "verified-user-tr" : "unverified-user-tr" ?>">
            <th scope="row"> <?= $serial++ ?> </th>
            <td>
                <?php
                $fullName = $tempUser->getFullName();
                echo $fullName != ' ' ? $fullName : "-";
                ?>
            </td>
            <td> <?= $tempUser->email ?> </td>
            <td>
                <?php
                $phoneNumber = $tempUser->getPhoneNumber() ?? '';
                echo $phoneNumber != "" ? $phoneNumber : "-";
                ?>
            </td>
            <td> <?= $tempUser->getFullAddress() ?>
            </td>
            <td> <?= ucfirst($tempUser->accountStatus) ?> </td>
            <td>
                <abbr title="Show full details">
                    <a href="/bookrack/admin/admin-user-details/<?= $userId ?>" class="text-primary small">
                        Show details
                    </a>
                </abbr>
            </td>
        </tr>
        <?php
    }
}

if ($searchCount == 0) {
    ?>
    <tr>
        <td colspan="7">
            <div class="d-flex flex-row gap-2 table-loading-gif-container">
                <p class="m-0 text-secondary"> No user found! </p>
            </div>
        </td>
    </tr>
    <?php
}


exit;