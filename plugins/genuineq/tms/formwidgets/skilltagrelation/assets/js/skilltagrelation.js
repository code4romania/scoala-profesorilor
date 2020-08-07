$(document).on('render', function(){
    $('.skilltagrelation').select2({
        placeholder: 'Select a skill',
        tags: true,
        maximumSelectionLength: 5
    });
});
