$(document).ready( () => {
    $("#password-restore-modal").on('shown.bs.modal', function(){
        /* Hide the body scrollbar. */
        document.body.style.overflow = "hidden";
    });

    $("#password-restore-modal").on('hidden.bs.modal', function(){
        /* Show the body scrollbar. */
        document.body.style.overflow = "auto";
    });
});
