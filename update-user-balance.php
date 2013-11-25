<!-- update-user-balance.php -->

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

    <style type="text/css">
		.modal-dialog {
		  width: auto;
		  height: auto;
		  padding: 0;
		}

		.modal-content {
		  height: auto;
		  border-radius: 0;
		}
    </style>

    <!-- Custom styles for this template -->
    <link href="custom/css/signin.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
    	$(window).on('load',function(){
    		$("#show_modal_btn").on("click",function(){
    			$("#myModal").modal();
    		}); 
    	});

    </script>

    <!-- PHP Files for reporting errors and sql functions -->
    <?php 
    	include "report_errors.php";
	  	include "php/sql-functions.php"; 
  	?>
  </head>

  <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Showing Database Table</h4>
	      </div>
	      <div class="modal-body">
	        <table class="table" id="db-table">
	        </table>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<body>
		
	<?php
	    $username = $_POST["username"];
	    $password = $_POST["password"];
    	$user_id = $_POST["user_id"];
		$balance = $_POST["balance"];

		// Update the Dataabase with the new values above

		$tuple = array (
			":user_id" => $user_id,
			":balance" => $balance,
		);

		$alltuples = array (
			$tuple
		);

		$vartest = "update members set owing=:balance, where member_id=:user_id";

		$result_bool = executeBoundSQL("update members set owing=:balance where member_id=:user_id", $alltuples);
		$oci_commit = OCICommit($db_conn);

		$tableToDisplay = getTable("MEMBERS");
		echo "<script>$('#db-table','.modal-body').append('{$tableToDisplay}')</script>";

		//Commit changes
		logoff_oci();

	?>



	<div class="container">
		<div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">
		<!-- On success commit -->
		<?php if ($result_bool) { ?> 
			<!-- <?php echo "<table>" . $tableToDisplay. "</table>"; ?> -->
			<h2> Member's Balance Was Updated Succesfully </h2>
			<img src="custom/images/success.png" height="200px" width="200px">
		<?php } else { ?>
			<h2> Error when updating balance <img src="custom/images/smiley-sad.png" height="20px" width="20px"> </h2>

			<form method="POST" action="edit-user-balance.php">
		        <input type="hidden" name="user_id" value= <?php echo $user_id; ?> >
		        <input type="hidden" name="balance" value= <?php echo $balance; ?> >
		        <button class="btn btn-danger btn-large">Try Again</button>
			</form>
		<?php } ?>

			<form method="POST" action="login.php">
		        <input type="hidden" name="username" value= <?php echo $username; ?> >
		        <input type="hidden" name="password" value= <?php echo $password; ?> >
				<button class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back to Home </button><br>
				<!-- Button trigger modal -->
				<button class="btn btn-primary btn-lg" id="show_modal_btn" data-toggle="modal" data-target="#myModal">Show Database Table For Members</button>
			</form>


		</div>


        

		<!-- Add Code Below -->
	</div> <!-- End of container div-->

</body>
</html>