$(document).ready(function() {
    $('.appraisal-details').on('click', function () {
        let jsScript = $("#partial-appraisal-grid");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the appraisal and teacher data. */
        var $appraisalId = $(this).data('appraisalId');
        var $teacherId = $(this).data('teacherId');

        $.request(
            'onSchoolViewGetAppraisalDetails',
            {
                update: {
                    'school-appraisals/appraisal-details': '#appraisalViewEditDetails'
                },
                data: {
                    appraisalId: $appraisalId,
                    teacherId: $teacherId,
                    nonce: cspNonce
                },
            }
        );
    });
});
