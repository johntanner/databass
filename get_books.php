<?php 
	include "report_errors.php";
	include "php/sql-functions.php"; 

	//Do not use intval when using isbn #'s'

	// Do not forget to use json_encoe when returning the result

	if($db_conn) {
		$row = $_POST['row'];
		$username = $_POST['username'];
		$member_id = $_POST['member_id']; //May not need this.

		$index = 0;
		foreach ($row["ISBN"] as $book_isbn) {
			$curr_book_branch_id = $row["BRANCH_ID"][$index];

			//Make a field called 'reserved' in the rows array
			$reserved = get_whether_reserved($username, $book_isbn, $curr_book_branch_id);

			//Get the Branch Location for the book given the branch id
			$row['BRANCH_LOC'][$index] = get_branch_loc($curr_book_branch_id);

			$row['RESERVED'][$index] = $reserved;
			$index++;
		}

		echo json_encode($row);

		//Commit changes
		logoff_oci();

	}

	function get_branch_loc($branch_id){
		//For Each ISBN, check whether or not the person has reserved it
		$tuple = array (
					":branch_id" => $branch_id,
					);
		$alltuples = array (
			$tuple
		);

		$result= executeBoundSQL("SELECT b.name FROM Branches b WHERE b.BRANCH_ID = :branch_id",$alltuples);
		
		oci_fetch_all($result, $res_row);

		return $res_row["NAME"][0];
	}

	function get_whether_reserved($username , $isbn, $current_book_branch_id){

		//For Each ISBN, check whether or not the person has rented it out before
		$tuple = array (
						":username" => $username,
						":isbn" => $isbn,
						":branch_id" => $current_book_branch_id
					);
		$alltuples = array (
			$tuple
		);
		$result = executeBoundSQL("SELECT title FROM Makes_Rental mr, Rental_Due_On r, Has_Books h, Members m 
									WHERE mr.rental_id = r.rental_id AND r.branch_id=:branch_id AND m.member_id = mr.member_id AND h.isbn=:isbn AND r.isbn=:isbn AND m.username = :username",$alltuples);

		oci_fetch_all($result, $res_row);

		$ret_val = 0; //0 is false

		if(count($res_row["TITLE"]) >= 1) {
			$ret_val = 1; //1 is true
		}

		return $ret_val;
	}

?>