$( document ).ready(function() {
    let jsScript = $("#learning-plan-course-add-modal");
    let schools = jsScript.attr("data-schools");

    console.log(schools);
    console.log("add-modal");

    $('#school').autocomplete({
        source: schools,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#learning-plan-course-add-modal-form').on('submit', function () {
        /* Close the learning plan add course modal. */
        $('#learning-plan-course-add-modal').modal('hide');

        /* Initiate the learning plan course add. */
        $('#learning-plan-course-add-modal-form').request(
            'onTeacherLearningPlanCourseAdd',
            {
                update: { 'teacher-learning-plans/learning-plan-course-add-remove-btn': '#add-remove-course' },
                data: { 'noSearch': 'true' }
            }
        );

        return false;
    })
});

/* Function that saves the ID of the course that was clicked. */
$('#learning-plan-course-add-modal').on('show.bs.modal', function (event) {
    $('#courseId').val($(event.relatedTarget).data('courseId'));
    $('#learningPlanId').val($(event.relatedTarget).data('learningPlanId'));
});
