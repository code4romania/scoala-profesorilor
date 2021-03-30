$( document ).ready(function() {
    let jsScript = $('#appraisal-details-skills');
    console.log(jsScript);
    let appraisal = jsScript.attr('data-appraisal');
    console.log(appraisal);
    let skills = jsScript.attr('data-skills');
    console.log(skills);

    /* MDBOOTSTRAP Limitation: Trigger change value to move all input labels up. */
    $('input.form-control').change();
    $('textarea.form-control').change();
    $('input.custom-file-input').change();

    if ('objectives-approved' == appraisal.status) {
        $('#first_skill').autocomplete({
            source: skills,
            highlightClass: 'text-danger',
            treshold: 1
        });

        $('#second_skill').autocomplete({
            source: skills,
            highlightClass: 'text-danger',
            treshold: 1
        });

        $('#third_skill').autocomplete({
            source: skills,
            highlightClass: 'text-danger',
            treshold: 1
        });
    }
});
