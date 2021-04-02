$(document).ready(function() {
    $('.appraisal-details').on('click', function () {
        /* Extract the appraisal and teacher data. */
        var $appraisalId = $(this).data('appraisalId');
        var $teacherId = $(this).data('teacherId');

        $.request(
            'onTeacherViewGetAppraisalDetails',
            {
                update: {
                    'teacher-appraisals/appraisal-details': '#appraisalViewEditDetails'
                },
                data: {
                    appraisalId: $appraisalId,
                    teacherId: $teacherId
                },
            }
        );
    });
});
