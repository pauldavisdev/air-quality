<?php

include('file_reader.php');

$post_data = json_decode(file_get_contents('php://input'), true);

$location = $post_data['location'];

$year = $post_data['year'];

$time = $post_data['time'];

$readings = array();

$xml_reader = new XMLReader();

$read_path_array = get_read_path_array($clean_data_dir);

for ($i = 0; $i < count($read_path_array); $i++) {

    $xml_reader->open($read_path_array[$i]);

    while ($xml_reader->read()) {
        if ($xml_reader->name == 'location') {
            $location_id = $xml_reader->getAttribute('id');
            if ($location_id == $location) {
                    while ($xml_reader->read()) {
                        if ($xml_reader->name == 'reading') {
                            # get reading time from xml in format HH:mm
                            $reading_time = substr($xml_reader->getAttribute('time'), 0, 5);
                            # get reading date from xml
                            $reading_date = $xml_reader->getAttribute('date');
                            # get year from reading date
                            $reading_year = substr($reading_date, -4);
                            # get reading no2 value from xml
                            $reading_val = $xml_reader->getAttribute('val');

                            # if the reading year and time is equal to the selected year and time, then add to list
                            if ($reading_year == $year && $reading_time == $time) {
                                $readings[$reading_date] = $reading_val;//$xml_reader->getAttribute('date');
                            }
                        }
                    }
                # no more readings in current xml file, break out of all loops and echo the generated readings list
                break 2;
            } else {
                # this xml file is not for the selected location, break to for loop to read in next xml file in data_2 dir
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
?>