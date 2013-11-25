<!-- edit-user-balance.php -->
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
        ?>

		<form method="POST" action="login.php">
	        <input type="hidden" name="username" value= <?php echo $username; ?> >
	        <input type="hidden" name="password" value= <?php echo $password; ?> >
			<button class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back to Home </button>
			<hr>
		</form>


		<?php 
			$user_id = $_POST["member-id-text"];

			if($db_conn){			

				$balance_due = executePlainSQL("select owing from members where member_id='". $user_id ."'");

				oci_fetch_all($balance_due, $due);
				$balance_due = $due["OWING"][0];


				//Commit changes
				logoff_oci();

			}
			else{
				echo "cannot connect";
				$e = OCI_Error(); // For OCILogon errors pass no handle
				alert(htmlentities($e['message']));
			}
		?>

		<h3> Member <?php echo $user_id ?>'s Balance Owing : $<?php echo $balance_due ?></h3>	

		<div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">
        <!-- Make the form with the user balance filled in -->
        	
        	<form method="POST" action="update-user-balance.php" class="form-signin">
        		<h2 class="form-signin-heading">Edit Balance</h2>
        		<hr>
        		<input type="hidden" name="user_id" class="form-control" value= <?php echo $user_id ?> >
        		<input type="hidden" name="username" value= <?php echo $username; ?> >
	        	<input type="hidden" name="password" value= <?php echo $password; ?> >

        		<h4>Balance</h4>
        		<input type="text" name="balance" class="form-control" placeholder="Balance" value= <?php echo $balance_due ?> required autofocus>
        		
        		<button class="btn btn-lg btn-primary btn-block" style="opacity:0.9;"type="submit">Update Balance</button>
      		</form>    

        </div>


        </div>

        <?php 
        	

        	//
        ?>

		<!-- Add Code Below -->
	</div> <!-- End of container div-->

</body>
</html>