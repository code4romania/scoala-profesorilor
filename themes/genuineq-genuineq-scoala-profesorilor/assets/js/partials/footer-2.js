$(document).ready( () => {
    const anchor = window.location.hash;
    $(`a[href="${anchor}"]`).tab('show');
})
