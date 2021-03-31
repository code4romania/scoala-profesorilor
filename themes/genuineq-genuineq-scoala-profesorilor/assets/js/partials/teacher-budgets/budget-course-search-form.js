function budgetCourseSearchFormSubmit(){
    /* Extract the clicked page. */
    var $newPage = $('#budgetCourseSearchPagination > ul > li.active').data('page');

    /* Select the course select form and send the request */
    $('#budgetCourseSearchForm').request(
        'onTeacherBudgetCourseSearch',
        {
            update: {
                'teacher-budgets/budget-summary': '#budgetSummary',
                'teacher-budgets/budget-category-grid': '#budgetCategories',
                'teacher-budgets/budget-course-grid': '#budgetCourseSearchResults',
                'teacher-budgets/budget-course-pagination': '#budgetCourseSearchPagination',
            },
            data: { page: $newPage }
        }
    );
}

$(document).ready( () => {
    $('#budgetCourseSearchFormContent').on('change', 'select', function () {
        budgetCourseSearchFormSubmit();
    });

    $('#budgetCourseSearchForm').on('submit', function () {
        budgetCourseSearchFormSubmit();

        return false;
    })
})
