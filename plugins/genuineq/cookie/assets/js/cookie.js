function set_cookie() {
    var expiryDate = new Date();

    expiryDate.setMonth(expiryDate.getMonth() + 1);
    document.cookie = 'accept-cookies=cookieset; expires=' + expiryDate.toGMTString();

    jQuery(".cookie_container").remove();
}

jQuery(function() {
    jQuery(".cookie_container").hide();

    setTimeout(function(){
        jQuery('.cookie_container').slideDown();
    }, 1000);
});
