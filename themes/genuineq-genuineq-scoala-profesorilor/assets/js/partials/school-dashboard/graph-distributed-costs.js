$( document ).ready(function() {
    let jsScript = $('#graph-distributed-costs');
    let distributedLabels = JSON.parse(jsScript.attr('data-distributedLabels'));
    let distributedCostsLabels = JSON.parse(jsScript.attr('data-distributedCostsLabels'));
    let distributedCosts = JSON.parse(jsScript.attr('data-distributedCosts'));
    let distributedCostsColor = JSON.parse(jsScript.attr('data-distributedCostsColor'));
    let distributedCostsBorderColor = JSON.parse(jsScript.attr('data-distributedCostsBorderColor'));

    console.log(distributedCostsLabels);
    console.log(distributedCosts);

    var ctxB = document.getElementById("barChart").getContext('2d');

    var myBarChart = new Chart(ctxB, {
        type: 'bar',
        data: {
            labels: [distributedLabels['0'], distributedLabels['1'], distributedLabels['2'], distributedLabels['3'], distributedLabels['4'], distributedLabels['5']],
            datasets: [
                distributedCostsLabels.forEach(distributedCostsLabel => {
                    return {
                        label: distributedCostsLabel,
                        data: [distributedCosts[distributedCostsLabel]['0'], distributedCosts[distributedCostsLabel]['1'], distributedCosts[distributedCostsLabel]['2'], distributedCosts[distributedCostsLabel]['3'], distributedCosts[distributedCostsLabel]['4'], distributedCosts[distributedCostsLabel]['5'] ],
                        backgroundColor: [
                            distributedCostsColor[distributedCostsLabel],
                            distributedCostsColor[distributedCostsLabel],
                            distributedCostsColor[distributedCostsLabel],
                            distributedCostsColor[distributedCostsLabel],
                            distributedCostsColor[distributedCostsLabel],
                            distributedCostsColor[distributedCostsLabel]
                        ],
                        borderColor: [
                            distributedCostsBorderColor[distributedCostsLabel],
                            distributedCostsBorderColor[distributedCostsLabel],
                            distributedCostsBorderColor[distributedCostsLabel],
                            distributedCostsBorderColor[distributedCostsLabel],
                            distributedCostsBorderColor[distributedCostsLabel],
                            distributedCostsBorderColor[distributedCostsLabel]
                        ],
                        borderWidth: 1
                    }
                })
            ]
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
