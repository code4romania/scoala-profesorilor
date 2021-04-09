$( document ).ready(function() {
    let jsScript = $("#partial-graph-spent-budget");

    let budgetTotalLabel = jsScript.attr("data-budgetTotalLabel");
    let budgetSpentLabel = jsScript.attr("data-budgetSpentLabel");
    let budgetTotal = jsScript.attr("data-budgetTotal");
    let budgetSpent = jsScript.attr("data-budgetSpent");

    var ctxP = document.getElementById("spentBudget").getContext('2d');

    var myPieChart = new Chart(ctxP, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: [budgetTotalLabel, budgetSpentLabel],
            datasets: [{
                data: [budgetTotal, budgetSpent],
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
