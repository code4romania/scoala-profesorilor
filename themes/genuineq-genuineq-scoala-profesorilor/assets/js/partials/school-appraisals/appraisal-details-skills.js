$( document ).ready(function() {
    let jsScript = $('#partial-appraisal-details-skills');
    let appraisal = jsScript.attr('data-appraisal');
    let skills = jsScript.attr('data-skills');

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
