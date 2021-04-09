$( document ).ready(function() {
    let jsScript = $('#partial-graph-budget-total');

    let schoolBudgetLabel = jsScript.attr('data-schoolLabel');
    let teachersBudgetLabel = jsScript.attr('data-teachersLabel');
    let schoolBudget = jsScript.attr('data-schoolBudget');
    let teachersBudget = jsScript.attr('data-teachersBudget');

    var ctxP = document.getElementById("budgetTotal").getContext('2d');

    var myPieChart = new Chart(ctxP, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: [schoolBudgetLabel, teachersBudgetLabel],
            datasets: [{
                data: [schoolBudget, teachersBudget],
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
                            sum += parseInt(data);
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
