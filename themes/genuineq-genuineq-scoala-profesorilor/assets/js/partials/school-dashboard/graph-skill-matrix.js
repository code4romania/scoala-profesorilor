$( document ).ready(function() {
    let jsScript = $('#partial-graph-skill-matrix');

    let skillSemestersLabels = JSON.parse(jsScript.attr('data-skillSemestersLabels'));
    let skillSemestersNames = JSON.parse(jsScript.attr('data-skillSemestersNames'));
    let skillSemestersValues = JSON.parse(jsScript.attr('data-skillSemestersValues'));
    let skillSemestersBackgroundColors = JSON.parse(jsScript.attr('data-skillSemestersBackgroundColors'));
    let skillSemestersBorderColor = JSON.parse(jsScript.attr('data-skillSemestersBorderColor'));

    var ctxL = document.getElementById("skillMatrix").getContext('2d');

    /** Construnct the graph labels. */
    let labels = [];
    for (const [key, value] of Object.entries(skillSemestersLabels)) {
        labels.push(skillSemestersLabels[key]);
    }

    /** Construnct the graph datasets. */
    let datasets = [];
    for (const [key, value] of Object.entries(skillSemestersNames)) {
        let data = [];
        for (const [dataKey, dataValue] of Object.entries(skillSemestersValues[key])) {
            data.push(dataValue);
        }

        datasets.push({
            label: value,
            data: data,
            backgroundColor: [ skillSemestersBackgroundColors[key] ],
            borderColor: [ skillSemestersBorderColor[key] ],
            borderWidth: 2
        });
    }

    var myLineChart = new Chart(ctxL, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true
        }
    });
});
