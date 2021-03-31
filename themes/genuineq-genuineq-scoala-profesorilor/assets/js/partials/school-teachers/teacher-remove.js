$(document).ready( () => {
    $('#teacher-remove').on('click', function () {
        /* Extract the clicked teacher ID. */
        var $teacherId = $(this).data('teacherId');

        /* Send the view request */
        $.request(
            'onTeacherRemove',
            {
                update: {'school-profile/teachers-tab': '#teachers-tab-content'},
                data: {teacherId: $teacherId}
            }
        );
    });
})
