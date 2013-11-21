<!-- list-copy-by-branch.php -->
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

	<?php
	if ($db_conn){
		//Get the book title AND the branch location
		$book_search_text = $_POST["book-search-text"];
		$book_search_location = $_POST["book-search-location"];

        //Converting branch name into branch ID
		$branch_location_id = executePlainSQL("select branch_id from branches where name='". $book_search_location ."'");
		oci_fetch_all($branch_location_id, $branch);

		$branch_location_id = $branch["BRANCH_ID"]["0"];

		//Get the count of the particular book at the just obtained branch id
		$tuple = array (
			":branch_location_id" => $branch_location_id
		);

		$alltuples = array (
			$tuple
		);

		$query = "select count(COPY_ID) from book_copy c, has_books b WHERE c.ISBN = b.ISBN AND b.TITLE = '". $book_search_text ."' AND c.BRANCH_ID = :branch_location_id AND b.branch_id = :branch_location_id";

		$result = executeBoundSQL($query,$alltuples);

		oci_fetch_all($result, $row);

		$count = $row["COUNT(COPY_ID)"][0];

		//Commit
		logoff_oci();

	}

	else{
		echo "cannot connect";
		$e = OCI_Error(); // For OCILogon errors pass no handle
		echo htmlentities($e['message']);
	}

	?>
	<div class="container">
		<button type="button" class="btn btn-default" onclick="history.go(-1);"><span class="glyphicon glyphicon-chevron-left"></span> Back to Home </button>
		<hr>

        <div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">

        <h3>Showing Number of Book Copies <hr style="width:50%;"><b><?php echo "'" . $book_search_text. "'</b> at <b>" . $book_search_location; ?></b></h3>

        <h4>Number of Copies : <b><?php echo $count; ?></b></h4>
        	
        </div>

	</div> <!-- End of container div-->

</body>
</html>