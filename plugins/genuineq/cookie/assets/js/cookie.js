$( document ).ready(function() {
    function set_cookie() {
        var expiryDate = new Date();

        expiryDate.setMonth(expiryDate.getMonth() + 1);
        document.cookie = 'accept-cookies=cookieset; expires=' + expiryDate.toGMTString();

        jQuery(".cookie_container").remove();
    }


    $('#accept_cookies').on('click',function(event){
        set_cookie();
    })


    $(".cookie_container").hide();

    setTimeout(function() {
        $('.cookie_container').slideDown();
    }, 1000);
});
