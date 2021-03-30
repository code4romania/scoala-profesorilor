$(document).ready(function() {
    $('.appraisal-details').on('click', function () {
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
                    teacherId: $teacherId
                },
            }
        );
    });
});
