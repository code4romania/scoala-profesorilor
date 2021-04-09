$(document).ready( () => {
    $('.teacher-search-pagination').on('click', function () {
        let jsScript = $("#partial-teacher-search-pagination");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the clicked page. */
        var $newPage = $(this).data('page');

        /* Select the teacher select form and send the request */
        $('#teacherSearchForm').request(
            'onTeacherSearch',
            {
                update: {
                    'school-teachers/teacher-grid': '#teacherSearchResults',
                    'school-teachers/teacher-search-pagination': '#teacherSearchPagination'
                },
                data: {
                    page: $newPage,
                    nonce: cspNonce
                }
            }
        );
    });
})
