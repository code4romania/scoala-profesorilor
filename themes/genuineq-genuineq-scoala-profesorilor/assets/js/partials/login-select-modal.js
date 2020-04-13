$("#login-select-modal").on('shown.bs.modal', function(){
    /* Hide the body scrollbar. */
    document.body.style.overflow = "hidden";
});

$("#login-select-modal").on('hidden.bs.modal', function(){
    /* Show the body scrollbar. */
    document.body.style.overflow = "auto";
});
