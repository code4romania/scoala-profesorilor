$(document).ready( () => {
    $('.teacher-search-pagination').on('click', function () {
        /* Extract the clicked page. */
        var $newPage = $(this).data('page');

        console.log("search-pagination");

        /* Select the teacher select form and send the request */
        $('#teacherSearchForm').request(
            'onTeacherSearch',
            {
                update: {'school-teachers/teacher-grid': '#teacherSearchResults', 'school-teachers/teacher-search-pagination': '#teacherSearchPagination'},
                data: {page: $newPage}
            }
        );
    });
})
