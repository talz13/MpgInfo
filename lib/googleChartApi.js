// Load the Visualization API and the chart packages.
google.load('visualization', '1', {'packages': ['corechart', 'controls', 'table']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawCharts);

function drawCharts() {
    var data = getData();
    if ($('#chart_div_mpg').length) {
        drawMpgChart(data);
    }
    if ($('#chart_div_price').length) {
        drawPriceChart(data);
    }
    if ($('#table_div').length) {
        drawTable(data);
    }
}

function getData() {
    var jsonData = $.ajax({
        url: "/lib/testData.php",
        data: "g=table",
        //data: JSON.stringify(columns),
        type: "POST",
        dataType: "json",
        async: false
    }).responseText;
    return new google.visualization.DataTable(jsonData);
//    return data;
}

function drawMpgChart(data) {
    var element = document.getElementById('chart_div_mpg');
    var view = new google.visualization.DataView(data);
    view.setColumns([{
            type: 'date',
            label: data.getColumnLabel(0),
            calc: function (dt, row) {
                return new Date(dt.getValue(row, 0));
            }
        }, 3]);
    var minMaxMpg = view.getColumnRange(1);

    var options = {
//        vAxis: {title: 'MPG', minValue: minMaxMpg[0], maxValue: minMaxMpg[1]},
        vAxis: {minValue: minMaxMpg[0], maxValue: minMaxMpg[1]},
        width: 800,
        height: 400,
        animation: {duration: 1000, easing: 'inAndOut'},
        explorer: {actions: ['dragToZoom', 'dragToPan', 'rightClickToReset'], axis: 'horizontal', keepInBounds: true},
        chartArea: {left: 40, top: 50, width: 680, height: 300}
    };
    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.LineChart(element);
    // Show the hidden chart div before drawing:
    element.style.display = 'block';
    chart.draw(view, options);
}

function drawPriceChart(data) {
    var element = document.getElementById('chart_div_price');
    var view = new google.visualization.DataView(data);
    view.setColumns([{
            type: 'date',
            label: data.getColumnLabel(0),
            calc: function (dt, row) {
                return new Date(dt.getValue(row, 0));
            }
        }, 4]);

    var minMaxVals = view.getColumnRange(1);

    var options = {
//        vAxis: {title: 'Price', minValue: minMaxVals[0], maxValue: minMaxVals[1], format: '$#.###'},
        vAxis: {minValue: minMaxVals[0], maxValue: minMaxVals[1], format: '$#.###'},
        width: 800,
        height: 400,
        explorer: {actions: ['dragToZoom', 'dragToPan', 'rightClickToReset'], axis: 'horizontal', keepInBounds: true},
        chartArea: {left: 40, top: 50, width: 680, height: 300}
    };
    var chart = new google.visualization.LineChart(element);
    element.style.display = 'block';
    chart.draw(view, options);
}

function drawTable(data) {
    var element = document.getElementById('table_div');
    var fmt2digCur = new google.visualization.NumberFormat({
        prefix: '$',
        fractionDigits: 2
    });
    var fmt3digCur = new google.visualization.NumberFormat({
        prefix: '$',
        fractionDigits: 3
    });
    var fmt1dig = new google.visualization.NumberFormat({
        fractionDigits: 1
    });
    var fmt3dig = new google.visualization.NumberFormat({
        fractionDigits: 1
    });

    fmt1dig.format(data, 1);
    fmt3dig.format(data, 2);
    fmt1dig.format(data, 3);
    fmt3digCur.format(data, 4);
    fmt2digCur.format(data, 5);

    var table = new google.visualization.Table(element);
    element.style.display = 'block';
    table.draw(data, {showRowNumber: false, height: '400px', width: '800px', sortColumn: 0, sortAscending: false});
}
