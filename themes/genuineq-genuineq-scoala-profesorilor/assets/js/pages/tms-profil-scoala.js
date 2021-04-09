$( document ).ready(function() {
    let jsScript = $("#page-tms-profil-scoala");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /** Display the tab specified inside the URL. */
    const tmsSchoolProfileAnchor = window.location.hash;
    $(`a[href="${tmsSchoolProfileAnchor}"]`).tab('show');

    /** Extract the notifications. */
    $.request(
        'onViewNotifications',
        {
            update: {'school-profile/notifications-tab': '#notifications-tab-content'},
            data: {nonce: cspNonce}
        }
    );


    $('.learning-plan-course-search-pagination').on('click', function () {
        let jsScript = $("#page-tms-profil-scoala");

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


    $('#teachers-tab-nav').on('click', function () {
        let jsScript = $("#page-tms-profil-scoala");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Send the view request */
        $.request(
            'onDisplayTeachers',
            {
                update: {'school-profile/teachers-tab': '#teachers-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });


    $('#dashboard-tab-nav').on('click', function () {
        let jsScript = $("#page-tms-profil-scoala");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Send the view request */
        $.request(
            'onSchoolDashboardView',
            {
                update: {'school-profile/dashboard-tab': '#dashboard-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });


    $('#courses-tab-nav').on('click', function () {
        let jsScript = $("#page-tms-profil-scoala");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Send the view request */
        $.request(
            'onSchoolViewCourses',
            {
                update: {'school-profile/courses-tab': '#courses-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });


    $('#notifications-tab-nav').on('click', function () {
        let jsScript = $("#page-tms-profil-scoala");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Send the view request */
        $.request(
            'onViewNotifications',
            {
                update: {'school-profile/notifications-tab': '#notifications-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });


    $('#reports-tab-nav').on('click', function () {
        let jsScript = $("#page-tms-profil-scoala");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Send the view request */
        $.request(
            'onSchoolViewReports',
            {
                update: {'school-profile/reports-tab': '#reports-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });
});
