<?php
$post_data = json_decode(file_get_contents('php://input'), true);

$location = $post_data['location'];

$time = strtotime((str_replace('/', '-', $post_data['date'])));

$readings = array();

$xml_reader = new XMLReader();

$read_dir = 'data_2';

$read_path_array = get_read_path_array($read_dir);

for ($i = 0; $i < count($read_path_array); $i++) {

    if ($time) {

        $xml_reader->open($read_path_array[$i]);

        $date = date('d/m/Y', $time);

        while ($xml_reader->read()) {
            if ($xml_reader->name == 'location') {
                $location_id = $xml_reader->getAttribute('id');
                if ($location_id == $location) {
                    while ($xml_reader->read()) {
                        if ($xml_reader->name == 'reading') {
                            $reading_date = $xml_reader->getAttribute('date');
                            $reading_time = substr($xml_reader->getAttribute('time'), 0, 5);
                            $reading_val = $xml_reader->getAttribute('val');
                            if ($time == strtotime((str_replace('/', '-', $reading_date)))) {
                                $readings[$reading_time] = $reading_val;
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
}

echo json_encode($readings);

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
 