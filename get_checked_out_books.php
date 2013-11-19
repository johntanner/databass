<?php 
	include "report_errors.php";
	include "php/sql-functions.php"; 

	// Do not forget to use json_encoe when returning the result

	if($db_conn) {
		$username = $_POST['username'];

		$tuple = array (
						":username" => $username,
					);
					$alltuples = array (
						$tuple
					);
		$result = executeBoundSQL("SELECT title FROM Makes_Reservation_or_Rental mror, Rental_Due_On r, Has_Books h, Members m 
									WHERE mror.rental_id = r.rental_id AND m.member_id = mror.member_id AND r.isbn=h.isbn AND m.username = :username",$alltuples);

		oci_fetch_all($result, $row);

		if($result){
			$row['status'] = 'ok';
		}

		echo json_encode($row); 
		
		//Commit changes
		logoff_oci();
		

	}

?>