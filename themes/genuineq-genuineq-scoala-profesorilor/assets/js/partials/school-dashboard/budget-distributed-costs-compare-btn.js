$(document).ready( () => {
    $('#distributed-costs-compare').on('click', function () {
        let jsScript = $("#partial-budget-distributed-costs-compare-btn");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        var $year = $('#schoolYears').val();
        var $semester = $('#schoolSemesters').val();

        $.request(
            'onSchoolCostDistributionCompare',
            {
                update: {'school-dashboard/graph-distributed-costs': '#costsDistribution'},
                data: {
                    year: $year,
                    semester: $semester,
                    nonce: cspNonce
                }
            }
        );
    });

    $('#distributed-costs-reset').on('click', function () {
        let jsScript = $("#partial-budget-distributed-costs-compare-btn");

        /* Extract the "nonce" script attribute. */
        let cspNonce = jsScript.attr("data-cspNonce");

        $.request(
            'onSchoolCostDistributionReset',
            {
                update: {
                    'school-dashboard/graph-distributed-costs': '#costsDistribution'
                },
                data: {nonce: cspNonce}
            }
        );
    });
})
