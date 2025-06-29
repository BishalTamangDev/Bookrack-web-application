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

    $accountStatusClass = "";
    switch ($tempUser->accountStatus) {
        case 'verified':
            $accountStatusClass = "verified-user-tr";
            break;
        case 'unverified':
            $accountStatusClass = "unverified-user-tr";
            break;
        case 'pending':
            $accountStatusClass = "pending-user-tr";
            break;
        case 'on-hold':
            $accountStatusClass = "on-hold-user-tr";
            break;
        default:
            $accountStatusClass = "unknown";
    }

    if ($content == $tempUser->getAddressMunicipality() || $content == $tempUser->getAddressToleVillage() || $content == $tempUser->nameFirst || $content == $tempUser->nameLast) {
        $searchCount++;
        ?>
        <tr class="user-tr <?= $accountStatusClass ?>">
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