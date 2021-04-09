$(document).ready( () => {
    $('.appraisal-search-pagination').on('click', function () {
        let jsScript = $("#partial-appraisal-pagination");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        /* Extract the clicked page. */
        var $newPage = $(this).data('page');
        /* Extract the active teacher plan ID. */
        var $teacherId = $(this).data('id');

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
    });
})
