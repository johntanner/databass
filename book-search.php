<!-- book-search.php -->

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
		<!-- Add Code Below -->

		<?php 

			if($db_conn){			
				$book_search_text  =  $_POST["book-search-text"]; //This is actually the ISBN number
				// $book_search_option =  $_POST["book-search-option"];
				$tuple = array (
				":isbn" => $book_search_text,
				);
				$alltuples = array (
					$tuple
				);
				$result = executeBoundSQL("select * from book_copy where isbn=:isbn", $alltuples);
				oci_fetch_all($result, $row);
				
				//Commit changes
				logoff_oci();

				$book_found = false;

				if(count($row["COPY_ID"]) > 0) {
					$book_found = true;
				}

			}
			else{
				echo "cannot connect";
				$e = OCI_Error(); // For OCILogon errors pass no handle
				echo htmlentities($e['message']);
			}
		?>

		<?php if ($book_found) : ?>
	        <div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">This a \XJKSBNADCKJNS</div>
	    <?php else: ?>
	    	<div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">
	    		Book Not Found <img src="custom/images/smiley-sad.png" height="20px" width="20px">
	    	</div>
	    <?php endif ?>
	</div> <!-- End of container div-->

</body>
</html>