<?php

include('file_reader.php');

$post_data = json_decode(file_get_contents('php://input'), true);

$location = $post_data['location'];

# format time received from datepicker
$time = strtotime((str_replace('/', '-', $post_data['date'])));

$readings = array();

$xml_reader = new XMLReader();

$read_path_array = get_read_path_array($clean_data_dir);

for ($i = 0; $i < count($read_path_array); $i++) {
    # check that time from datepicker is valid
    if ($time) {

        $xml_reader->open($read_path_array[$i]);

        while ($xml_reader->read()) {
            if ($xml_reader->name == 'location') {
                $location_id = $xml_reader->getAttribute('id');
                if ($location_id == $location) {
                    while ($xml_reader->read()) {
                        if ($xml_reader->name == 'reading') {
                            $reading_date = $xml_reader->getAttribute('date');
                            $reading_time = substr($xml_reader->getAttribute('time'), 0, 5);
                            $reading_val = $xml_reader->getAttribute('val');
                            # if the date of the current xml reading is the same as the one received from post data, 
                            # then add its time and value to the readings list
                            if ($time == strtotime((str_replace('/', '-', $reading_date)))) {
                                $readings[$reading_time] = $reading_val;
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
}

echo json_encode($readings);
 