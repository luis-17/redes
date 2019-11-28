<?php
/**
*	@author 	Ing. Israel Barragan C.  Email: ibarragan at behstant dot com
*	@since 		07-Jun-2013
*	##########################################################################################
*	Comments:
*	This includes a function that will return a PDO Instance.
*	##########################################################################################
*	@version
*	##########################################################################################
*	1.0	|	06-Jun-2013	|	Creation of new class with parameter as constructor.
*	##########################################################################################
*/

function dbConnect (){
 	/*$conn =	null;
 	$host = '50.62.209.11:3306';
 	$db = 	'doctorweb';
 	$user = 'redPeru_doctor';
 	$pwd = 	'doc$%&54321';*/

 	$conn =	null;
 	$host = 'rdsinstancemysql.czvvckkesgis.us-east-2.rds.amazonaws.com:3306';
 	$db = 	'new_redes_peru';
 	$user = 'rsaws2019';
 	$pwd = 	'hcarsAcces2019$';
 	

	try {
	   	$conn = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pwd);
		//echo 'Connected succesfully.<br>';
	}
	catch (PDOException $e) {
		echo '<p>Cannot connect to database !!</p>';
		echo '<p>'.$e.'</p>';
	    exit;
	}
	return $conn;
 }
 
 ?>