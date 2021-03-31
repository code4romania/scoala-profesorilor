$(document).ready( () => {
    $('.learning-plan-edit-card-remove').on('click', function () {
        let jsScript = $("#learning-plan-edit-list");
        let confirmMessage = jsScript.attr("data-confirmMessage");

        /* Extract the course and learning plan ID. */
        var $courseId = $(this).data('courseId');
        var $learningPlanId = $(this).data('learningPlanId');

        /* Extract the search form input data for the update. */
        var $learningPlanCourseSearchInput = $('#learningPlanCourseSearchInput').val();
        var $learningPlanCourseSort = $('#learningPlanCourseSort').val();
        var $learningPlanCourseCategory = $('#learningPlanCourseCategory').val();
        var $learningPlanCourseAccreditation = $('#learningPlanCourseAccreditation').val();

        /* Extract the active page. */
        var $newPage = $('#learningPlanCourseSearchPagination > ul > li.active').data('page');

        $.request(
            'onSchoolLearningPlanCourseRemove',
            {
                confirm: confirmMessage,
                update: {
                    'school-learning-plans/learning-plan-edit-list': '#learningPlanCardList',
                    'school-learning-plans/learning-plan-course-grid': '#learningPlanCourseSearchResults',
                    'school-learning-plans/learning-plan-course-pagination': '#learningPlanCourseSearchPagination'
                },
                data: {
                    courseId: $courseId,
                    learningPlanId: $learningPlanId,
                    learningPlanCourseSearchInput: $learningPlanCourseSearchInput,
                    learningPlanCourseSort: $learningPlanCourseSort,
                    learningPlanCourseCategory: $learningPlanCourseCategory,
                    learningPlanCourseAccreditation: $learningPlanCourseAccreditation,
                    page: $newPage,
                },
            }
        );
    });
})
