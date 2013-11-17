<!-- login.php -->

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
    	include "report_errors.php";
	  	include "php/sql-functions.php"; 
  	?>
  </head>

<body>
	<div class="container">

	<?php
	
	if($db_conn){
		$username = $_POST["username"];
		$password = $_POST["password"];

		$tuple = array (
						":bind1" => $username,
						":bind2" => $password
					);
					$alltuples = array (
						$tuple
					);
		$result = executeBoundSQL("select * from members where username=:bind1 and password=:bind2", $alltuples);
		oci_fetch_all($result, $row);

		//Commit changes
		logoff_oci();
		
		$is_member = false;

		//Check if the login credentials belong to a member
		if(count($row["LAST_NAME"]) == 1 && count($row["FIRST_NAME"]) == 1) {
			$is_member = true;
		}
	}
	else {
		echo "cannot connect";
		$e = OCI_Error(); // For OCILogon errors pass no handle
		echo htmlentities($e['message']);
	}

	?>

<!-- Execute below if the given person is a member -->
<?php if ($is_member) { ?>
	
	<h2>
		Welcome, <?php echo $row["FIRST_NAME"][0]." ".$row["LAST_NAME"][0] ?> 
		<button type="button" class="btn btn-danger" onclick="window.location.href='index.php'">
			<span class="glyphicon glyphicon-chevron-left"></span> Logout 
		</button>

		<!-- Editing user profiles -->
		<form method="POST" action="edit-member-profile.php">
	        <input type="hidden" name="member_uname" value= <?php echo $username; ?> >
	        <input type="hidden" name="member_pwd" value= <?php echo $password; ?> >
	        <input type="hidden" name="member_id" value= <?php echo $row["MEMBER_ID"][0]; ?> >
	        <input type="hidden" name="member_address" value= "<?php echo $row["ADDRESS"][0]; ?>" >
	        <input type="hidden" name="member_fname" value= <?php echo $row["FIRST_NAME"][0]; ?> >
	        <input type="hidden" name="member_lname" value= <?php echo $row["LAST_NAME"][0]; ?> >
	        <input type="hidden" name="member_pno" value= <?php echo $row["PHONE_NUMBER"][0]; ?> >

	        <button class="btn btn-success">Edit Your Profile</button>
		</form>
	</h2>

	<hr>
	<h3>Balance Owing : $<?php echo $row["OWING"][0] ?></h3>	
	<hr>
		<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
			<h2><small>Please search for a book using ISBN number</small></h2>
			<form method="POST" action="book-search.php" class="form-inline">
		        <div class="form-group"><input type="text" name="book-search-text" class="form-control" placeholder="9780672327231" required autofocus></div>
	<!-- 			<select name="book-search-option">
					  <option>ISBN</option>
					  <option>Title</option>
					</select>
	-->			<button class="btn btn-primary btn-large" id="search-books-btn">Search</button>
			</form>
		</div>
			<?php } else { ?>
			<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
				<h3> Invalid Login Credentials. Please press back and try again. </h3>
				<button type="button" class="btn btn-danger" onclick="window.location.href='index.php'">
					<span class="glyphicon glyphicon-chevron-left"></span> Back to Login Page 
				</button>
			</div>
			<?php } ?>

</div> <!-- End of container div-->

</body>
</html>