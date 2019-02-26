<?php

include('file_reader.php');

$xml_reader = new XMLReader();

$read_path_array = get_read_path_array($clean_data_dir);

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

?>