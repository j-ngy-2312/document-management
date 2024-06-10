<!--
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<link href="assets/css/bootstrap-fileupload.min.css" rel="stylesheet" />
<script src="assets/js/jquery-1.12.4.js"></script>

<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-fileupload.js"></script>
-->
<!--
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
-->
<link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"
      integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <!-- ✅ load jQuery ✅ -->
    <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous"
    ></script>

    <!-- ✅ load DataTables ✅ -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
      integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>

<?php
include("functions.php");
$dblink=db_connect("document");
echo '<div id="page-inner">';
echo '<h1 class="page-head-line">Search Files on DB</h1>';
echo '<div class="panel-body">';
if (!isset($_POST['submit']))
{
	echo '<form action="" method="post">';
	echo '<div class="form-group">';
	echo '<label>Search String:</label>';
	echo '<input type="text" class="form-control" name="searchString">';
	echo '</div>';
	echo '<select name="searchType">';
	echo '<option value="name">Name</option>';
	echo '<option value="uploadBy">Uploaded By</option>';
	echo '<option value="uploadDate">Date</option>';
	echo '<option value="all">All</option>';
	echo '</select>';
	echo '<hr>';
	echo '<button type="submit" name="submit" value="submit">Search</button>';
	echo '</form>';
}
if (isset($_POST['submit']))
{
	$searchType=$_POST['searchType'];
	$searchString=addslashes($_POST['searchString']);
	$sql = "SELECT `auto_id`, `title` FROM `category`";
	$result = $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
	$category = array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$category[$row['auto_id']] = $row['title'];
	}
	switch($searchType)
	{
		case "name":
			$sql="Select `file_name`,`upload_date`,`upload_by`,`auto_id`,`category`,`status` from `doc` where `file_name` like '%$searchString%'";
			break;
		case "uploadBy":
			$sql="Select `file_name`,`upload_date`,`upload_by`,`auto_id`,`category`,`status` from `doc` where `upload_by` like '%$searchString%'";
			break;
		case "uploadDate":
			$sql="Select `file_name`,`upload_date`,`upload_by`,`auto_id`,`category`,`status` from `doc` where `upload_date` like '%$searchString%'";
			break;
		case "all":
			$sql="Select `file_name`,`upload_date`,`upload_by`,`auto_id`,`category`,`status` from `doc`";
			break;
		default:
			redirect("search.php?msg=searchTypeError");
			break;
	}
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	echo '<table id="myTable">';
	echo '<thead>';
	echo '<th>Name</th>';
	echo '<th>Upload Date</th>';
	echo '<th>Category</th>';
	echo '<th>Status</th>';
	echo '<th></th>';
	echo '<th></th>';
	echo '<th></th>';
	echo '</thead>';
	echo '<tbody>';
	while ($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<tr>';
		echo '<td>'.$data['file_name'].'</td>';
		echo '<td>'.$data['upload_date'].'</td>';
		echo '<td>'.$category[$data['category']].'</td>';
		echo '<td>'.$data['status'].'</td>';
		echo '<td><a href="view.php?fid='.$data['auto_id'].'">View</a></td>';
		echo '<td><a href="edit.php?fid='.$data['auto_id'].'">Edit</a></td>';
		echo '<td><a href="delete.php?fid='.$data['auto_id'].'">Delete</a></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}
echo '</div>';//end panel-body
echo '</div>';//end page-inner
?>
<script>
	$(document).ready(function () {
    $('#myTable').DataTable();
});</script>