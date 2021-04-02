$( document ).ready(function() {
    /** Display the tab specified inside the URL. */
    const tmsTeacherProfileAnchor = window.location.hash;
    $(`a[href="${tmsTeacherProfileAnchor}"]`).tab('show');

    /** Extract the notifications. */
    $.request(
        'onViewNotifications',
        {
            update: {
                'teacher-profile/notifications-tab': '#notifications-tab-content'
            }
        }
    );
});
