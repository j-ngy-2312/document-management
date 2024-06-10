
<?php

include("functions.php");
$dblink=db_connect("document");
//Get all of the documents from the database
$sql = "SELECT `name` FROM `empty_doc`";
$result = $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
while($data = $result->fetch_array(MYSQLI_ASSOC)) {
	$tmp = explode("-",$data['name']);
	$typeArray[] = $tmp[1];
}
$typeUnique = array_unique($typeArray);

//loop through each loan number
foreach($typeUnique as $key=>$value){
	$type = array();
	//get all of the loans with the same loan numbers
	$sql = "SELECT count(`name`) from `empty_doc` where `name` LIKE '%$value%'";
	$result = $dblink->query($sql) or die("Seomthing went wrong with $sql<br>.$dblink->error");
	$tmp = $result->fetch_array(MYSQLI_NUM);
	echo '<div>Document type: '.$value.' has '.$tmp[0].' number of documents</div>';
	
//	foreach($typeUnique as $k=>$v){
//		echo '<p>Document  has '.$v.' of the documents</p>';
//	}
//	$array_diff = array_diff($type,$typeUnique);
//	foreach($array_diff as $k=>$v){
//		echo '<p>Document  has '.$v.' of the documents</p>';
//	}
	//page break for easier inspection
	echo '<br>';
}//end for each


?>