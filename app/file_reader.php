<?php
# name of directory to read from and write to (relative)
$unclean_data_dir = 'data_1';

$clean_data_dir = 'data_2';

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