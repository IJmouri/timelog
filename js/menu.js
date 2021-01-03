$('#menu').toggle(function () {
    $('.widget-loggedin').css("display", "block");
    $('.widget-loggedin').css("border", "none");
}, function () {
    $('.widget-loggedin').css("display", "none");
});