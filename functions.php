
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
?>