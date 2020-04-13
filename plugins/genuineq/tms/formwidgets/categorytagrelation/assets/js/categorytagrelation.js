$(document).on('render', function(){
    $('.categorytagrelation').select2({
        placeholder: 'Select a category',
        tags: true,
        maximumSelectionLength: 3
    });
});
