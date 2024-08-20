<?php

require_once __DIR__ . '/../../classes/user.php';

$userObj = new User();

// fetch all users
$userIdList = $userObj->fetchAllUserId();

if (sizeof($userIdList) == 0) {
    ?>
    <tr>
        <td colspan="7" class="text-danger"> No user found! </td>
    </tr>
    <?php
} else {
    $serial = 1;
    foreach ($userIdList as $userId) {
        $userObj->fetch($userId);
        ?>
        <tr class="user-tr <?= ($userObj->accountStatus == "verified") ? "verified-user-tr" : "unverified-user-tr" ?>">
            <th scope="row"> <?= $serial++ ?> </th>
            <td>
                <?php
                $fullName = $userObj->getFullName();
                echo $fullName != ' ' ? $fullName : "-";
                ?>
            </td>
            <td> <?= $userObj->email ?> </td>
            <td>
                <?php
                $phoneNumber = $userObj->getPhoneNumber() ?? '';
                echo $phoneNumber != "" ? $phoneNumber : "-";
                ?>
            </td>
            <td> <?= $userObj->getFullAddress() ?>
            </td>
            <td> <?= ucfirst($userObj->accountStatus) ?> </td>
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

exit;