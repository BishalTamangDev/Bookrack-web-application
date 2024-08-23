// click notification
$(document).on('click', '.unseen-notification', function () {
    var notification_id = $(this).data('notification-id');

    // update status as seen
    $.ajax({
        type: "POST",
        url: "/bookrack/app/notification-status-change.php",
        data: { notificationId: notification_id },
        success: function (response) {
            console.log(response);
        }
    });
});