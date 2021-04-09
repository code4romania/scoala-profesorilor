function learningPlanCourseSearchFormSubmit(){
    let jsScript = $("#partial-learning-plan-course-search-form");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /* Extract the clicked page. */
    var $newPage = $('#learningPlanCourseSearchPagination > ul > li.active').data('page');
    /* Extract the active learning plan ID. */
    var $learningPlanId = $('#learningPlanCourseSearchPagination > ul > li.active').data('id');

    /* Select the course select form and send the request */
    $('#learningPlanCourseSearchForm').request(
        'onLearningPlanCourseSearch',
        {
            update: {
                'teacher-learning-plans/learning-plan-course-grid': '#learningPlanCourseSearchResults',
                'teacher-learning-plans/learning-plan-course-pagination': '#learningPlanCourseSearchPagination'
            },
            data: {
                page: $newPage,
                learningPlanId: $learningPlanId,
                nonce: cspNonce
            }
        }
    );
}

$(document).ready( () => {
    $('#learningPlanCourseSearchFormContent').on('change', 'select', function () {
        learningPlanCourseSearchFormSubmit();
    });

    $('#learningPlanCourseSearchForm').on('submit', function () {
        learningPlanCourseSearchFormSubmit();

        return false;
    })
})
