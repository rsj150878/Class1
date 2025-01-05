<?php require 'inc/tools.php';
include 'inc/daten.php';



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'head.php'; ?>

  <title>Userlist</title>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
  <?php include 'nav.php'; ?>
  <div class="container mt-5">

    <?php if (is_session_active()): ?>


      <div class="col-12 col-lg-6 mb-4 mb-md-6">

        <h1 class="text-bg-secondary p-3 border">Userlist</h1>
      </div>

      <?php
      require_once("conf/dbaccess.php");
      $db_conn = new mysqli($host, $user, $password, $dbname, $port);

      $sqlstatement = "select id, username, useremail, firstname, lastname, sex, role, status from users ";
      $selectStmt = $db_conn->prepare($sqlstatement);
      $selectStmt->bind_result($rId, $rUsername, $rUseremail, $rFirstname, $rLastname, $rSex, $rRole, $rStatus);
      $selectStmt->execute();

      ?>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Username</th>
              <th scope="col">Anrede</th>
              <th scope="col">Vorname</th>
              <th scope="col">Nachname</th>
              <th scope="col">User-Rolle</th>
              <th scope="col">User-Status</th>

              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php

            while ($selectStmt->fetch()) {
              echo "<tr>";
              echo "<td>" . $rId . "</td>";
              echo "<td>" . $rUsername . "</td>";
              echo "<td>" . $rSex . "</td>";
              echo "<td>" . $rFirstname . "</td>";
              echo "<td>" . $rLastname . "</td>";

              echo "<td>" . $rRole . "</td>";
              echo "<td>" . $rStatus . "</td>";

              echo "<td>";
              echo '<form action="edituser.php" method="post">';
              echo '<input type="hidden" name="user_id" value="' . $rId . '" />';
              echo "<button>Edit $rId </button>";
              echo '</form>';

              echo "</td>";
              echo "</tr>";
            }

            ?>






          </tbody>
        </table>
      </div>


    <?php endif ?>


</body>