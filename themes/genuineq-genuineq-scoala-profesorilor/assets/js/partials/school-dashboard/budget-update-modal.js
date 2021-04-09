$(document).ready( () => {
    $('#school-budget-update-modal-add').on('click', function () {
        let jsScript = $("#partial-budget-update-modal");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        $('#school-budget-update-modal-form').request(
            'onSchoolBudgetUpdate',
            {
                update: {
                    // 'school-dashboard/budget-active': '#budgetStatus',
                    'school-profile/dashboard-tab': '#dashboard-tab-content'
                },
                data: {nonce: cspNonce}
            }
        );
    });
})
