$(document).ready( () => {
    /* MDBOOTSTRAP Limitation: Trigger change value to move all input labels up. */
    $('input.form-control').change();
    $('textarea.form-control').change();
    $('input.custom-file-input').change();
})
