$( document ).ready(function() {
    let jsScript = $("#graph-teacher-seniorities");
    let senioritiesLabels1 = jsScript.attr("data-senioritiesLabels1");
    let senioritiesLabels2 = jsScript.attr("data-senioritiesLabels2");
    let senioritiesLabels3 = jsScript.attr("data-senioritiesLabels3");
    let senioritiesLabels4 = jsScript.attr("data-senioritiesLabels4");
    let senioritiesLabels5 = jsScript.attr("data-senioritiesLabels5");
    let seniorities1 = jsScript.attr("data-seniorities1");
    let seniorities2 = jsScript.attr("data-seniorities2");
    let seniorities3 = jsScript.attr("data-seniorities3");
    let seniorities4 = jsScript.attr("data-seniorities4");
    let seniorities5 = jsScript.attr("data-seniorities5");

    var ctxP = document.getElementById("teacherSeniorities").getContext('2d');

    var myPieChart = new Chart(ctxP, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: [senioritiesLabels1, senioritiesLabels2, senioritiesLabels3, senioritiesLabels4, senioritiesLabels5],
            datasets: [{
                data: [seniorities1, seniorities2, seniorities3, seniorities4, seniorities5],
                backgroundColor: ["#e898a6", "#86645b", "#e36924", "#e69983", "#c02427"],
                hoverBackgroundColor: ["#CA7A88", "#68463D", "#C54B06", "#C87B65", "#A20609"]
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
