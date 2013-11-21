<?php 
	include "report_errors.php";
	include "php/sql-functions.php"; 

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

	if($is_member){
		echo json_encode($row);
	}
?>