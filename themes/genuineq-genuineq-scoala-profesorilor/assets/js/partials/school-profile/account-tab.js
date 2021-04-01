$( document ).ready(function() {
    let jsScript = $("#school-account-tab");
    let inspectorates = JSON.parse(jsScript.attr("data-inspectorates"));
    let addresses = JSON.parse(jsScript.attr("data-addresses"));

    console.log('inspectorates: ' + inspectorates);
    console.log('inspectorates keys: ' + Object.keys(inspectorates));
    let inspectoratesKeys = Object.keys(inspectorates);

    console.log('addresses: ' + addresses);
    console.log('addresses keys: ' + Object.keys(addresses));
    let addressesKeys = Object.keys(addresses);

    $('#school_inspectorate_id').autocomplete({
        source: inspectoratesKeys,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_address_id').autocomplete({
        source: addressesKeys,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_avatar').on('change',function(event){
        if (event.target.files.length) {
            $(this).next('.custom-file-label').html(event.target.files[0].name);
        }
    })
});
