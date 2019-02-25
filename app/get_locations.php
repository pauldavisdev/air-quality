<?php

$xml_reader = new XMLReader();

$read_dir = 'data_2';

$read_path_array = get_read_path_array($read_dir);

$location = array();

for ($i = 0; $i < count($read_path_array); $i++) {

    $xml_reader->open($read_path_array[$i]);

    while ($xml_reader->read()) {
        if ($xml_reader->name == 'location') {
            $location_id = $xml_reader->getAttribute('id');
            $location[] = $location_id;
            $xml_reader->close();
            break;
        }
    }
}

echo json_encode($location);

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