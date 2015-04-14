<?php
	
	$list = file_get_contents('http://localhost/tugas-akhir/repo.php');
	$obj = json_decode($list);
	$size = count($obj);
	$myfile = fopen("d:/sundaNews.txt", "w") or die("Unable to open file!");
	$output = "";
	$myfile2 = fopen("d:/indoNews.txt", "w") or die("Unable to open file!");
	$output2 = "";

	for($i=0;$i<$size;$i++){
		ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
		$name = $obj[$i]->{'sunda'};	
		$output .= "<  ".$name."   >\n\n";
		$ret = wiki($name);
		$output .= $ret[0]."\n\n";
		$output2 .= $ret[1];
		
	}
	fwrite($myfile, $output);
	fclose($myfile);
	fwrite($myfile2, $output2);
	fclose($myfile2);

	function wiki($title){
		$title = preg_replace('/\s/s', '_', $title);
		$sunda = file_get_contents('http://su.wikipedia.org/w/api.php?format=json&action=parse&page='.$title);
		$obj = json_decode($sunda);
		$getTeks = $obj->{'parse'}->{'text'}->{'*'};
		$clean = preg_replace('/\[[0-9]*\]/', '', $getTeks);
		preg_match_all('/<p>(.*?)<\/p>/s', $clean, $matches);
		$size = sizeof($matches[1]);	
		$output = "";
		for($i=0;$i<$size;$i++){			
			$output .= strip_tags($matches[1][$i])." ";
		}
	
		$getTeks = $obj->{'parse'}->{'langlinks'};	
		$size = count($getTeks);
		$output2 = "";
		$output2 .= "<  ".$title."   >\n\n";
		for($i=0;$i<$size;$i++){
			$lang = $getTeks[$i]->{'lang'};
			if($lang == "id"){
				$name = $getTeks[$i]->{'*'};
				$output2 .= indo($name)."\n\n";
			}
		}
		$ret = array($output, $output2);

		return $ret;
	 }

	 function indo($title){
	 	$title = preg_replace('/\s/s', '_', $title);
	 	$indo = file_get_contents('http://id.wikipedia.org/w/api.php?format=json&action=parse&page='.$title);
		$obj = json_decode($indo);
		$getTeks = $obj->{'parse'}->{'text'}->{'*'};
		$clean = preg_replace('/\[[0-9]\]/', '', $getTeks);
		preg_match_all('/<p>(.*?)<\/p>/s', $clean, $matches);
		$size = sizeof($matches[1]);
		$output = "";
		for($i=0;$i<$size;$i++){
			$output .= strip_tags($matches[1][$i])." ";
		}
		return $output;
	 }

	
?>
