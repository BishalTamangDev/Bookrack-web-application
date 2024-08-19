<?php
$userId = $_POST['userId'] ?? 0;

if ($userId == 0) {
    ?>
    <tr>
        <td colspan="7" style="text-align:center;" class="text-danger"> No request found! </td>
    </tr>
    <?php
    exit;
}
?>

<tr>
    <th scope="row"> 1 </th>
    <td> Title </td>
    <td> Price </td>
    <td> 0000-00-00 </td>
    <td> 0000-00-00 </td>
    <td> Accepted </td>
    <td> <a href="" class="text-primary" data-bs-toggle="modal" data-bs-target="#request-modal" data-request-id=""> Show
            detail </a> </td>
</tr>

<tr>
    <td colspan="7" style="text-align:center;" class="text-danger"> No request found! </td>
</tr>