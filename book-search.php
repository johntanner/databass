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
				$book_search_text  =  $_POST["book-search-text"]; //This is actually the ISBN number or title
				$book_search_option  =  $_POST["book-search-option"]; //This is the book search option (title or ISBN)
				$username = $_POST["member_uname"];
				$member_id = $_POST['member_id'];

				if($book_search_option == 'ISBN'){ //Book Search Option is ISBN number
					$tuple = array (
					":isbn" => $book_search_text
					);
					$alltuples = array (
						$tuple
					);
					$result = executeBoundSQL("select * from has_books where isbn=:isbn", $alltuples);
				} 
				else { //Book Search option is title

					$query_string = "select * from has_books hb where hb.title like '%" . $book_search_text . "%'";
					$result = executePlainSQL($query_string);
				}

				oci_fetch_all($result, $row);
				
				//Commit changes
				logoff_oci();

				$book_found = false;

				if(count($row["ISBN"]) > 0) {
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
			<div id="all_results" class='text-center'><h3>Showing All Results</h3></div>			

			<!-- Execute ajax query to get whether the book has been checked out or not and then print the table -->
			<div id="show_results"></div>

			<script type="text/javascript">

			var row = JSON.parse('<?php echo json_encode($row) ?>');
			var username = JSON.parse('<?php echo json_encode($username) ?>');
			var member_id = JSON.parse('<?php echo json_encode($member_id) ?>');

			//var $jumbotron = '<div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">';
			var $jumbotron = '<div class="bs-example text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">';

			$.ajax({
				//Use data attribute
      			url: 'get_books.php', //Get books that have been reserved and not reserved
      			type: 'POST',
      			dataType: 'json',
      			data: { 'username': username, 'member_id' : member_id, 'row' : row},
      			success: function(data,status) {
      				var length = data.ISBN.length;
      				if(length > 0){

      					for (var i = 0; i < length; i++) {
      						//Show the jumbotron

      						$desc_class = '<dl class="dl_horizontal">'
	      					var row_arr = new Array();
	      					row_arr["ISBN"] = data.ISBN[i];
	      					row_arr["Title"] = data.TITLE[i];
	      					row_arr["Author"] = data.AUTHOR[i];
	      					row_arr["Branch_ID"] = data.BRANCH_ID[i];
	      					row_arr["Publisher"] = data.PUBLISHER[i];
	      					row_arr["Reserved"] = data.RESERVED[i];
		      				$details = make_check_out_row(row_arr);
		      				$("#show_results").append($jumbotron + $details + '</dl>' + "</div>" + "<hr>");
	      				}

      				}
      				
      			},
      			error: function(xhr, desc, err){
      				console.log("Reached error");
      			}
      		  });

			function make_check_out_row(data){
				var $ret_val;
				var $isbn= "<dt>ISBN </dt><dd>" + data.ISBN +"</dd>";
				var $title = "<dt>Title </dt><dd>" + data.Title +"</dd>";
				var $author= "<dt>Author </dt><dd>" + data.Author +"</dd>";
				// var $branch_id_row = "<td>" + data.Branch_ID +"</td>";
				var $publisher = "<dt>Publisher </dt><dd>" + data.Publisher +"</dd>";
				var $reserved;
				
				if (data.Reserved == 1){
					// $reserved = '<dt>Status </dt><dd><button class="btn btn-primary btn-large reserved-books-btn" disabled="disabled">Already Checked Out</button></dd>';					
					$reserved = '<dt>Status </dt><dd class="btn btn-danger reserved-books-btn" disabled="disabled">Already Checked Out</dd>';					

				}
				else{
					// $reserved = '<dt>Status </dt><dd><button class="btn btn-primary btn-large reserved-books-btn">Available For Check Out</button></dd>';				
					$reserved = '<dt>Status </dt><dd class="btn btn-primary reserved-books-btn" disabled="disabled">Available For Check Out</dd>';						
				}
				$ret_val = $isbn + $title + $author +  $publisher + $reserved;
				return $ret_val;
			}


			</script>
	    <?php else: ?>
	    	<div class="jumbotron text-center" style="padding-left: 10px; padding-top: 10px; padding-bottom: 10px; background-color: #DDDDDD;">
	    		Book Not Found <img src="custom/images/smiley-sad.png" height="20px" width="20px">
	    	</div>
	    <?php endif ?>
	</div> <!-- End of container div-->

</body>
</html>