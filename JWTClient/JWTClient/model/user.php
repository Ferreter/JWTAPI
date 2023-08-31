<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


function Signup($username, $password, $member) {
    global $db;
    $query = "INSERT INTO user (username, password, memberType) VALUES (:username, :password, :member)";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":member", $member);
    
    try {
        $statement->execute();
        
        // Set up session for the registered user
        $_SESSION["login"] = true;
        $_SESSION["username"] = $username;
        $_SESSION["member"] = getMembeType($username);
        
        // Fetch the user's ID from the database
        $userIdQuery = "SELECT id FROM user WHERE username = :username";
        $userIdStatement = $db->prepare($userIdQuery);
        $userIdStatement->bindValue(":username", $username);
        $userIdStatement->execute();
        
        // Fetch the ID
        $userId = $userIdStatement->fetchColumn();
        
        // Store the ID in the session
        $_SESSION["user_id"] = $userId;
        
        $userIdStatement->closeCursor();
        
    } catch (PDOException $ex) {
        // Redirect to an error page passing the error message 
        header("Location:../view/error.php?msg=" . $ex->getMessage());
        exit();
    }
    $statement->closeCursor();
    return true;
}


function getMembeType($username){
     global $db;
    $query = "SELECT memberType FROM user WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    try {
        $statement->execute();
    } catch (Exception $ex) {
        header("Location:../view/error.php?msg=" . $ex->getMessage());
        exit();
    }
    
      $member = $statement->fetch();
    $statement->closeCursor();
  
    return $member["memberType"];
}

function check_user($username, $password) {
    global $db;
    $query = "SELECT * FROM user WHERE username = :username AND " . "password = :password";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $password);
    try {
        $statement->execute();
    } catch (Exception $ex) {
        header("Location:../view/error.php?msg=" . $ex->getMessage());
        exit();
    }
    $count = $statement->rowCount();
    if ($count != 1) {
        return FALSE;
    }
  
    return TRUE;
}


function request_token($username, $password,$member) {
    global $db;
    $query = "SELECT * FROM user WHERE username = :username AND " . "password = :password AND " . "memberType = :member";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":member", $member);
    try {
        $statement->execute();
    } catch (Exception $ex) {
        header("Location:../view/error.php?msg=" . $ex->getMessage());
        exit();
    }
    $count = $statement->rowCount();
    if ($count != 1) {
        return FALSE;
    }
    session_start();
    $user = $statement->fetch();
    $statement->closeCursor();
    $_SESSION['userId'] = $user['userId'];
    $_SESSION['userType'] = $user['userType'];
    return TRUE;
}

function upgradeToPremium($username) {
    global $db; 

    try {
        $query = "UPDATE user SET memberType = 1 WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(":username", $username);
        $statement->execute();
        $rowCount = $statement->rowCount();

        return $rowCount > 0; // Return true if the update was successful
    } catch (PDOException $ex) {
        // Handle the error gracefully, log, or display an appropriate message
        header("Location:../view/error.php?msg=" . $ex->getMessage());
    }
}

?>