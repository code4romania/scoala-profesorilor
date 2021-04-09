function appraisalSearchFormSubmit(){
    let jsScript = $("#partial-appraisal-search-form");

    /* Extract the "nonce" script attribute. */
    let cspNonce = jsScript.attr("data-cspNonce");

    /* Extract the clicked page. */
    var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');
    /* Extract the active teacher ID. */
    var $teacherId = $('#appraisalSearchPagination > ul > li.active').data('id');

    /* Select the course select form and send the request */
    $('#appraisalSearchForm').request(
        'onSchoolAppraisalSearch',
        {
            update: {
                'school-appraisals/appraisal-grid': '#appraisalSearchResults',
                'school-appraisals/appraisal-pagination': '#appraisalSearchPagination'
            },
            data: {
                page: $newPage,
                teacherId: $teacherId,
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
});
