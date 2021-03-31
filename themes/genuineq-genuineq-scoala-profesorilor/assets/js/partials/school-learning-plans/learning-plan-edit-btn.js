$(document).ready( () => {
    $('#learning-plan-edit').on('click', function () {
        /* Extract the clicked learning plan ID. */
        var $learningPlanId = $(this).data('id');

        /* Send the view request */
        $.request(
            'onLearningPlanEdit',
            {
                update: {'school-learning-plans/learning-plan-edit': '#teachers-tab-content'},
                data: {learningPlanId: $learningPlanId}
            }
        );
    });
})
