
<?php

include("functions.php");
$dblink=db_connect("document");

$sql = "SELECT * FROM `empty_doc`";
$result = $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
while($data = $result->fetch_array(MYSQLI_ASSOC)) {
//	echo '<div>'.$data['name'].'</div>';
	$tmp = explode("-",$data['name']);
	$loanArray[] = $tmp[0];
}
$loanUnique = array_unique($loanArray);
foreach($loanUnique as $key=>$value){
	$sql = "SELECT count(`name`) from `empty_doc` where `name` LIKE '%$value%'";
	$result = $dblink->query($sql) or die("Seomthing went wrong with $sql<br>.$dblink->error");
	$tmp = $result->fetch_array(MYSQLI_NUM);
	echo '<div>Loan number: '.$value.' has '.$tmp[0].' number of documents</div>';
	
}


?>