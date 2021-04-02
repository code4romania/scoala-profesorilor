$(document).ready( () => {
    $("#signup-teacher-modal").on('shown.bs.modal', function(event){
        /* Hide the body scrollbar. */
        document.body.style.overflow = "hidden";
    });

    $("#signup-teacher-modal").on('hidden.bs.modal', function(){
        /* Show the body scrollbar. */
        document.body.style.overflow = "auto";
    });
})
