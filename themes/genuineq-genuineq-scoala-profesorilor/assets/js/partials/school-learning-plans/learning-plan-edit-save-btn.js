$(document).ready( () => {
    $('#learning-plan-save').on('click', function () {
        /* Extract the clicked teacher ID. */
        var $teacherId = $(this).data('id');

        /* Send the view request */
        $.request(
            'onTeacherView',
            {
                update: {'school-teachers/teacher-view': '#teachers-tab-content'},
                data: {teacherId: $teacherId}
            }
        );
    });
})
