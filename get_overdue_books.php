<?php 
	include "report_errors.php";
	include "php/sql-functions.php"; 

	// Do not forget to use json_encoe when returning the result

	if($db_conn) {
		$username = $_POST['username'];
		$member_id = $_POST['id']; //May not need this

		//For Each ISBN, check whether or not the person has reserved it
		$tuple = array (
						":username" => $username,
					);

		$alltuples = array (
			$tuple
		);

		$result = executeBoundSQL("select h.title
									from Makes_Reservation_Or_Rental mror, Members m, Rental_Due_On r, Has_Books h
									where mror.rental_id = r.rental_id AND m.member_id = mror.member_id AND r.isbn = h.isbn AND r.end_date < (select CURRENT_TIMESTAMP from DUAL) AND m.username = :username",$alltuples);

		oci_fetch_all($result, $row);

		if($result){
			$row['status'] = 'ok';
		}

		echo json_encode($row);

		//Commit changes
		logoff_oci();
		
	}
?>