$(document).ready( () => {
    $('#email_notifications').on('change', function () {
        let jsScript = $("#partial-notifications-tab");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        var $emailNotifications = ($('#email_notifications').prop('checked')) ? (1) : (0);

        $.request(
            'onEmailNotificationsUpdate',
            {
                data: {
                    emailNotifications: $emailNotifications,
                    nonce: cspNonce
                }
            }
        );
    });


    $('.btn-mark-as-read-notification').on('click', function () {
        let jsScript = $("#partial-notifications-tab");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /** Extract the request data. */
        let notificationId = $(this).data('notificationId');

        $.request(
            'onMarkNotificationAsRead',
            {
                update: {
                    'teacher-notifications/list': '#notificationsList',
                    'navbar-notifications': '#notificationsSummary'
                },
                data: {
                    notificationId: notificationId,
                    nonce: cspNonce
                }
            }
        );
    });


    $('.btn-decline-course-request').on('click', function () {
        let jsScript = $("#partial-notifications-tab");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /** Extract the request data. */
        let courseId = $(this).data('notificationId');
        let learningPlanId = $(this).data('courseId');
        let notificationId = $(this).data('learningPlanId');

        $.request(
            'onTeacherLearningPlanRequestDecline',
            {
                update: {
                    'teacher-notifications/list': '#notificationsList',
                    'navbar-notifications': '#notificationsSummary'
                },
                data: {
                    courseId: courseId,
                    learningPlanId: learningPlanId,
                    notificationId: notificationId,
                    nonce: cspNonce
                }
            }
        );
    });


    $('.btn-accept-course-request').on('click', function () {
        let jsScript = $("#partial-notifications-tab");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /** Extract the request data. */
        let courseId = $(this).data('notificationId');
        let learningPlanId = $(this).data('courseId');
        let notificationId = $(this).data('learningPlanId');

        $.request(
            'onTeacherLearningPlanRequestAccept',
            {
                update: {
                    'teacher-notifications/list': '#notificationsList',
                    'navbar-notifications': '#notificationsSummary'
                },
                data: {
                    courseId: courseId,
                    learningPlanId: learningPlanId,
                    notificationId: notificationId,
                    nonce: cspNonce
                }
            }
        );
    });
});
