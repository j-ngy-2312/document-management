<?php 
function db_connect($db){
	$hostname="localhost";
    $username="webuser";
    $password="sT1Y)eQ/KogpGf84";
    $dblink=new mysqli($hostname,$username,$password,$db);
	if (mysqli_connect_errno())
    {
        die("Error connecting to database: ".mysqli_connect_error());   
    }
	return $dblink;
}
$dblink = db_connect("document");
$username = "fyc585";
$password = '9LfXFQjynvJ2H@$R';
$data = "username=$username&password=$password";
$ch = curl_init('https://cs4743.professorvaladez.com/api/create_session');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'content-type: application/x-www-form-urlencoded',
    'content-length: ' . strlen($data)
));

$time_start = microtime(true);
$result = curl_exec($ch);
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
curl_close($ch);
$cinfo = json_decode($result, true);

if ($cinfo[0] == "Status: OK" && $cinfo[1] == "MSG: Session Created") {
	$sid = $cinfo[2];
	$data = "sid=$sid&uid=$username";
	echo "\r\nSession Created Successfully!\r\n";
	echo "SID: $sid\r\n";
	echo "Created Session Execution Time: $execution_time\r\n";
	$ch = curl_init('https://cs4743.professorvaladez.com/api/query_files');
    curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'content-type: application/x-www-form-urlencoded',
    'content-length: ' . strlen($data)
	));
	$time_start = microtime(true);
	$result = curl_exec($ch);
	$time_end = microtime(true);
	$execution_time = ($time_end - $time_start)/60;
	curl_close($ch);
	$cinfo = json_decode($result, true);
	if($cinfo[0] == "Status: OK") {
		if($cinfo[1] == "Action: None") {
			echo "\r\nNo New files to import\r\n";
			echo "SID: $sid\r\n";
			echo "Username: $username\r\n";
			echo "Query Files Execution Time: $execution_time\r\n";
		} else {
			$tmp = explode(":",$cinfo[1]);
			$files = explode(",",$tmp[1]);
			$num_files = count($files);
				echo "Number of new files to import found: ".$num_files."\r\n";
				echo "Files:\r\n";
				foreach($files as $key=>$value){
					$tmp = explode("/", $value);
					$file = $tmp[4];
					if(!isset($file)){
						echo "0 file to import<br>";
						$sql = "INSERT INTO `import` (num_files, status) VALUES('0','ok') ";
						$dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
						echo "Query Files Execution Time: $execution_time\r\n";
						break;
					}
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
					$fp = fopen("/var/www/html/receive/$file","wb");
					fwrite($fp, $content);
					fclose($fp);
					echo "\r\n$file written to file system\r\n";
					$path = "/var/www/html/receive/";
					$sql = "INSERT INTO `empty_doc`(`file_name`,`path`,`size`) VALUES('$file','$path','')";
					$dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
				}
				$sql = "INSERT INTO `import` (num_files, status) VALUES('$num_files','ok') ";
				$dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
				echo "Query Files Execution Time: $execution_time\r\n";
			
		}// end action none
	} else {
		$sql = "INSERT INTO `import` (num_files, status) VALUES('0','error') ";
		$dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
		echo $cinfo[0];
        echo "\r\n";
        echo $cinfo[1];
        echo "\r\n";
        echo $cinfo[2];
        echo "\r\n";
		$sql = "INSERT INTO `log` (`info1`, `info2`,`info3`) VALUES('$cinfo[0]','$cinfo[1]','$cinfo[2]') ";
		$dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
	}
	$data = "sid:$sid";
    $ch = curl_init('https://cs4743.professorvaladez.com/api/close_session');
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
    curl_close($ch);
    if ($cinfo[0] == "Status: OK") { // successfully close session
        echo "Session successfully closed!\r\n";
        echo "SID: $sid \r\n";
        echo "Close session execution time: $execution_time \r\n";
    } else { // error closing the session
        echo $cinfo[0];
        echo "\r\n";
        echo $cinfo[1];
        echo "\r\n";
        echo $cinfo[2];
        echo "\r\n";
		$sql = "INSERT INTO `log` (`info1`, `info2`,`info3`) VALUES('$cinfo[0]','$cinfo[1]','$cinfo[2]') ";
		$dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
    }
    
}

?>