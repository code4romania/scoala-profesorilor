$( document ).ready(function() {
    let jsScript = $('#partial-graph-distributed-costs');

    let distributedLabels = JSON.parse(jsScript.attr('data-distributedLabels'));
    let distributedCostsLabels = JSON.parse(jsScript.attr('data-distributedCostsLabels'));
    let distributedCosts = JSON.parse(jsScript.attr('data-distributedCosts'));
    let distributedCostsColor = JSON.parse(jsScript.attr('data-distributedCostsColor'));
    let distributedCostsBorderColor = JSON.parse(jsScript.attr('data-distributedCostsBorderColor'));

    var ctxB = document.getElementById("barChart").getContext('2d');

    /** Construnct the graph labels. */
    let labels = [];
    for (const [key, value] of Object.entries(distributedLabels)) {
        labels.push(distributedLabels[key]);
    }

    /** Construnct the graph datasets. */
    let datasets = [];
    for (const [key, value] of Object.entries(distributedCostsLabels)) {
        datasets.push({
            label: value,
            data: [
                distributedCosts[key]['0'],
                distributedCosts[key]['1'],
                distributedCosts[key]['2'],
                distributedCosts[key]['3'],
                distributedCosts[key]['4'],
                distributedCosts[key]['5']
            ],
            backgroundColor: [
                distributedCostsColor[key],
                distributedCostsColor[key],
                distributedCostsColor[key],
                distributedCostsColor[key],
                distributedCostsColor[key],
                distributedCostsColor[key]
            ],
            borderColor: [
                distributedCostsBorderColor[key],
                distributedCostsBorderColor[key],
                distributedCostsBorderColor[key],
                distributedCostsBorderColor[key],
                distributedCostsBorderColor[key],
                distributedCostsBorderColor[key]
            ],
            borderWidth: 1
        });
    }

    var myBarChart = new Chart(ctxB, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});
