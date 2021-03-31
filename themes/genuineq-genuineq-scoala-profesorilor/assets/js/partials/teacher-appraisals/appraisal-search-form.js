function appraisalSearchFormSubmit(){
    /* Extract the clicked page. */
    var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');
    /* Extract the active teacher ID. */
    var $teacherId = $('#appraisalSearchPagination > ul > li.active').data('id');

    /* Select the course select form and send the request */
    $('#appraisalSearchForm').request(
        'onAppraisalSearch',
        {
            update: {
                'teacher-appraisals/appraisal-grid': '#appraisalSearchResults',
                'teacher-appraisals/appraisal-pagination': '#appraisalSearchPagination'
            },
            data: {
                page: $newPage,
                teacherId: $teacherId
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
