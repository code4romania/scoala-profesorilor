$(document).ready(function () {
    $(".cookie_container").append('<div class="close_cookie"><span class="close_cookie_inner">x</span></div>');
    $(".close_cookie").on("click", function(){
        $(".cookie_container").remove();
    })
});
