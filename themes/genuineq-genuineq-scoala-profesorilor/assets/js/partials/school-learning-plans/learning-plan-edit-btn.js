$(document).ready( () => {
    $('#learning-plan-edit').on('click', function () {
        let jsScript = $("#partial-learning-plan-edit-btn");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the clicked learning plan ID. */
        var $learningPlanId = $(this).data('id');

        /* Send the view request */
        $.request(
            'onLearningPlanEdit',
            {
                update: {'school-learning-plans/learning-plan-edit': '#teachers-tab-content'},
                data: {
                    learningPlanId: $learningPlanId,
                    nonce: cspNonce
                }
            }
        );
    });
})
