$(document).ready( () => {
    $('#teacher-remove').on('click', function () {
        let jsScript = $("#partial-teacher-remove");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the clicked teacher ID. */
        var $teacherId = $(this).data('teacherId');

        /* Send the view request */
        $.request(
            'onTeacherRemove',
            {
                update: {'school-profile/teachers-tab': '#teachers-tab-content'},
                data: {
                    teacherId: $teacherId,
                    nonce: cspNonce
                }
            }
        );
    });
})
