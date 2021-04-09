$(document).ready( () => {
    $('.course-search-pagination').on('click', function () {
        let jsScript = $("#partial-course-search-pagination");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the clicked page. */
        var $newPage = $(this).data('page');

        /* Select the course select form and send the request */
        $('#courseSearchForm').request(
            'onCourseSearch',
            {
                update: {
                    'course-grid': '#courseSearchResults',
                    'course-search-pagination': '#courseSearchPagination'
                },
                data: {
                    page: $newPage,
                    nonce: cspNonce
                }
            }
        );
    });
})
