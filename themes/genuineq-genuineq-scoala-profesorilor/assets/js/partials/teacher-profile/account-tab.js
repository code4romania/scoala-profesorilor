$( document ).ready(function() {
    let jsScript = $("#teacher-account-tab");
    let addresses = JSON.parse(jsScript.attr("data-addresses"));
    let seniorityLevels = JSON.parse(jsScript.attr("data-seniorityLevels"));

    $('#teacher_address_id').autocomplete({
        source: addresses,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#teacher_seniority_level_id').autocomplete({
        source: seniorityLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#teacher_birth_date').datepicker({
        format: "dd-mm-yyyy",
        weekStart: 1,
        language: "ro",
        todayHighlight: true
    });

    $('#teacher_avatar').on('change',function(event){
        if (event.target.files.length) {
            $(this).next('.custom-file-label').html(event.target.files[0].name);
        }
    })
});
