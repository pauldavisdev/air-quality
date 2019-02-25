<?php

$post_data = json_decode(file_get_contents('php://input'), true);

$location = $post_data['location'];

$year = $post_data['year'];

$time = $post_data['time'];

$readings = array();

//echo json_encode('location is ' . $location . ', time is ' . $time . ', year is ' . $year);

$xml_reader = new XMLReader();

$read_dir = 'data_2';

$read_path_array = get_read_path_array($read_dir);

for ($i = 0; $i < count($read_path_array); $i++) {

    $xml_reader->open($read_path_array[$i]);

    while ($xml_reader->read()) {
        if ($xml_reader->name == 'location') {
            $location_id = $xml_reader->getAttribute('id');
            if ($location_id == $location) {
                    while ($xml_reader->read()) {
                        if ($xml_reader->name == 'reading') {
                            // get reading time from xml in format HH:mm
                            $reading_time = substr($xml_reader->getAttribute('time'), 0, 5);
                            // get reading date from xml
                            $reading_date = $xml_reader->getAttribute('date');
                            // get year from reading date
                            $reading_year = substr($reading_date, -4);
                            // get reading no2 value from xml
                            $reading_val = $xml_reader->getAttribute('val');

                            // if the reading year and time is equal to the selected year and time, then add to list
                            if ($reading_year == $year && $reading_time == $time) {
                                $readings[$reading_date] = $reading_val;//$xml_reader->getAttribute('date');
                            }
                        }
                    }
                break 2;
            } else {
                break;
            }
        }
    }
}

# if the readings list is not empty, echo result. else if it is empty, send error message
if (count($readings) > 0) {
    echo json_encode($readings);
} else {
    echo json_encode('no data found');
}

function get_read_path_array($read_dir)
{
    # get list of files to normalise from data_1 folder
    $read_path = getcwd() . '/' . $read_dir . '/';
    $read_files = scandir($read_path);
    $read_files = array_values(array_diff(scandir($read_path), array('.', '..')));

    $read_file_path_array = array();

    # create array of all file paths to read from
    foreach ($read_files as $file) {
        array_push($read_file_path_array, $read_dir . '/' . $file);
    }
    return $read_file_path_array;
}
?>