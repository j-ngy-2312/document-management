<!DOCTYPE html>
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<link href="assets/css/bootstrap-fileupload.min.css" rel="stylesheet" />
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.12.4.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-fileupload.js"></script>
<?php
include("functions.php");
$dblink=db_connect("document");
session_start();
echo '<div id="page-inner">';
if (isset($_REQUEST['msg']) && ($_REQUEST['msg']=="success"))
{
	echo '<div class="alert alert-success alert-dismissable">';
	echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
	echo 'Document successfully uploaded!</div>';
}
echo '<h1 class="page-head-line">Upload a New File to DocStorage</h1>';
echo '<div class="panel-body">';
echo '<form method="post" enctype="multipart/form-data" action="">';
echo '<input type="hidden" name="uploadedby" value="user@test.mail">';
echo '<input type="hidden" name="MAX_FILE_SIZE" value="100000000">';
echo '<div class="form-group">';
echo '<label class="control-label col-lg-4">File Upload</label>';
echo '<div class="">';
echo '<div class="fileupload fileupload-new" data-provides="fileupload">';
echo '<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>';
echo '<div class="row">';//buttons
echo '<div class="col-md-2"><span class="btn btn-file btn-primary"><span class="fileupload-new">Select File</span><span class="fileupload-exists">Change</span>';
echo '<input name="userfile" type="file"></span></div>';
echo '<div class="col-md-2"><a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a></div>';
echo '</div>';//end buttons
echo '</div>';//end fileupload fileupload-new
echo '</div>';//end ""
echo '</div>';//end form-group
echo '<hr>';
echo '<label>File category: </label>'; //choosing the file category
echo '<select name="category">';//start select option
$sql = 'SELECT `auto_id`, `title` FROM `category`';
$result = $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
while($row = $result->fetch_array(MYSQLI_ASSOC)){
	echo '<option value="'.$row['auto_id'].'">'.$row['title'].'</option>';
}
echo '</select>';//end select option
echo '<button type="submit" name="submit" value="submit" class="btn btn-lg btn-block btn-success">Upload File</button>';
echo '</form>';
echo '</div>';//end panel-body
echo '</div>';//end page-inner
echo '<form action="" method="post">';
echo '<div>';
echo '<button type="submit" name="logout" value="logout" class="btn btn-lg btn-block btn-success">Logout</button>';
echo '</div>';
echo '</form>';
if (isset($_POST['submit']))
{
  
	$uploadDate=date("Y-m-d H:i:s");
	$uploadBy="user@test.mail";
	$fileName=$_FILES['userfile']['name'];
	$docType="pdf";
	$tmpName=$_FILES['userfile']['tmp_name'];
	$fileSize=$_FILES['userfile']['size'];
	$fileType=$_FILES['userfile']['type'];
	$category = $_POST['category'];
    $path="uploads/";
	$fp=fopen($tmpName, 'r');
	$content=fread($fp, filesize($tmpName));
	fclose($fp);
	$con = "";
	$tags = "";
	$sql="Insert into `doc` (`file_name`, `file_size`, `file_type`, `category`,`upload_by`,`upload_date`,`path`,`content`,`status`, `tags`) values ('$fileName', '$fileSize','$docType','$category','$uploadBy','$uploadDate','$path','$con', '1', '$tags')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	$fp=fopen($path.$fileName,"wb") or
		die("Could not open $path$fileName for writing");
	fwrite($fp,$content);
	fclose($fp);
	header("upload.php?msg=success");
}
if(isset($_POST['logout'])){
	
	session_destroy();
	header('refresh:1;location: login.php');
}
?>