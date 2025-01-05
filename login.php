<?php

include 'inc/tools.php';
include 'inc/daten.php';
unset($error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST["formPassword"])) {

    unset($error);

  } else {


    $error = create_session_db(clean_input($_POST["formUserid"]), clean_input($_POST["formPassword"]));
    //unset($_POST["formPassword"]);
  }
}


if (is_session_active()) {

  // user lesen - die daten standen beim lesen in tools.php noch nicht zur verfügung
  require_once "conf/dbaccess.php";

  $db_conn = new mysqli($host, $user, $password, $dbname, $port);
  $sqlstatement = "select id, username, password, useremail, role, status, firstname, lastname, sex from users where username = ?";
  $selectStmt = $db_conn->prepare($sqlstatement);
  $selectStmt->bind_param("s", $_SESSION["user"]);
  $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail, $rRole, $rStatus, $rFirstname, $rLastname, $rSex);
  $selectStmt->execute();
  $selectStmt->fetch();
  $selectStmt->close();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'head.php'; ?>

  <title>Login</title>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
  <?php include('nav.php'); ?>
  <div class="container mt-5">

    <?php if (!is_session_active()): ?>
      <div class="col-12 col-lg-6 mb-4 mb-md-6">
        <h1 class="text-bg-secondary p-3 border">Login</h1>
      </div>
      <?php if (isset($error) && $error): ?>
        <div class="col-12 col-lg-6 mb-4 mb-md-6 alert alert-danger" role="alert">
          User und/oder Passwort falsch!
        </div>
      <?php endif ?>

      <form action="login.php" method="POST" class="row">
        <div class="form-group col-12 col-lg-6 mb-4">
          <label class="label-head col-2" for="userid">Benutzername</label>
          <input class="form-control input-field col-3" name="formUserid" id="userid" type="text"
            placeholder="Benutzername" required />


        </div>
        <div class="form-group col-12 col-lg-6 mb-4">

          <label class="label-head col-2" for="password">Passwort</label>
          <input class="form-control input-field col-3" name="formPassword" id="password" type="password"
            placeholder="password" required>
          </input>


        </div>

        <div class="col-12 col-lg-6 mb-4 mb-md-6">
          <input class="btn btn-primary col-3" type="submit" value="login" />
        </div>

      </form>
    <?php else: ?>

      <h4>Herzlich willkommen, <?php echo $_SESSION["user"]; ?></h4>


    <?php endif ?>


</body>

</html>