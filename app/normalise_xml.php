<?php
include('file_reader.php');

echo "normalising data .. wait\n\n";

$xml_reader = new XMLReader();
$xml_writer = new XMLWriter();

$write_dir = 'data_2';

if (file_exists($unclean_data_dir)) {

    $read_file_path_array = get_read_path_array($unclean_data_dir);

    # create array of all file paths to write to
    $write_file_path_array = get_write_path_array($read_file_path_array, $clean_data_dir);

    for ($i = 0; $i < count($read_file_path_array); $i++) {

        $xml_reader->open($read_file_path_array[$i]);
        $xml_writer->openURI($write_file_path_array[$i]);
        $xml_writer->setIndent(true);
        $xml_writer->startDocument('1.0', 'UTF-8');
        $xml_writer->startElement('data');
        $xml_writer->writeAttribute('type', 'nitrogen dioxide');
        $xml_writer->startElement('location');

        $is_lat_set = false;
        $is_long_set = false;

        # write lat and long attributes to location element once
        while ($xml_reader->read() && (!$is_lat_set || !$is_long_set)) {

            # write location id as name of location
            if ($xml_reader->name == 'desc') {
                $location_id = $xml_reader->getAttribute('val');
                $xml_writer->writeAttribute('id', $location_id);
            }

            # write lat
            if ($xml_reader->name == 'lat') {
                $lat_val = $xml_reader->getAttribute('val');
                $xml_writer->writeAttribute('lat', $lat_val);
                $is_lat_set = true;
            }

            # write long
            if ($xml_reader->name == 'long') {
                $long_val = $xml_reader->getAttribute('val');
                $xml_writer->writeAttribute('long', $long_val);
                $is_long_set = true;
            }
        }

        # start at the beginning of xml file again
        $xml_reader->open($read_file_path_array[$i]);

        while ($xml_reader->read()) {
            
            # create new row element
            if ($xml_reader->name == 'row') {

                $reading_date = '';
                $reading_time = '';
                $reading_val = 0;

                while ($xml_reader->read()) {
                    switch ($xml_reader->name) {
                        case 'date':
                            $reading_date = $xml_reader->getAttribute('val');
                            break;
                        case 'time':
                            $reading_time = $xml_reader->getAttribute('val');
                            break;
                        case 'no2':
                            $reading_val = $xml_reader->getAttribute('val');
                            break 2;
                        default:
                            break;
                    }

                }

                # if it is not the end of xml file, then write new reading element and attributes
                if (!empty($reading_date)) {
                    $xml_writer->startElement('reading');
                    $xml_writer->writeAttribute('date', $reading_date);
                    $xml_writer->writeAttribute('time', $reading_time);
                    $xml_writer->writeAttribute('val', $reading_val);
                    $xml_writer->endElement();
                }
            }
        }

        $xml_writer->endElement();
        $xml_writer->endDocument();
    }
    echo "\n\ndata normalised!";
} else {
    echo 'Read directory does not exist';
}

function get_write_path_array($read_file_path_array, $write_dir)
{
    if(!file_exists($write_dir)) {
        mkdir($write_dir);
    }

    # create new file path from read files using regex
    $pattern = array('/(data_1)/', '/(.xml)/');
    $replace = array($write_dir, '_2.xml');
    $write_file_path_array = array();

    foreach ($read_file_path_array as $file_path) {
        $new_path = preg_replace($pattern, $replace, $file_path);
        array_push($write_file_path_array, $new_path);
    }

    return $write_file_path_array;
}
?>