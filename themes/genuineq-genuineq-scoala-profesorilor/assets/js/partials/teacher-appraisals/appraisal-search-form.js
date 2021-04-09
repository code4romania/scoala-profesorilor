function appraisalSearchFormSubmit() {
    let jsScript = $("#partial-appraisal-search-form");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /* Extract the clicked page. */
    var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');
    /* Extract the active teacher ID. */
    var $teacherId = $('#appraisalSearchPagination > ul > li.active').data('id');

    /* Select the course select form and send the request */
    $('#appraisalSearchForm').request(
        'onTeacherAppraisalSearch',
        {
            update: {
                'teacher-appraisals/appraisal-grid': '#appraisalSearchResults',
                'teacher-appraisals/appraisal-pagination': '#appraisalSearchPagination'
            },
            data: {
                page: $newPage,
                nonce: cspNonce
            }
        }
    );
}

$(document).ready( () => {
    $('#appraisalSearchFormContent').on('change', 'select', function () {
        appraisalSearchFormSubmit();
    });

    $('#appraisalSearchForm').on('submit', function () {
        appraisalSearchFormSubmit();

        return false;
    })
})
