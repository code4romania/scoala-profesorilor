$("#signup-select-modal").on('show.bs.modal', function(){
    /* Hide the body scrollbar. */
    document.body.style.overflow = "hidden";
});

$("#signup-select-modal").on('hidden.bs.modal', function(){
    /* Show the body scrollbar. */
    document.body.style.overflow = "auto";
});
