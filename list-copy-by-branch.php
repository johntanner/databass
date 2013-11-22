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

		//Get Branches with minimum and maximum number of copies for the given book
		//First get the ISBN number for the book
		$query = "SELECT DISTINCT h.ISBN FROM Has_Books h WHERE h.title = '". $book_search_text ."'";
		$result = executePlainSQL($query);
		oci_fetch_all($result, $row);

		$isbn = $row["ISBN"][0];

		$view_query = "CREATE VIEW copies_of_books AS (
						  SELECT bc.branch_id , COUNT(bc.copy_id) AS copy_count 
						  FROM Book_Copy bc 
						  WHERE bc.ISBN='" . $isbn . "'
						  GROUP BY bc.branch_id )";

		executePlainSQL($view_query);

		$max_query = "SELECT c.branch_id, c.copy_count FROM copies_of_books c WHERE c.copy_count = (SELECT MAX(c1.copy_count) FROM copies_of_books c1)";
		$min_query = "SELECT c.branch_id, c.copy_count FROM copies_of_books c WHERE c.copy_count = (SELECT MIN(c1.copy_count) FROM copies_of_books c1)";

		$res_max_query = executePlainSQL($max_query);
		$res_min_query = executePlainSQL($min_query);

		oci_fetch_all($res_max_query, $max_copies); //Get branch id and number of copies for the branch with the most number of copies
		oci_fetch_all($res_min_query, $min_copies); //Get branch id and number of copies for the branch with the least number of copies

		//Get branch locations from the given branch_id's
		$branch_loc_max = executePlainSQL("select b.name from branches b where b.branch_id=". $max_copies["BRANCH_ID"][0]);
		$branch_loc_min = executePlainSQL("select b.name from branches b where b.branch_id=". $min_copies["BRANCH_ID"][0]);

		oci_fetch_all($branch_loc_max, $branch_loc_max);
		oci_fetch_all($branch_loc_min, $branch_loc_min);

		$branch_with_max_copies = array("branch_loc" => $branch_loc_max["NAME"][0] , "copy_count" => $max_copies["COPY_COUNT"][0] );
		$branch_with_min_copies = array("branch_loc" => $branch_loc_min["NAME"][0] , "copy_count" => $min_copies["COPY_COUNT"][0] );

		executePlainSQL("DROP VIEW copies_of_books");
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

        <div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">

        <h3>Branch Location with maximum number of copies for <b><?php echo "'" . $book_search_text ."'"; ?></h3>
        <h4><b><?php echo $branch_with_max_copies["branch_loc"]; ?></b> with <b>Copy Count : </b><?php echo $branch_with_max_copies["copy_count"]?></h4>

        <hr>

	    <h3>Branch Location with minimum number of copies for <b><?php echo "'" . $book_search_text ."'"; ?></h3>
        <h4><b><?php echo $branch_with_min_copies["branch_loc"]; ?></b> with <b>Copy Count : </b><?php echo $branch_with_min_copies["copy_count"]?></h4>

        </div>


	</div> <!-- End of container div-->

</body>
</html>