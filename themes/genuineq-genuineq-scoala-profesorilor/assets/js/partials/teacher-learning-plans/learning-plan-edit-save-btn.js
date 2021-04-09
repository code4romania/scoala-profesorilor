$( document ).ready(function() {
    $('#learning-plan-save').on('click', function () {
        let jsScript = $("#partial-learning-plan-edit-save-btn");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        $.request(
            'onTeacherViewLearningPlan',
            {
                update: {'teacher-profile/learning-plan-tab': '#learning-plan-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });
});
