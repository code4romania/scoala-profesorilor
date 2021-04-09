$(document).ready( () => {
    let jsScript = $("#partial-course-pagination");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    $('.course-search-pagination').on('click', function () {
        /* Extract the clicked page. */
        var $newPage = $(this).data('page');

        /* Select the course select form and send the request */
        $('#courseSearchForm').request(
            'onSchoolCourseSearch',
            {
                update: {
                    'school-courses/course-grid': '#courseSearchResults',
                    'school-courses/course-pagination': '#courseSearchPagination',
                    'school-courses/course-summary': '#courseSummary'
                },
                data: {
                    page: $newPage,
                    nonce: cspNonce
                }
            }
        );
    });
})
