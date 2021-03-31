$(document).ready( () => {
    $('.learning-plan-add-course').on('click', function () {
        /* Extract the course and learning plan data. */
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
            'onTeacherLearningPlanCourseAdd',
            {
                update: {
                    'teacher-learning-plans/learning-plan-edit-list': '#learningPlanCardList',
                    'teacher-learning-plans/learning-plan-course-grid': '#learningPlanCourseSearchResults',
                    'teacher-learning-plans/learning-plan-course-pagination': '#learningPlanCourseSearchPagination'
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
