function courseSearchFormSubmit(){
    let jsScript = $("#partial-course-search-form");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /* Extract the clicked page. */
    var $newPage = $('#courseSearchPagination > ul > li.active').data('page');

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
}

$(document).ready( () => {
    $('#courseSearchFormContent').on('change', 'select', function () {
        courseSearchFormSubmit();
    });

    $('#courseSearchForm').on('submit', function () {
        courseSearchFormSubmit();

        return false;
    });
})
