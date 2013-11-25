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
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="custom/css/signin.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Only for select picker -->
    <script type="text/javascript" src="css/selectpick/bootstrap-select.js"></script>
    <link rel="stylesheet" type="text/css" href="css/selectpick/bootstrap-select.css">
    <script type="text/javascript">
	    $(window).on('load', function () {

	        $('.selectpicker').selectpicker({});
	        // $('.selectpicker').selectpicker('hide');

	        $('.search-input').tooltip({});
	        $('#list-count-books-btn').tooltip({});
	    });
	</script>

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
	
	<!-- Show Balance Owing -->
	<h3>Balance Owing : $<?php echo $row["OWING"][0] ?></h3>	
	
	<hr>

	<!-- DIV for searching books -->
		<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
			<h2><small>Please search for a book using ISBN number</small></h2>
			<form method="POST" action="book-search.php" class="form-inline">
		        <div class="form-group"><input data-toggle="tooltip" data-placement="left" title data-original-title="For listing all books, just leave this blank" 
		        		type="text" name="book-search-text" class="form-control search-input" placeholder="Win In 20 Days" autofocus></div>
	 			<select name="book-search-option" class="selectpicker">
					  <option>Title</option>
  					  <option>ISBN</option>
				</select>
				<select name="book-search-location" class="selectpicker" data-live-search="true">
					  <option>All Branches</option>
					  <option>Grouse Public Library</option>
					  <option>Seymour Public Library</option>
					  <option>Cypress Public Library</option>
					  <option>Cathedral Public Library</option>
					  <option>Coliseum Public Library</option>
				</select>
				<input type="hidden" name="member_uname" value= <?php echo $username; ?> >
		        <input type="hidden" name="member_id" value= <?php echo $row["MEMBER_ID"][0]; ?> >
				<button class="btn btn-primary btn-large" id="search-books-btn">Search</button>
			</form>
<!-- 			<p style="color:brown;"><small>Note: For listing all books, just enter " "(1 space) in the search box and select Title from dropdown</small></p>
 -->		</div>
	<!-- End of DIV for searching books  -->

	<!-- Start DIV for checked out books -->
		<div class="jumbotron text-center list-checked-out-books" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
			<div class='list-checked-out'>
				<h2><small>List All My Checked Out Books</small></h2>
				<form class="form-inline">
					<input type="hidden" name="member_uname" value= <?php echo $username; ?> >
			        <input type="hidden" name="member_id" value= <?php echo $row["MEMBER_ID"][0]; ?> >
					<button class="btn btn-primary btn-large btn-list-checked-out">List Checked Out Books</button>
				</form>
			</div>
			<div id='co-list-final' style="display:none;">
				<button class="btn btn-default" id='close-btn-list-checked-out' style="float:right;"><span class="glyphicon glyphicon-remove"></span></button>
				<h3 style="color:#428bca;">Book Titles Checked Out<h3>
				<div id="actual-list-co"></div>
			</div>
		</div>
	<!-- End of DIV for checked out books -->

	<!-- Start DIV for overdue books -->
	<div class="jumbotron text-center list-overdue-books" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
		<div class='list-overdue'>
			<h2><small>List All My Overdue Books</small></h2>
			<form class="form-inline">
				<input type="hidden" name="member_uname" value= <?php echo $username; ?> >
		        <input type="hidden" name="member_id" value= <?php echo $row["MEMBER_ID"][0]; ?> >
				<button class="btn btn-primary btn-large btn-list-overdue">List Overdue Books</button>
			</form>
		</div>
		<div id='od-list-final' style="display:none;">
			<button class="btn btn-default" id='close-btn-list-overdue' style="float:right;"><span class="glyphicon glyphicon-remove"></span></button>
			<h3 style="color:#428bca;">Book Titles Past Overdue<h3>
			<div id="actual-list"></div>
		</div>
	</div>
	<!-- End of DIV for checked out books -->


	<!-- Code for Librarians (permission 1) AND DBA's (permission 1). The below code only gets executed if the member has enough permissions. First, check if permissions are appropriate-->
	<?php if ($row["PERMISSIONS"][0] == '1' || $row["PERMISSIONS"][0] == '0') :?>  

			<!-- Start of DIV for listing the number of copies of a particular book at a particular branch -->
			<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
				<h3> List Number of Book Copies AT a Branch</h3>
<!-- 				<h4> Note :  Also lists the branches with the maximum and minimum copies for the book (if they have any at all)</h4>
 -->				<form method="POST" action="list-copy-by-branch.php" class="form-inline">
			        <div class="form-group"><input type="text" name="book-search-text" class="form-control list-count-copies" placeholder="Win In 20 Days" required autofocus></div>
					<select name="book-search-location" class="selectpicker" data-live-search="true">
						  <option>Grouse Public Library</option>
						  <option>Seymour Public Library</option>
						  <option>Cypress Public Library</option>
						  <option>Cathedral Public Library</option>
						  <option>Coliseum Public Library</option>
					</select>
					<button class="btn btn-primary btn-large" id="list-count-books-btn" 
    						 data-toggle="tooltip" data-placement="right" title data-original-title=" Note :  Also lists the branches with the maximum and minimum copies for the book (if they have any at all)">List Count</button>
				</form>				
			</div>
			<!-- Start of DIV for listing the number of copies of a particular book at a particular branch -->

			<!-- Start of DIV for Delete From Rentals_due_on with Cascade -->
			<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
				<h3> Delete Rental Information After Book Return</h3>
				<form method="POST" action="delete_rental_information.php" class="form-inline">
					<!-- Delete Rental Due on by entering rental id -->
			        <div class="form-group"><input type="text" name="rental-delete-query-text" class="form-control" placeholder="Enter Rental ID e.g. 1" required autofocus></div>
					<button class="btn btn-danger btn-large" id="delete-rental-information-button">DELETE</button>
				</form>				
			</div>
			<!-- End of DIV for Delete From Rentals_due_on with Cascade -->


			<!-- Start of DIV for Update From Balance -->
			<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
				<h3> Check and Update Member's Overdue Balance</h3>
				<form method="POST" action="edit-user-balance.php" class="form-inline">
					<!-- Delete Rental Due on by entering rental id -->
			        <div class="form-group"><input type="text" name="member-id-text" class="form-control" placeholder="e.g. 10000001" required autofocus></div>
					<button class="btn btn-danger btn-large" id="delete-rental-information-button">UPDATE</button>
					<input type="hidden" name="member_uname" value= <?php echo $username; ?> >
	       			<input type="hidden" name="member_pwd" value= <?php echo $password; ?> >
				</form>				
			</div>
			<!-- End of DIV for Delete From Rentals_due_on with Cascade -->

			<?php if ($row["PERMISSIONS"][0] == '1') : ?>
				<!-- Start of DIV for Delete From Employees  -->
				<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
					<h3> Delete Librarian</h3>
					<form method="POST" action="delete_employee.php" class="form-inline">
						<!-- Delete Employee by entering rental id -->
				        <div class="form-group"><input type="text" name="employee-delete-query-text" class="form-control" placeholder="e.g. 10000001" required autofocus></div>
						<button class="btn btn-danger btn-large" id="delete-employee-information-button">DELETE</button>
					</form>				
				</div>
				<!-- End of DIV for Delete From Rentals_due_on with Cascade -->

			<?php endif; ?>
	<?php endif; ?>
	<!-- End of Code for Librarians (permission 1) AND DBA's (permission 1). -->

			<?php } else { ?>
			<div class="jumbotron text-center" style="padding: 10px 10px 30px 10px; background-color: #DDDDDD;">
				<h3> Invalid Login Credentials. Please press back and try again. </h3>
				<button type="button" class="btn btn-danger" onclick="window.location.href='index.php'">
					<span class="glyphicon glyphicon-chevron-left"></span> Back to Login Page 
				</button>
			</div>
			<?php } ?>

<!-- Begin of Script for listing checked-out books -->
	<script type="text/javascript">

	//This script block is only for checked out books
		$('.btn-list-checked-out').click(function(){
			event.preventDefault();
			var username = $("input[name='member_uname']").val();

			//Ajax query to get all books overdue for the user
			$.ajax({
      			url: 'get_checked_out_books.php',
      			type: 'POST',
      			dataType: 'json',
      			data: { 'username': username},
      			success: function(data,status) {
      				if (data.status == "ok"){
	      				var title = data.TITLE;

		      			//Empty DIV's for overdue books first 
      					$('#actual-list-co','#co-list-final').empty();

		      			$('.list-checked-out','.list-checked-out-books').fadeOut(500, function(){
		      				if(title.length >= 1){
		      					for (var i = 0; i < title.length; i++) {
			      					$book_title = "<h4>" + title[i] + "</h4>";
			      					$('#actual-list-co','#co-list-final').append($book_title);
			      				};
		      				}
		      				else {
		      					$no_items_overdue = "<h4>No Items Checked Out :)</h4>";
		      					$('#actual-list-co','#co-list-final').append($no_items_overdue);
		      				}
			      			$('#co-list-final','.list-checked-out-books').fadeIn(500);
		      			});
	      				}
      				else {
      					alert("Sorry, there was on error is processing your request :(. Please Try again.");
      				}
      			},
      			error: function(xhr, desc, err){
  					alert("Sorry, there was on error is processing your request :(. Please Try again.");
      				console.log("Reached error during ajax fetching of list checked out books");
      			}
      		});
      	});

		$('#close-btn-list-checked-out').click(function(event){
			event.preventDefault();

			//Reverse above FadeOut and FadeIn
			$('#co-list-final','.list-checked-out-books').fadeOut(500,function(){
				$('.list-checked-out','.list-checked-out-books').fadeIn(500);
			});
		});

	</script>
<!-- End of Script for listing checked out books -->

<!-- Begin of Script for listing overdue books -->
	<script type="text/javascript">

		$('.btn-list-overdue').click(function(){
			event.preventDefault();
			var username = $("input[name='member_uname']").val();
			var member_id = $("input[name='member_id']").val();

			//Ajax query to get all books overdue for the user
			$.ajax({
      			url: 'get_overdue_books.php',
      			type: 'POST',
      			dataType: 'json',
      			data: { 'username': username, 'id' : member_id},
      			success: function(data,status) {
      				if (data.status == "ok"){
	      				var title = data.TITLE;

		      			//Empty DIV's first
      					$('#actual-list','#od-list-final').empty();

		      			$('.list-overdue','.list-overdue-books').fadeOut(500, function(){
		      				if(title.length >= 1){
		      					for (var i = 0; i < title.length; i++) {
			      					$book_title = "<h4>" + title[i] + "</h4>";
			      					$('#actual-list','#od-list-final').append($book_title);
			      				};
		      				}
		      				else {
		      					$no_items_overdue = "<h4>No Items Past Overdue :)</h4>";
		      					$('#actual-list','#od-list-final').append($no_items_overdue);
		      				}
			      			$('#od-list-final','.list-overdue-books').fadeIn(500);
		      			});
	      				}
      				else {
      					alert("Sorry, there was on error is processing your request :(. Please Try again.");
      				}
      			},
      			error: function(xhr, desc, err){
  					alert("Sorry, there was on error is processing your request :(. Please Try again.");
      				console.log("Reached error during ajax fetching of list overdue");
      			}
      		});
      	});

		$('#close-btn-list-overdue').click(function(event){
			event.preventDefault();

			//Reverse above
			$('#od-list-final','.list-overdue-books').fadeOut(500,function(){
				$('.list-overdue','.list-overdue-books').fadeIn(500);
			});
		});

	</script>
<!-- End of Script for listing overdue books -->

</div> <!-- End of container div-->
</body>
</html>