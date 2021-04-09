$(document).ready( () => {
    /* MDBOOTSTRAP Limitation: Trigger change value to move all input labels up. */
    $('input.form-control').change();
    $('textarea.form-control').change();
    $('input.custom-file-input').change();

    $('#appraisal-edit').on('click', function () {
        let jsScript = $("#partial-appraisal-view");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the appraisal and teacher data. */
        var $appraisalId = $(this).data('appraisalId');
        var $teacherId = $(this).data('teacherId');

        /* Send the view request */
        $.request(
            'onSchoolAppraisalEdit',
            {
                update: {'school-appraisals/appraisal-edit': '#teachers-tab-content'},
                data: {
                    teacherId: $teacherId,
                    nonce: cspNonce
                }
            }
        );
    });
})
