$(document).ready( () => {
    $("#signup-school-modal").on('shown.bs.modal', function(event){
        /* Hide the body scrollbar. */
        document.body.style.overflow = "hidden";
    });

    $("#signup-school-modal").on('hidden.bs.modal', function(){
        /* Show the body scrollbar. */
        document.body.style.overflow = "auto";
    });
})
