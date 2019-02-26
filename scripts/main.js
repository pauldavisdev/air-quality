// Load the Visualization API and the corechart package.
google.charts.load('current', { 'packages': ['corechart'] });

// holds the last data returned by get_scatter_data.php
var scatterData;

// holds the last data returned by get_line_data.php
var lineData;

//on document load, populate dropdown boxes
window.onload = function () {
    getScatterData();
}

// on document ready, populate dropdown
$(document).ready(function () {
    setLocationOptions();
    setTimeOptions();
});

$('select').change(function () {
    console.log($('#location').val());
    getScatterData();
    getLineData();
});

// redraws chart upon resize - makes the page feel more responsive
$(window).on('resize', function () {
    var scatter = document.getElementById('scatter_chart_div');
    if (scatter.style.display === 'none') {
        drawLineChart();
    } else {
        drawScatterChart();
    }
});

// add event listener to datepicker
const datepicker = document.getElementById('datepicker');

datepicker.addEventListener("change", function () {
    getLineData();
});

// this function handles switching between the scatter and line charts
$("#chart-select-buttons :input").change(function () {
    var scatter = document.getElementById('scatter_chart_div');
    var line = document.getElementById('line_chart_div');
    var scatterOptions = document.getElementsByClassName('scatter-options');
    var lineOptions = document.getElementsByClassName('line-options');
    if (scatter.style.display === 'none') {
        getScatterData();
        scatter.style.display = 'block';
        line.style.display = 'none';
        for (let index = 0; index < scatterOptions.length; index++) {
            scatterOptions[index].style.display = 'block';
        }
        for (let index = 0; index < lineOptions.length; index++) {
            lineOptions[index].style.display = 'none';
        }

    } else {
        getLineData();
        line.style.display = 'block';
        scatter.style.display = 'none';
        for (let index = 0; index < scatterOptions.length; index++) {
            scatterOptions[index].style.display = 'none';
        }
        for (let index = 0; index < lineOptions.length; index++) {
            lineOptions[index].style.display = 'block';
        }

    }
});

// populate location dropdown box with all xml file locations
function setLocationOptions() {
    $.ajax({
        type: "GET",
        url: "app/get_locations.php",
        success: function (data) {
            // decode JSON received back from get_readings.php
            data = JSON.parse(data);

            var locationString = '';

            // generate dropdown option HTML based on data
            for (var i = 0; i < data.length; i++) {
                locationString += "<option value='" + data[i] + "'>" + data[i] + "</option>";
            }

            console.log(locationString);

            // append generated HTML to dropdown list
            $('#location').append(locationString);
        }
    });
}

function setTimeOptions() {
    // below code populates times dropdown list
    // begin: code copied from stack overflow answer found at https://codereview.stackexchange.com/a/121097
    var quarterHours = ["00", "15", "30", "45"];

    var times = [];
    for (var i = 0; i < 24; i++) {
        for (var j = 0; j < 4; j++) {
            // Using slice() with negative index => You get always (the last) two digit numbers.
            times.push(('0' + i).slice(-2) + ":" + quarterHours[j]);
        }
    }
    // end: copied from stack overflow answer found at https://codereview.stackexchange.com/a/121097

    var timeString = '';

    for (var i = 0; i < times.length; i++) {
        timeString += "<option value='" + times[i] + "'> " + times[i] + "</option>";
    }

    $('#time').append(timeString);
}

/* Scatter Graph Functions */
function getScatterData() {
    $.ajax({
        url: 'app/get_scatter_data.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify({ "location": $('#location').val(), "year": $('#year').val(), "time": $('#time').val() }),
        success: function (data) {
            scatterData = data;
            drawScatterChart()
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            alert(err.message);
        }
    });
}

// draw scatter chart to scatter_chart_div
function drawScatterChart() {

    var result = [];
    // format date string as date and no2 value as number
    for (var i in scatterData) {
        var from = i.split("/")
        var f = new Date(from[2], from[1] - 1, from[0])
        result.push([f, parseInt(scatterData[i])]);
    }

    console.log(result);
    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('date', 'Date');
    data.addColumn('number', 'no2 reading');
    data.addRows(result);

    // Set chart options
    var options = {
        'title': 'Air Quality',
        'hAxis': { title: 'Date' },
        'vAxis': { title: 'no2 level' },
        'height': 600
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.ScatterChart(document.getElementById('scatter_chart_div'));
    chart.draw(data, options);
}

/* Line Graph Functions */
function getLineData() {
    $.ajax({
        url: 'app/get_line_data.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify({ "location": $('#location').val(), "date": $('#datepicker').val() }),
        success: function (data) {
            console.log(data);
            lineData = data;
            drawLineChart();
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            alert(err.message);
        }
    });
}

// draw line chart to line_chart_div
function drawLineChart() {

    result = [];
    for (var i in lineData) {
        // format data into timeofday and number format
        // timeofday must be an array of at least 3 numbers in form of [hours, minutes, seconds]
        var time = i.split(':').concat('0');
        time = time.map(v => parseInt(v, 10));
        result.push([time, parseInt(lineData[i])]);
    }

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('timeofday', 'Time of Day');
    data.addColumn('number', 'no2 reading');
    data.addRows(result);

    // Set chart options
    var options = {
        'title': 'Air Quality Over a 24 Hour Period',
        'hAxis': { title: 'Time' },
        'vAxis': { title: 'no2 level' },
        'height': 600
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.ScatterChart(document.getElementById('line_chart_div'));
    chart.draw(data, options);

}