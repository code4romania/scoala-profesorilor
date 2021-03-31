$(document).ready( () => {
    $('.learning-plan-remove-course').on('click', function () {
        /* Extract the course and learning plan data. */
        var $courseId = $(this).data('courseId');
        var $learningPlanId = $(this).data('learningPlanId');

        $.request(
            'onTeacherLearningPlanCourseRemove',
            {
                update: {
                    'teacher-learning-plans/learning-plan-course-add-remove-btn': '#add-remove-course'
                },
                data: {
                    courseId: $courseId,
                    learningPlanId: $learningPlanId,
                    noSearch: true
                },
            }
        );
    });
})
