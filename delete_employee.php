<!-- delete_employee.php -->

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
		<button type="button" class="btn btn-default" onclick="history.go(-1);"><span class="glyphicon glyphicon-chevron-left"></span> Back to Home </button>

		<hr>

        <div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">
        	<!-- PHP Script for deleting employees-->
			 <?php 
			  		$employee_id = $_POST['employee-delete-query-text'];

			  		function delete_info($id, $conn){
						$query = "DELETE FROM Librarians L WHERE L.employee_id=";
						$stmt = $query . $id;
						$s = oci_parse($conn, $stmt);
						$r = oci_execute($s);

						return $r;
					}

			  		if($db_conn){
						$result = executePlainSQL("SELECT COUNT(DISTINCT L.employee_id)  AS NUMENTRIES FROM Librarians L WHERE L.employee_id = ". $employee_id);
						oci_fetch_all($result, $row);

						$num_entries = intval($row["NUMENTRIES"][0]);
						$employee_exists = ($num_entries == 1)? true : false ; 

						if($employee_exists){
							$bool_result = delete_info($employee_id, $db_conn);
				        	echo "<h2> Deleting Information For Employee ID : {$employee_id} </h2> <hr>";

							if ($bool_result) {
								echo "<h3 style='color:#4cae4c;'>Successfully Deleted The Employee With ID : {$employee_id} </h3>";
							} else {
								echo "<h3 style='color:#d43f3a;'>There was some problem in deleting the information :(</h3>";
							}
			  			}
			  			else{ //The given employee does not exist
			  				echo "<h3 style='color:#428bca;'> The Employee with ID : {$employee_id} does not exist</h3>";
			  			}

			  			logoff_oci();
			  		}
			  		else{
			  			echo "<script>alert('Error Connecting to Database :(. Please Try Again Later.')</script>";
			  		}
			  ?>
        </div>


	</div> <!-- End of container div-->

</body>
</html>