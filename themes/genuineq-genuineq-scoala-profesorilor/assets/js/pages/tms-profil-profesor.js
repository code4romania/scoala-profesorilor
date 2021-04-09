$( document ).ready(function() {
    let jsScript = $("#page-tms-profil-profesor");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /** Display the tab specified inside the URL. */
    const tmsTeacherProfileAnchor = window.location.hash;
    $(`a[href="${tmsTeacherProfileAnchor}"]`).tab('show');

    /** Function that updated the notifications tab. */
    function updateNotifications() {
        /** Extract the notifications. */
        $.request(
            'onViewNotifications',
            {
                update: {'teacher-profile/notifications-tab': '#notifications-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    }

    /** Update notifications on first load. */
    updateNotifications();

    /** Update notificatins each time the tab is displayed. */
    $('#notifications-tab-nav').on('click', function () {
        updateNotifications();
    });

    $('#learning-plan-tab-nav').on('click', function () {
        /* Send the view request */
        $.request(
            'onTeacherViewLearningPlan',
            {
                update: {'teacher-profile/learning-plan-tab': '#learning-plan-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });

    $('#budget-tab-nav').on('click', function () {
        /* Send the view request */
        $.request(
            'onTeacherViewBudget',
            {
                update: {'teacher-profile/budget-course-plan-tab': '#budget-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });

    $('#appraisals-tab-nav').on('click', function () {
        /* Send the view request */
        $.request(
            'onTeacherAppraisalsView',
            {
                update: {'teacher-profile/appraisals-tab': '#appraisals-tab-content'},
                data: {nonce: cspNonce}
            }
        );
    });
});
