$(document).ready( () => {
    $('#distributed-costs-compare').on('click', function () {
        var $year = $('#schoolYears').val();
        var $semester = $('#schoolSemesters').val();

        $.request(
            'onSchoolCostDistributionCompare',
            {
                update: {
                    'school-dashboard/graph-distributed-costs': '#costsDistribution'
                },
                data: {
                    year: $year,
                    semester: $semester
                }
            }
        );

    });
})
