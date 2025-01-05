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
    $select = "SELECT * from users";
    $result = $db_conn->query($select);

    while ($row = $result->fetch_assoc()) {
        var_dump($row);
    }

    $stmResult = $result->fetch_assoc();
    var_dump($stmResult);
    //$db_conn ->close();
    

    echo "vor insert";
    $preparedInsertStatement = "INSERT INTO users (username, password, useremail) values (?,?,?)";
    $stmt = $db_conn->prepare($preparedInsertStatement);
    $stmt->bind_param("sss", $uname, $pass, $mail);

    echo "nach bind";
    $uname = "Sheldon";
    $pass = password_hash("Cooper", PASSWORD_DEFAULT);
    $mail = "test@test.com";
    echo "vor execeute";
    //$stmt->execute();
    echo "nach execute";

    //$stmResult = $result -> fetch_assoc();
    //var_dump($stmResult);

    $sqlstatement = "select id, username, password, useremail from users where username like ?";
    $selectStmt = $db_conn-> prepare($sqlstatement);
    $selectStmt -> bind_param("s", $selectUsername);
    $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail);
    $selectUsername ="%Shel%";
    $selectStmt -> execute();
    //$db_conn->close();



    //echo "<pre>".$result -> fetch_assoc()."</pre>";
    ?>
    <table border="1">
        <th> id</th>
        <th>username</th>
        <th>password</th>
        <th>email</th>
        <?php
        
        while($selectStmt ->fetch()) {
            echo "<tr>"; 
            echo "<td>".$rId."</td>";
            echo "<td>".$rUsername."</td>";
            echo "<td>".$rPassword."</td>";
            echo "<td>".$rEmail."</td>";

            echo "</tr>";    
        } 
        
        ?>
    
    </table>
</body>