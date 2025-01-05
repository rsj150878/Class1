<?php require 'inc/tools.php';
include 'inc/daten.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'head.php'; ?>

  <title>Reservation</title>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
  <?php include 'nav.php'; ?>
  <div class="container mt-5">


    <?php



    require_once("dbacces.php");
    $db_conn = new mysqli($host, $user, $password, $dbname,$port);

    $sqlstatement = "select id, file_path, header, comment,create_ts from news order by create_ts desc ";
    $selectStmt = $db_conn->prepare($sqlstatement);
    $selectStmt->bind_result($rNewsId, $rfilePath, $rHeader, $rcomment,$rCreateTs);
    $selectStmt->execute();


    $directory = 'uloapds/news/';

    //$files = scandir($directory);


    echo "<div class=\"accordion\" id=\"accordionPanelsStayOpenExample\">";

    while ($selectStmt->fetch()) {
      // Eindeutige ID-Generierung basierend auf $key
      $headingID = "panelsStayOpen-heading-" . $rNewsId;
      $collapseID = "panelsStayOpen-collapse-" . $rNewsId;

      echo "<div class=\"accordion-item\">";
      echo "<h2 class=\"accordion-header\" id=\"$headingID\">
              <button class=\"accordion-button\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#$collapseID\" 
              aria-expanded=\"true\" aria-controls=\"$collapseID\">
              " . htmlspecialchars($rHeader) . "
              </button>
          </h2>";
      echo "<div id=\"$collapseID\" class=\"accordion-collapse collapse show\" aria-labelledby=\"$headingID\">
              <div class=\"accordion-body\">";

      echo "<p>" . htmlspecialchars($rcomment) . "</p>";


      // Bildausgabe
    
      echo "<div class=\"container mt-5 mb-5\">";
      echo "<img src=\"" . htmlspecialchars($rfilePath) . "\" alt=\"" . htmlspecialchars($rHeader) . "\" class=\"img-thumbnail\" style=\"width: 300px;\">";
      echo "</div>";

      echo "<p>Erstellt am: " .$rCreateTs . "</p>";


      echo "      </div>
          </div>";
      echo "</div>"; // Schließt die aktuelle accordion-item
    }

    echo "</div>"; // Schließt das gesamte accordion
    
    ?>







</body>