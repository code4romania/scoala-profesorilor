$(document).ready( () => {
    function courseSearchFormSubmit() {
        let jsScript = $("#partial-course-search-form");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the active page. */
        var $newPage = $('#courseSearchPagination > ul > li.active').data('page');

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
    }

    $('#courseSearchFormContent').on('change', 'select', function () {
        courseSearchFormSubmit();
    });

    $('#courseSearchForm').on('submit', function () {
        courseSearchFormSubmit();

        return false;
    })
})
