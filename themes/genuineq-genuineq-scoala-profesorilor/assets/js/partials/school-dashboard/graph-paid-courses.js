$( document ).ready(function() {
    let jsScript = $("#graph-paid-courses");
    let schoolPaidCoursesLabel = jsScript.attr("data-schoolPaidCoursesLabel");
    let teachersPaidCoursesLabel = jsScript.attr("data-teachersPaidCoursesLael");
    let teachersPaidCourses = jsScript.attr("data-teachersPaidCourses");
    let schoolPaidCourses = jsScript.attr("data-schoolPaidCourses");

    var ctxP = document.getElementById("paidCourses").getContext('2d');

    var myPieChart = new Chart(ctxP, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: [schoolPaidCoursesLabel, teachersPaidCoursesLabel],
            datasets: [{
                data: [schoolPaidCourses, teachersPaidCourses],
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
