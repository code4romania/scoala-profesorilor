$(document).ready( () => {
    let jsScript = $("#partial-appraisal-details");
    let appraisal = jsScript.attr('data-appraisal');

    /* MDBOOTSTRAP Limitation: Trigger change value to move all input labels up. */
    $('input.form-control').change();
    $('textarea.form-control').change();
    $('input.custom-file-input').change();

    if ('objectives-set' == appraisal.status) {
        $('.appraisal-approve-objectives').on('click', function () {
            let jsScript = $("#partial-appraisal-details");

            /* Extract the "nonce" script attribute. */
            let cspNonce = jsScript.attr("data-cspNonce");

            /* Extract the appraisal and teacher data. */
            var $appraisalId = $(this).data('appraisalId');
            var $teacherId = $(this).data('teacherId');

            /* Extract the search form input data for the update. */
            var $appraisalSearchInput = $('#appraisalSearchInput').val();
            var $appraisalSort = $('#appraisalSort').val();
            var $appraisalStatus = $('#appraisalStatus').val();
            var $appraisalYear = $('#appraisalYear').val();
            var $appraisalSemester = $('#appraisalSemester').val();
            var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');

            $('#appraisalDetails').request(
                'onSchoolApproveAppraisalObjectives',
                {
                    update: {
                        'school-appraisals/appraisal-details': '#appraisalViewEditDetails',
                        'school-appraisals/appraisal-details-skills': '#appraisalDetailsSkills',
                        'school-appraisals/appraisal-grid': '#appraisalSearchResults',
                        'school-appraisals/appraisal-pagination': '#appraisalSearchPagination'
                    },
                    data: {
                        appraisalId: $appraisalId,
                        teacherId: $teacherId,
                        appraisalSearchInput: $appraisalSearchInput,
                        appraisalSort: $appraisalSort,
                        appraisalStatus: $appraisalStatus,
                        appraisalYear: $appraisalYear,
                        appraisalSemester: $appraisalSemester,
                        newPage: $newPage,
                        nonce: cspNonce
                    },
                }
            );
        });
    } else if ('objectives-approved' == appraisal.status) {
        $('.appraisal-set-skills').on('click', function () {

            let jsScript = $("#partial-appraisal-details");

            /* Extract the "nonce" script attribute. */
            let cspNonce = jsScript.attr("data-cspNonce");
            /* Extract the appraisal and teacher data. */
            var $appraisalId = $(this).data('appraisalId');
            var $teacherId = $(this).data('teacherId');

            /* Extract the search form input data for the update. */
            var $appraisalSearchInput = $('#appraisalSearchInput').val();
            var $appraisalSort = $('#appraisalSort').val();
            var $appraisalStatus = $('#appraisalStatus').val();
            var $appraisalYear = $('#appraisalYear').val();
            var $appraisalSemester = $('#appraisalSemester').val();
            var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');

            $('#appraisalDetails').request(
                'onSchoolSetAppraisalSkills',
                {
                    update: {
                        'school-appraisals/appraisal-details': '#appraisalViewEditDetails',
                        'school-appraisals/appraisal-details-skills': '#appraisalDetailsSkills',
                        'school-appraisals/appraisal-grid': '#appraisalSearchResults',
                        'school-appraisals/appraisal-pagination': '#appraisalSearchPagination'
                    },
                    data: {
                        appraisalId: $appraisalId,
                        teacherId: $teacherId,
                        appraisalSearchInput: $appraisalSearchInput,
                        appraisalSort: $appraisalSort,
                        appraisalStatus: $appraisalStatus,
                        appraisalYear: $appraisalYear,
                        appraisalSemester: $appraisalSemester,
                        newPage: $newPage,
                        nonce: cspNonce
                    },
                }
            );
        });
    } else if ('evaluation-opened' == appraisal.status) {
        $('.appraisal-close').on('click', function () {
            let jsScript = $("#partial-appraisal-details");

            /* Extract the "nonce" script attribute. */
            let cspNonce = jsScript.attr("data-cspNonce");

            /* Extract the appraisal and teacher data. */
            var $appraisalId = $(this).data('appraisalId');
            var $teacherId = $(this).data('teacherId');

            /* Extract the search form input data for the update. */
            var $appraisalSearchInput = $('#appraisalSearchInput').val();
            var $appraisalSort = $('#appraisalSort').val();
            var $appraisalStatus = $('#appraisalStatus').val();
            var $appraisalYear = $('#appraisalYear').val();
            var $appraisalSemester = $('#appraisalSemester').val();
            var $newPage = $('#appraisalSearchPagination > ul > li.active').data('page');

            $('#appraisalDetails').request(
                'onSchoolAppraisalClose',
                {
                    update: {
                        'school-appraisals/appraisal-details': '#appraisalViewEditDetails',
                        'school-appraisals/appraisal-details-skills': '#appraisalDetailsSkills',
                        'school-appraisals/appraisal-grid': '#appraisalSearchResults',
                        'school-appraisals/appraisal-pagination': '#appraisalSearchPagination'
                    },
                    data: {
                        appraisalId: $appraisalId,
                        teacherId: $teacherId,
                        appraisalSearchInput: $appraisalSearchInput,
                        appraisalSort: $appraisalSort,
                        appraisalStatus: $appraisalStatus,
                        appraisalYear: $appraisalYear,
                        appraisalSemester: $appraisalSemester,
                        newPage: $newPage,
                        nonce: cspNonce
                    },
                }
            );
        });
    }

    $('.appraisal-save').on('click', function () {
        let jsScript = $("#partial-appraisal-details");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

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
            'onSchoolAppraisalSave',
            {
                update: {
                    'school-appraisals/appraisal-details': '#appraisalViewEditDetails',
                    'school-appraisals/appraisal-details-skills': '#appraisalDetailsSkills',
                    'school-appraisals/appraisal-grid': '#appraisalSearchResults',
                    'school-appraisals/appraisal-pagination': '#appraisalSearchPagination'
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
                    newPage: $newPage,
                    nonce: cspNonce
                },
            }
        );
    });
})
