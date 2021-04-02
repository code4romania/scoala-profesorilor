$(document).ready( () => {
    $('#learning-plan-edit').on('click', function () {
        /* Extract the clicked learning plan ID. */
        var $learningPlanId = $(this).data('id');

        /* Send the view request */
        $.request(
            'onTeacherLearningPlanEdit',
            {
                update: {'teacher-learning-plans/learning-plan-edit': '#learning-plan-tab-content'},
                data: {learningPlanId: $learningPlanId}
            }
        );
    });
})
