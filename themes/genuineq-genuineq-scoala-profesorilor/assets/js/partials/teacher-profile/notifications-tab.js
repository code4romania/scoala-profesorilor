$(document).ready( () => {
    $('#email_notifications').on('change', function () {
        var $emailNotifications = ($('#email_notifications').prop('checked')) ? (1) : (0);

        $.request(
            'onEmailNotificationsUpdate',
            {
                update: {'course-grid': '#courseSearchResults', 'course-search-pagination': '#courseSearchPagination'},
                data: {emailNotifications: $emailNotifications}
            }
        );
    });
});
