
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
}
echo '<div>Total number of the document is: '.$total_document.'.</div>';
//store the unique loan numbers and the types
$loanUnique = array_unique($loanArray);
$sum = 0;
//loop through each loan number
foreach($loanUnique as $key=>$value){
	//get all of the loans with the same loan numbers
	$sql = "SELECT count(`name`) from `empty_doc` where `name` LIKE '%$value%'";
	$result = $dblink->query($sql) or die("Seomthing went wrong with $sql<br>.$dblink->error");
	$tmp = $result->fetch_array(MYSQLI_NUM);
	$sum += $tmp[0];
	$loanComplete[$value] = $tmp[0];
	//page break for easier inspection
}//end for each
$average = $sum/count($loanUnique);
echo '<div>The average number of documents across all loans is: '.$average.'</div>';
foreach($loanComplete as $key=>$value){
	echo '<div>Loan Number '.$key.' has '.$value.' documents';
	if($value < $average){
		echo ': lower than average</div>';	
	} else if ($value > $average) {
		echo ': larger than average</div>';
	} else {
		echo ': equal to average</div>';
	}
}

?>