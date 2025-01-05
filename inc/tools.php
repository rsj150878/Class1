<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();



require_once "conf/dbaccess.php";

$db_conn = new mysqli($host, $user, $password, $dbname, $port);
$sqlstatement = "select id, username, password, useremail, role, status, firstname, lastname, sex from users where username = ?";
$selectStmt = $db_conn->prepare($sqlstatement);
$selectStmt->bind_param("s", $_SESSION["user"]);
$selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail, $rRole, $rStatus, $rFirstname, $rLastname, $rSex);
$selectStmt->execute();
$selectStmt->fetch();
$selectStmt->close();


function clean_input($input): string
{
    $output = trim($input);
    $output = stripslashes($output);
    $output = htmlspecialchars($output);

    return $output;
}


function create_session_db($cleanedUser, $cleanedPassword)
{
    include "conf/dbaccess.php";

    $db_conn = new mysqli($host, $user, $password, $dbname, $port);

    $sqlstatement = "select id, username, password, useremail from users where username = ? and status = 'ACTIVE'";
    $selectStmt = $db_conn->prepare($sqlstatement);
    $selectStmt->bind_param("s", $cleanedUser);
    $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail);
    $selectStmt->execute();

    $selectStmt->fetch();
    $hashvalue = password_hash($cleanedPassword, PASSWORD_DEFAULT);

    $selectStmt->close();
    $db_conn->close();
    if (!isset($rPassword)) {

        $_SESSION["activeSession"] = False;

        return True;
    }


    if (password_verify($cleanedPassword, $rPassword)) {
        $_SESSION["user"] = $cleanedUser;
        $_SESSION["activeSession"] = True;

        return False;

    } else {

        $_SESSION["activeSession"] = False;

        return True;
    }

}

function check_user_vorhanden_db($newUser)
{

    include "conf/dbaccess.php";

    $db_conn = new mysqli($host, $user, $password, $dbname, $port);

    //echo "newuser " . $newUser;
    $sqlstatement = "select id, username, password, useremail from users where username = ?";
    $selectStmt = $db_conn->prepare($sqlstatement);
    $selectStmt->bind_param("s", $newUser);
    $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail);
    $selectStmt->execute();
    $selectStmt->fetch();
    $selectStmt->close();
    $db_conn->close();

    //echo "resultEmail" . $rEmail;
    //echo "rows: " . $selectStmt->num_rows;
    return (isset($rUsername));

}

function insert_user_db($newUser, $cleaned_Password, $firstName, $lastName, $userEmail, $geschlecht)
{

    include "conf/dbaccess.php";

    $db_conn = new mysqli($host, $user, $password, $dbname, $port);

    //echo "newuser " . $newUser;
    $hashvalue = password_hash($cleaned_Password, PASSWORD_DEFAULT);
    $insertStatement = "insert into users (username, password, useremail, firstname, lastname, sex) values (?,?,?,?,?,?)";
    $insertStatement = $db_conn->prepare($insertStatement);
    $insertStatement->bind_param("ssssss", $newUser, $hashvalue, $userEmail, $firstName, $lastName, $geschlecht);

    $insertStatement->execute();

    $insertStatement->close();
    $db_conn->close();
    //echo "neue id" . $insertStatement->insert_id;


}

function is_session_active()
{
    return isset($_SESSION["activeSession"]) && $_SESSION["activeSession"];
}



function end_session()
{

    session_unset();
    session_destroy();
    $_SESSION["activeSession"] = False;

}

function update_user($userId, $pUser, $firstName, $lastName, $userEmail, $geschlecht, $status)
{

    include "conf/dbaccess.php";


    $db_conn = new mysqli($host, $user, $password, $dbname, $port);

    $insertStatement = "update users set username=?, useremail=?, firstname=?, lastname=?, sex=?, status=? where id=?";
    $insertStatement = $db_conn->prepare($insertStatement);
    $insertStatement->bind_param("ssssssi", $pUser, $userEmail, $firstName, $lastName, $geschlecht, $status, $userId);

    $insertStatement->execute();
    $insertStatement->close();
    $db_conn->close();




}

function checkPassword($cleanedUser, $cleanedPassword)
{
    include "conf/dbaccess.php";

    $db_conn = new mysqli($host, $user, $password, $dbname, $port);

    $sqlstatement = "select id, username, password, useremail from users where username = ?";
    $selectStmt = $db_conn->prepare($sqlstatement);
    $selectStmt->bind_param("s", $cleanedUser);
    $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail);
    $selectStmt->execute();

    $selectStmt->fetch();
    $selectStmt->close();
    $db_conn->close();
    return password_verify($cleanedPassword, $rPassword);


}

function update_password($id, $cleaned_Password)
{

    include "conf/dbaccess.php";

    $db_conn = new mysqli($host, $user, $password, $dbname, $port);

    $hashvalue = password_hash($cleaned_Password, PASSWORD_DEFAULT);
    $insertStatement = "update users set password=? where id=?";
    $insertStatement = $db_conn->prepare($insertStatement);
    $insertStatement->bind_param("si", $hashvalue, $id);

    $insertStatement->execute();
    $insertStatement->close();
    $db_conn->close();
}

