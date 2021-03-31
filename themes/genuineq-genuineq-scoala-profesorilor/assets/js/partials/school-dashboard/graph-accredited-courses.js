$( document ).ready(function() {
    let jsScript = $('#graph-accredited-courses');
    let accreditedCoursesLabel = jsScript.attr('data-accreditedLabel');
    let noncreditedCoursesLabel = jsScript.attr('data-noncreditedLabel');
    let accreditedCourses = jsScript.attr('data-accreditedCourses');
    let noncreditedCourses = jsScript.attr('data-noncreditedCourses');

    var ctxP = document.getElementById("accreditedCourses").getContext('2d');

    var myPieChart = new Chart(ctxP, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: [accreditedCoursesLabel, noncreditedCoursesLabel],
            datasets: [{
                data: [accreditedCourses, noncreditedCourses],
                backgroundColor: ["#e898a6", "#86645b"],
                hoverBackgroundColor: ["#CA7A88", "#68463D"]
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    boxWidth: 10
                }
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value * 100 / sum).toFixed(2) + "%";
                        return percentage;
                    },
                    color: 'white',
                    labels: {
                        title: {
                                font: {
                                    size: '16'
                            }
                        }
                    }
                }
            }
        }
    });
});
