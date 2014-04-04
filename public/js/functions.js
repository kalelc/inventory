$(document).ready(function() {
    $("#viewchars").click(function() {
        if ($("#viewchars").is(':checked')) {
            $("#password").prop('type', 'text');
            $("#password").attr('value', $('#password').val());
        }
        else {
            $("#password").prop('type', 'password');
        }
    });
});