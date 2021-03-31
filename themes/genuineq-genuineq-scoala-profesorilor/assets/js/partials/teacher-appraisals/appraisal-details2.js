$(document).ready( () => {
    $('.appraisal-set-objectives').on('click', function () {
        /* Extract the appraisal and teacher data. */
        var $appraisalId = $(this).data('appraisalId');
        var $teacherId = $(this).data('teacherId');

        /* Extract the search form input data for the update. */
        var $appraisalSearchInput = $('#appraisalSearchInput').val();
        var $appraisalSchools = $('#appraisalSchools').val();
        var $appraisalSort = $('#appraisalSort').val();
        var $appraisalStatus = $('#appraisalStatus').val();
        var $appraisalYear = $('#appraisalYear').val();
        var $appraisalSemester = $('#appraisalSemester').val();
        var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');

        $('#appraisalDetails').request(
            'onTeacherSetAppraisalObjectives',
            {
                update: {
                    'teacher-appraisals/appraisal-details': '#appraisalViewEditDetails',
                    'teacher-appraisals/appraisal-grid': '#appraisalSearchResults',
                    'teacher-appraisals/appraisal-pagination': '#appraisalSearchPagination'
                },
                data: {
                    appraisalId: $appraisalId,
                    teacherId: $teacherId,
                    appraisalSearchInput: $appraisalSearchInput,
                    appraisalSchools: $appraisalSchools,
                    appraisalSort: $appraisalSort,
                    appraisalStatus: $appraisalStatus,
                    appraisalYear: $appraisalYear,
                    appraisalSemester: $appraisalSemester,
                    newPage: $newPage
                },
            }
        );
    });

    $('.appraisal-save-objectives').on('click', function () {
        /* Extract the appraisal and teacher data. */
        var $appraisalId = $(this).data('appraisalId');
        var $teacherId = $(this).data('teacherId');

        /* Extract the search form input data for the update. */
        var $appraisalSearchInput = $('#appraisalSearchInput').val();
        var $appraisalSchools = $('#appraisalSchools').val();
        var $appraisalSort = $('#appraisalSort').val();
        var $appraisalStatus = $('#appraisalStatus').val();
        var $appraisalYear = $('#appraisalYear').val();
        var $appraisalSemester = $('#appraisalSemester').val();
        var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');

        $('#appraisalDetails').request(
            'onTeacherAppraisalSave',
            {
                update: {
                    'teacher-appraisals/appraisal-details': '#appraisalViewEditDetails',
                    'teacher-appraisals/appraisal-grid': '#appraisalSearchResults',
                    'teacher-appraisals/appraisal-pagination': '#appraisalSearchPagination'
                },
                data: {
                    appraisalId: $appraisalId,
                    teacherId: $teacherId,
                    appraisalSearchInput: $appraisalSearchInput,
                    appraisalSchools: $appraisalSchools,
                    appraisalSort: $appraisalSort,
                    appraisalStatus: $appraisalStatus,
                    appraisalYear: $appraisalYear,
                    appraisalSemester: $appraisalSemester,
                    newPage: $newPage
                },
            }
        );
    });
})
