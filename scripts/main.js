    // Load the Visualization API and the corechart package.
    google.charts.load('current', { 'packages': ['corechart'] });

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart(readingData) {

      var result = [];
      // format date string as date and no2 value as number
      for (var i in readingData) {
        var from = i.split("/")
        var f = new Date(from[2], from[1] - 1, from[0])
        result.push([f, parseInt(readingData[i])]);
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
      var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

    //on document load, populate dropdown boxes
    window.onload = function () {
      getScatterData();
    }

    $(document).ready(function () {
      getLocation();
    });

    $('select').change(function () {
      console.log($('#location').val());
      getScatterData();
    });

    function getLocation() {
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

      });
    }

    function getScatterData() {
      $.ajax({
        url: 'app/get_scatter_data.php',
        dataType: 'json',
        type: 'POST',
        data: JSON.stringify({ "location": $('#location').val(), "year": $('#year').val(), "time": $('#time').val() }),
        success: function (data) {
          drawChart(data);
          console.log(data);
        },
        error: function (xhr, status, error) {
          var err = JSON.parse(xhr.responseText);
          alert(err.message);
        }
      });
    }
