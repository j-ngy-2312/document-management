
<?php

include("functions.php");
$dblink=db_connect("document");
$total_size = 0;
$sql = "SELECT `name`,`size` FROM `empty_doc`";
$result = $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
while($data = $result->fetch_array(MYSQLI_ASSOC)) {
//	echo '<div>'.$data['name'].'</div>';
	$tmp = explode("-",$data['name']);
	$total_size += $data['size'];
	$loanArray[] = $tmp[0];
}
echo '<div>Total size of the document is: '.$total_size.' KB.';
$loanUnique = array_unique($loanArray);
foreach($loanUnique as $key=>$value){
	$sql = "SELECT count(`name`), sum(`size`) from `empty_doc` where `name` LIKE '%$value%'";
	$result = $dblink->query($sql) or die("Seomthing went wrong with $sql<br>.$dblink->error");
	$tmp = $result->fetch_array(MYSQLI_NUM);
	echo '<div>Loan number: '.$value.' has '.$tmp[0].' number of documents and the average size of documents is: '.ceil($tmp[1]/$tmp[0]).' KB</div>';
	$count += 1;
	$num_documents += $tmp[0];
}

?>