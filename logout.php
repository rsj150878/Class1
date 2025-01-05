<?php
require 'inc/tools.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <?php include 'head.php'; ?>
  <title>Logout</title>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
  <?php end_session(); ?>
  <?php include 'nav.php'; ?>

  <div class="container mt-5">
    <div class="col-12 col-lg-6 mb-4 mb-md-6">

            <h3 class="mt-5">Wir danken für Ihren Besuch!</h3>
            <p class="mb-1">
                Wir freuen uns sehr, wenn Sie uns wieder einmal besuchen!
            </p>
      <form action="login.php" method="POST">
        <input type="submit" value="Login" />
      </form>
    </div>

</body>

</html>