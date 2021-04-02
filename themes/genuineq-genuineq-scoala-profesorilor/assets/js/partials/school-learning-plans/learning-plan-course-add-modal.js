$( document ).ready(function() {
    /* Function that saves the ID of the course that was clicked. */
    $('#learning-plan-course-add-modal').on('show.bs.modal', function (event) {
        $('#courseId').val($(event.relatedTarget).data('courseId'));
        $('#learningPlanId').val($(event.relatedTarget).data('learningPlanId'));
    });

    $('#learning-plan-course-add-modal-add').on('click', function () {
        /* Extract the search form input data for the update. */
        var $learningPlanCourseSearchInput = $('#learningPlanCourseSearchInput').val();
        var $learningPlanCourseSort = $('#learningPlanCourseSort').val();
        var $learningPlanCourseCategory = $('#learningPlanCourseCategory').val();
        var $learningPlanCourseAccreditation = $('#learningPlanCourseAccreditation').val();
        /* Extract the active page. */
        var $newPage = $('#learningPlanCourseSearchPagination > ul > li.active').data('page');

        $('#learning-plan-course-add-modal-form').request(
            'onSchoolLearningPlanCourseAdd',
            {
                update: {
                    'school-learning-plans/learning-plan-edit-list': '#learningPlanCardList',
                    'school-learning-plans/learning-plan-course-grid': '#learningPlanCourseSearchResults',
                    'school-learning-plans/learning-plan-course-pagination': '#learningPlanCourseSearchPagination'
                },
                data: {
                    learningPlanCourseSearchInput: $learningPlanCourseSearchInput,
                    learningPlanCourseSort: $learningPlanCourseSort,
                    learningPlanCourseCategory: $learningPlanCourseCategory,
                    learningPlanCourseAccreditation: $learningPlanCourseAccreditation,
                    page: $newPage,
                },
            }
        );
    });
});
