$('#custom-popup-alert').hide();

function showPopupAlert(msg) {
    $('#custom-popup-alert').html(msg).fadeIn();
    setTimeout(function () {
        $('#custom-popup-alert').fadeOut("slow");
    }, 4000);
}