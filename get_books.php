<?php 
	include "report_errors.php";
	include "php/sql-functions.php"; 

	// Do not forget to use json_encoe when returning the result

	if($db_conn) {
		$row = $_POST['row'];
		$username = $_POST['username'];
		$member_id = $_POST['member_id']; //May not need this.

		$index = 0;
		foreach ($row["ISBN"] as $book_isbn) {
			//Make a field called 'reserved' in the rows array
			$reserved = get_whether_reserved($username, $book_isbn);

			$row['RESERVED'][$index] = $reserved;
			$index++;
		}

		echo json_encode($row);

		//Commit changes
		logoff_oci();
		

	}

	function get_whether_reserved($username , $isbn){

		//For Each ISBN, check whether or not the person has reserved it
		$tuple = array (
						":username" => $username,
						":isbn" => $isbn
					);
					$alltuples = array (
						$tuple
					);
		$result = executeBoundSQL("SELECT title FROM Makes_Reservation_or_Rental mror, Rental_Due_On r, Has_Books h, Members m 
									WHERE mror.rental_id = r.rental_id AND m.member_id = mror.member_id AND h.isbn=:isbn AND r.isbn=:isbn AND m.username = :username",$alltuples);

		oci_fetch_all($result, $res_row);

		$ret_val = 0; //0 is false

		if(count($res_row["TITLE"]) >= 1) {
			$ret_val = 1; //1 is true
		}

		return $ret_val;
	}

?>