$('.learning-plan-course-search-pagination').on('click', function () {
    let jsScript = $("#partial-learning-plan-course-pagination");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /* Extract the clicked page. */
    var $newPage = $(this).data('page');
    /* Extract the clicked learning plan ID. */
    var $learningPlanId = $(this).data('id');

    /* Select the course select form and send the request */
    $('#learningPlanCourseSearchForm').request(
        'onLearningPlanCourseSearch',
        {
            update: {
                'school-learning-plans/learning-plan-course-grid': '#learningPlanCourseSearchResults',
                'school-learning-plans/learning-plan-course-pagination': '#learningPlanCourseSearchPagination'
            },
            data: {
                page: $newPage,
                learningPlanId: $learningPlanId,
                nonce: cspNonce
            }
        }
    );
});
