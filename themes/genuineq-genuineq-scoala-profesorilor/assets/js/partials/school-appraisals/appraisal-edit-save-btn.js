$(document).ready( () => {
    $('#appraisal-save').on('click', function () {
        let jsScript = $("#partial-appraisal-edit-save-btn");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the clicked teacher ID. */
        var $teacherId = $(this).data('id');

        /* Send the view request */
        $.request(
            'onTeacherView',
            {
                update: {'school-teachers/teacher-view': '#teachers-tab-content'},
                data: {
                    teacherId: $teacherId,
                    nonce: cspNonce
                }
            }
        );
    });
})
