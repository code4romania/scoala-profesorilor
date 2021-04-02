$(document).ready( () => {
    $('#teacher-edit').on('click', function () {
        /* Extract the clicked teacher ID. */
        var $teacherId = $(this).data('id');

        /* Send the view request */
        $.request(
            'onTeacherEdit',
            {
                update: {'school-teachers/teacher-edit': '#teachers-tab-content'},
                data: {teacherId: $teacherId}
            }
        );
    });
})
