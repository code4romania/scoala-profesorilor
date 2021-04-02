$(document).ready( () => {
    $('.learning-plan-edit-card-remove').on('click', function () {
        let jsScript = $("#learning-plan-edit-list");
        let confirmation = jsScript.attr("data-confirmation");

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
            'onTeacherLearningPlanCourseRemove',
            {
                confirm: confirmation,
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

    $('.learning-plan-edit-card-participate').on('change', function () {
        /* Extract the course and learning plan ID. */
        var $courseId = $(this).data('courseId');
        var $learningPlanId = $(this).data('learningPlanId');

        /* Extract the value of the participated checkbox. */
        var $participated = ($('#participated_' + $courseId).is(":checked")) ? (1) : (0);

        $.request(
            'onTeacherLearningPlanCourseParticipate',
            {
                data: {
                    courseId: $courseId,
                    learningPlanId: $learningPlanId,
                    participated: $participated
                },
            }
        );
    });
})
