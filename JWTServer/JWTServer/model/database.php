<?php


// connects to a database

$dsn1 = "mysql:host=localhost;dbname=jwtserver";
$username1 = "root";
$password1 = "";
 
try {
    $dbs = new PDO($dsn1, $username1, $password1);
    //set up error reporting on server
    $dbs->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $dbs->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_reporting(E_ALL);
} catch (PDOException $ex) {
    //echo "Connection Failure Error is " . $ex->getMessage();
    // redirect to an error page passing the error message
    header("Location:../view/error.php?msg=" . $ex->getMessage());

}










