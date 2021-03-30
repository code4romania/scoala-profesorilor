$(document).ready( () => {
    $('.appraisal-search-pagination').on('click', function () {
        /* Extract the clicked page. */
        var $newPage = $(this).data('page');
        /* Extract the active teacher plan ID. */
        var $teacherId = $(this).data('id');

        /* Select the course select form and send the request */
        $('#appraisalSearchForm').request(
            'onAppraisalSearch',
            {
                update: {
                    'school-appraisals/appraisal-grid': '#appraisalSearchResults',
                    'school-appraisals/appraisal-pagination': '#appraisalSearchPagination'
                },
                data: {
                    page: $newPage,
                    teacherId: $teacherId
                }
            }
        );
    });
})
