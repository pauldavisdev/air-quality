<?php
echo "working .. wait";
ob_flush();
flush();

# name of xml destination directory
$put_dir = 'data_1/';

if (($handle = fopen("air_quality.csv", "r")) !== false) {
    
	# define the tags - last col value in csv file is derived so ignore
	$header = array('id', 'desc', 'date', 'time', 'nox', 'no', 'no2', 'lat', 'long');
	
	# throw away the first line - field names
	fgetcsv($handle, 200, ",");
	
	# count the number of items in the $header array so we can loop using it
	$cols = count($header);
	
	#set record count to 1
	$count = 1;
	# set row count to 2 - this is the row in the original csv file
	$row = 2;
		
	# start ##################################################
	$out['brislington'] = '<records>'; # 3
	$out['fishponds'] = '<records>'; # 6
	$out['parson_st'] = '<records>'; # 8
	$out['rupert_st'] = '<records>'; # 9
	$out['wells_rd'] = '<records>'; # 10
	$out['newfoundland_way'] = '<records>'; # 11

	while (($data = fgetcsv($handle, 200, ",")) !== false) {
		$count++;
		switch ($data[0]) {
			case 3:
				$out['brislington'] .= get_record($header, $data, $count, $row, $cols);
		
				break;
			case 6:
				$out['fishponds'] .= get_record($header, $data, $count, $row, $cols);

				break;
			case 8:
				$out['parson_st'] .= get_record($header, $data, $count, $row, $cols);
	
				break;
			case 9:
				$out['rupert_st'] .= get_record($header, $data, $count, $row, $cols);
			
				break;
			case 10:
				$out['wells_rd'] .= get_record($header, $data, $count, $row, $cols);
			
				break;
			case 11:
				$out['newfoundland_way'] .= get_record($header, $data, $count, $row, $cols);
				
				break;
			default:
				echo "default break";
			
		}
		$row++;
	}

	foreach ($out as $x => $x_value) {
	# close records tag
		$out[$x] .= '</records>';

	# write out file
	if(!file_exists($put_dir)) {
		mkdir($put_dir);
	}

	file_put_contents($put_dir . $x . '.xml', $out[$x]);
	}

	# finish ##################################################

	fclose($handle);
}
echo "....all done!";

function get_record($header, $data, $count, $row, $cols) {
	$rec = '<row count="' . $count . '" id="' . $row . '">';

	for ($c = 0; $c < $cols; $c++) {
		$rec .= '<' . trim($header[$c]) . ' val="' . trim($data[$c]) . '"/>';
	}
	$rec .= '</row>';
	
	return $rec;
}
?>