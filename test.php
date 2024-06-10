if($cinfo[1] == "Action: None"){ //no new files
	echo "\r\n No new files to import\r\n";
	echo "SID: $sid\r\n";
	echo "Username: $username\r\n";
	echo "Query Files Execution Time: $execution_time\r\n";
	} else { // there are files to import
	$tmp = explode(":",$cinfo[1]);
	$files = explode(",", $tmp[1]);
	echo "Number of new files to import found: " .count(files)."\r\n";
	echo "Files:\r\n";
	foreach($files as $key=>$value) {
		$tmp = explode("/", $value);
		$file = $tmp[4];
		echo "File: $file\r\n";
		$data = "sid=$sid&uid=$username&fid=$file";
		//    
		$ch = curl_init('https://cs4743.professorvaladez.com/api/request_file');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'content-type: application/x-www-form-urlencoded',
		'content-length: ' . strlen($data)
		));
		//
		$time_start = microtime(true);
		$result = curl_exec($ch);
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start)/60;
		$content = $result;
		$fp = fopen("/var/www/html/receive/$file","wb");
		fwrite($fp, $content);
		fclose($fp);
		echo "\r\n$file written to file system\r\n";
	}
	echo "Query Files Execution Time: $execution_time\r\n";
	}