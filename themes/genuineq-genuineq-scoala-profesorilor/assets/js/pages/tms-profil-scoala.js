$( document ).ready(function() {
    /** Display the tab specified inside the URL. */
    const tmsSchoolProfileAnchor = window.location.hash;
    $(`a[href="${tmsSchoolProfileAnchor}"]`).tab('show');

    /** Extract the notifications. */
    $.request(
        'onViewNotifications',
        {
            update: {
                'school-profile/notifications-tab': '#notifications-tab-content'
            }
        }
    );


    $('.learning-plan-course-search-pagination').on('click', function () {
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
                    learningPlanId: $learningPlanId
                }
            }
        );
    });
});
