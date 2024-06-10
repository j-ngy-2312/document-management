
<?php

include("functions.php");
$dblink=db_connect("document");
$total_document = 0;
//Get all of the documents from the database
$sql = "SELECT `name` FROM `empty_doc`";
$result = $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
while($data = $result->fetch_array(MYSQLI_ASSOC)) {
	$tmp = explode("-",$data['name']);
	$total_document += 1;
	$loanArray[] = $tmp[0];
	$typeArray[] = $tmp[1];
}
echo '<div>Total number of the document is: '.$total_document.'.';
//store the unique loan numbers and the types
$loanUnique = array_unique($loanArray);
$typeUnique = array_unique($typeArray);

//loop through each loan number
foreach($loanUnique as $key=>$value){
	$type = array();
	//get all of the loans with the same loan numbers
	$sql = "SELECT `name` from `empty_doc` where `name` LIKE '%$value%'";
	$result = $dblink->query($sql) or die("Seomthing went wrong with $sql<br>.$dblink->error");
	while($data=$result->fetch_array(MYSQLI_ASSOC)){
		//get all of the types for the given loan number
		$tmp = explode("-",$data['name']);
		//record all of the types to $type
		array_push($type, $tmp[1]);
	
	}// end while
	/*
	*Compare the all of the types to the types of the current document
	*Report the missing document types
	*/
//	foreach($typeUnique as $k=>$v){
//		echo '<p>Document  has '.$v.' of the documents</p>';
//	}
//	$array_diff = array_diff($type,$typeUnique);
//	foreach($array_diff as $k=>$v){
//		echo '<p>Document  has '.$v.' of the documents</p>';
//	}
	if(empty(array_diff($typeUnique,$type))){
		echo '<p>Document '.$value.' has all of the documents</p>';
	}
	//page break for easier inspection
	echo '<br>';
}//end for each


?>