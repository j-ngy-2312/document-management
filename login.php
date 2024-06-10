<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
<?php 
include("functions.php");
$dblink = db_connect('document');
session_start();

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM `employee` where `username`='$username' AND `password` = md5('$password')";
    $result = $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
    if($result) {
        $sql = "INSERT INTO `log` (`username`,`action`,`description`,`status`) VALUES('$username',1,'$username login',1)";
        $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
        echo '<p>Login successfully</p>';
        $data = $result->fetch_array(MYSQLI_ASSOC);
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $data['auto_id'];
        $_SESSION['role'] = $data['role'];
        echo '<script type="text/javascript">
                jQuery(function validation() {
                    swal({
                    title: "Good job!",
                    text: "You clicked",
                    icon: "success",
                    button: "Ok",  
                    });
                });
            </script>';
        header('refresh:1;upload-new.php');
    } else {
		 $sql = "INSERT INTO `log` (`username`,`action`,`description`,`status`) VALUES('$username',1,'$username login',2)";
        $dblink->query($sql) or die("Something went wrong with $sql<br>".$dblink->error);
        echo '<p>Login fail</p>';
		echo '<script type="text/javascript">
                jQuery(function validation() {
                    swal({
                    title: "Error!",
                    text: "Invalid or incorrect credentials. Please try again",
                    icon: "error",
                    button: "Ok",  
                    });
                });
            </script>';
	}
}


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>

	<form action="" method="post">
		<div class="col-md-4 border rounded mx-auto mt-5 p-4 shadow">
			
			<div class="h2">Login</div>

			<div><small class="my-1 js-error js-error-email text-danger"></small></div>

			<div class="input-group mb-3">
			  <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope"></i></span>
			  <input name="username" type="text" class="form-control p-3" placeholder="Username" >
			</div>
			<div class="input-group mb-3">
			  <span class="input-group-text" id="basic-addon1"><i class="bi bi-key"></i></span>
			  <input name="password" type="password" class="form-control p-3" placeholder="Password" >
			</div>

			<button class="btn btn-primary col-12" type="submit" name="login" value="login"> Login</button>

		</div>
	</form>

</body>
</html>