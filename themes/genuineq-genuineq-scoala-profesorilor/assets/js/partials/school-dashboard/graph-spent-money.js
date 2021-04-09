$( document ).ready(function() {
    let jsScript = $("#partial-graph-spent-money");

    let schoolSpentMoneyLabel = jsScript.attr("data-schoolSpentMoneyLabel");
    let teachersSpentMoneyLabel = jsScript.attr("data-teachersSpentMoneyLabel");
    let schoolSpentMoney = jsScript.attr("data-schoolSpentMoney");
    let teachersSpentMoney = jsScript.attr("data-teachersSpentMoney");

    var ctxP = document.getElementById("spentMoney").getContext('2d');

    var myPieChart = new Chart(ctxP, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: [schoolSpentMoneyLabel, teachersSpentMoneyLabel],
            datasets: [{
                data: [schoolSpentMoney, teachersSpentMoney],
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
