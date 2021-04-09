$(document).ready( () => {
    $('#teacher-edit').on('click', function () {
        let jsScript = $("#partial-teacher-view");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the clicked teacher ID. */
        var $teacherId = $(this).data('id');

        /* Send the view request */
        $.request(
            'onTeacherEdit',
            {
                update: {'school-teachers/teacher-edit': '#teachers-tab-content'},
                data: {
                    teacherId: $teacherId,
                    nonce: cspNonce
                }
            }
        );
    });
})
