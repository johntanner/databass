<!-- edit-member-profile.php -->
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
			$username = $_POST["member_uname"];
        	$password = $_POST["member_pwd"];
        	$member_id = $_POST["member_id"];
			$address = $_POST["member_address"];
			$first_name = $_POST["member_fname"];
			$last_name = $_POST["member_lname"];
			$phone_number = $_POST["member_pno"];
		?>

		<form method="POST" action="login.php">
	        <input type="hidden" name="username" value= <?php echo $username; ?> >
	        <input type="hidden" name="password" value= <?php echo $password; ?> >
			<button class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back to Home </button>
			<hr>
		</form>

        <div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">
        <!-- Make the form with the user details filled in -->
        	
        	<form method="POST" action="update-user-profile.php" class="form-signin">
        		<h2 class="form-signin-heading">Edit Profile Details</h2>
        		<hr>
        		<input type="hidden" name="member-id" class="form-control" value= <?php echo $member_id ?> >

        		<h4>Username</h4>
        		<input type="text" name="username" class="form-control" placeholder="Username" value= <?php echo $username ?> required autofocus>
        		
        		<h4>Password</h4>
        		<input type="text" name="password" class="form-control" placeholder="Password" value= <?php echo $password ?> required autofocus>

        		<h4>First Name</h4>
        		<input type="text" name="first_name" class="form-control" placeholder="First Name" value= <?php echo $first_name ?> required autofocus>

        		<h4>Last Name</h4>
        		<input type="text" name="last_name" class="form-control" placeholder="Last Name" value= <?php echo $last_name ?> required autofocus>

        		<h4>Address</h4>
        		<input type="text" name="address" class="form-control" placeholder="Address" maxlength="90" value= "<?php echo $address;?>" required autofocus>

        		<h4>Phone Number</h4>
        		<input type="text" name="phone_number" class="form-control" placeholder="Phone Number" value= <?php echo $phone_number ?> required autofocus>

        		<hr>
        		<button class="btn btn-lg btn-primary btn-block" style="opacity:0.9;"type="submit">Update Profile Details</button>
      		</form>    

        </div>

        <?php 
        	

        	//
        ?>

		<!-- Add Code Below -->
	</div> <!-- End of container div-->

</body>
</html>