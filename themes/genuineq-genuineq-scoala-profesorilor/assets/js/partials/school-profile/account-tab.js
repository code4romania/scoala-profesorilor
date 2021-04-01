$( document ).ready(function() {
    let jsScript = $("#school-account-tab");

    let inspectorates = JSON.parse(jsScript.attr("data-inspectorates"));
    let addresses = JSON.parse(jsScript.attr("data-addresses"));

    $('#school_inspectorate_id').autocomplete({
        source: inspectorates,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_address_id').autocomplete({
        source: addresses ,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_avatar').on('change',function(event){
        if (event.target.files.length) {
            $(this).next('.custom-file-label').html(event.target.files[0].name);
        }
    })
});
