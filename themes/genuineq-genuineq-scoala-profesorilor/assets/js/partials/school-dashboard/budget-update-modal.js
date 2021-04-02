$(document).ready( () => {
    $('#school-budget-update-modal-add').on('click', function () {

        $('#school-budget-update-modal-form').request(
            'onSchoolBudgetUpdate',
            {
                update: {
                    'school-dashboard/budget-active': '#budgetStatus',
                    'school-profile/dashboard-tab': '#dashboard-tab-content'
                },
            }
        );

    });
})
