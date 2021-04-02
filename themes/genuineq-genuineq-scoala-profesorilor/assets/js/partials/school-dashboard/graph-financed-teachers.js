$( document ).ready(function() {
    let jsScript = $("#graph-financed-teachers");
    let teachersFiancedLabel = jsScript.attr("data-teachersFiancedLabel");
    let teachersNotFiancedLabel = jsScript.attr("data-teachersNotFiancedLabel");
    let teachersFianced = jsScript.attr("data-teachersFianced");
    let teachersNotFianced = jsScript.attr("data-teachersNotFianced");

    var ctxP = document.getElementById("financedTeachers").getContext('2d');

    var myPieChart = new Chart(ctxP, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: [teachersFiancedLabel, teachersNotFiancedLabel],
            datasets: [{
                data: [teachersFianced, teachersNotFianced],
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
