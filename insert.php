<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>
    <?php
    require_once("dbacces.php");
    error_reporting(E_ALL); ini_set("display_errors",1);
    echo 'Current PHP version: ' . phpversion();

    echo "bin da";
    echo $host . $user . $password . $dbname;

    $db_conn = new mysqli($host, $user, $password, $dbname, $port);
    echo "bin da2";

    if ($db_conn->connect_error) {
        echo "in fehler;";
        echo "Connection error " . $db_conn->connect_error;
        exit();

    }


    echo "vor insert";
    $preparedInsertStatement = "INSERT INTO users (username, password, useremail) values (?,?,?)";

    echo "vor prepare";
    $stmt = $db_conn->prepare($preparedInsertStatement);
    echo "nach prepare";
    if ($stmt -> error) {
        echo "error" . $stmt->error;
    }

    echo "vor bind";
    $stmt->bind_param("sss", $uname, $pass, $mail);

    echo "nach bind";
    $uname = "Sheldon";
    $pass = password_hash("Cooper", PASSWORD_DEFAULT);
    $mail = "test@test.com";
    echo "vor execeute";
    $stmt->execute();
    echo "nach execute";

    //$stmResult = $result -> fetch_assoc();
    //var_dump($stmResult);
    $db_conn->close();



    //echo "<pre>".$result -> fetch_assoc()."</pre>";
    ?>
</body>