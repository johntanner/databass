<!-- update-user-profile.php -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="custom/images/databass.png">

    <title>Databass Library</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="custom/css/signin.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.js"></script>

    <!-- PHP Files for reporting errors and sql functions -->
    <?php 
    	//include "report_errors.php";
	  	include "php/sql-functions.php"; 
  	?>
  </head>

<body>
	<?php 
		$username = $_POST["username"];
    	$password = $_POST["password"];
    	$member_id = $_POST["member-id"];
		$address = $_POST["address"];
		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$phone_number = $_POST["phone_number"];

		// Update the Dataabase with the new values above

		$tuple = array (
			":member_id" => $member_id,
			":username" => $username,
			":password" => $password,
			":address" => $address,
			":first_name" => $first_name,
			":last_name" => $last_name,
			":phone_number" => $phone_number
		);

		$alltuples = array (
			$tuple
		);

		$result_bool = executeBoundSQLResult("update members set username=:username,password=:password,address=:address,first_name=:first_name,last_name=:last_name,phone_number=:phone_number where member_id=:member_id", $alltuples);
		$oci_commit = OCICommit($db_conn);

		//Commit changes
		logoff_oci();

	?>

	<div class="container">
		<div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">
		<!-- On success commit -->
		<?php if ($result_bool) { ?> 
			<h2> Your Profile Was Updated Succesfully </h2>
			<img src="custom/images/success.png" height="200px" width="200px">
		<?php } else { ?>
			<h2> Error when updating profile <img src="custom/images/smiley-sad.png" height="20px" width="20px"> </h2>

			<form method="POST" action="edit-member-profile.php">
		        <input type="hidden" name="member_uname" value= <?php echo $username; ?> >
		        <input type="hidden" name="member_pwd" value= <?php echo $password; ?> >
		        <input type="hidden" name="member_id" value= <?php echo $member_id; ?> >
		        <input type="hidden" name="member_address" value= "<?php echo $address; ?>" >
		        <input type="hidden" name="member_fname" value= <?php echo $first_name?> >
		        <input type="hidden" name="member_lname" value= <?php echo $last_name; ?> >
		        <input type="hidden" name="member_pno" value= <?php echo $phone_number; ?> >
		        <button class="btn btn-danger btn-large">Try Again</button>
			</form>
		<?php } ?>

			<form method="POST" action="login.php">
		        <input type="hidden" name="username" value= <?php echo $username; ?> >
		        <input type="hidden" name="password" value= <?php echo $password; ?> >
				<button class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back to Home </button>
			</form>


		</div>


        

		<!-- Add Code Below -->
	</div> <!-- End of container div-->

</body>
</html>