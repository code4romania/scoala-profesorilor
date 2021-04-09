function teacherSearchFormSubmit(){
    let jsScript = $("#partial-teacher-search-form");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /* Extract the clicked page. */
    var $newPage = $('#teacherSearchPagination > ul > li.active').data('page');

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
}

$(document).ready( () => {
    $('#teacherSearchFormContent').on('change', 'select', function () {
        teacherSearchFormSubmit();
    });

    $('#teacherSearchForm').on('submit', function () {
        teacherSearchFormSubmit();

        return false;
    })
})
