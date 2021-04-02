$(document).ready( () => {
    $('#password-reset-modal').modal()

    $("#password-reset-modal").on('shown.bs.modal', function(){
        /* Hide the body scrollbar. */
        document.body.style.overflow = "hidden";
    });

    $("#password-reset-modal").on('hidden.bs.modal', function(){
        /* Show the body scrollbar. */
        document.body.style.overflow = "auto";
    });
});
