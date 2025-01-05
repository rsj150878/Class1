<?php require 'inc/tools.php';
include 'inc/daten.php'; //$error=False; //if ($_SERVER["REQUEST_METHOD"]=="POST" )
//{ // $error=create_session(clean_input($_POST["formUserid"]), clean_input($_POST["formPassword"])); //} 


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

        <?php if (is_session_active()): ?>


            <div class="col-12 col-lg-6 mb-4 mb-md-6">

                <h1 class="text-bg-secondary p-3 border">Ihre Reservierungen
                    <?php echo $rSex . " " . $rFirstname . " " . $rLastname ?>
                </h1>
            </div>

            <?php
            require_once("conf/dbaccess.php");
            $db_conn = new mysqli($host, $user, $password, $dbname,$port);

            $sqlstatement = "select reservation_id, start_date, end_date, room_type, persons, rooms, 
            status, breakfast, parking, pets, user_id, create_ts FROM reservation ";

            if (!($rRole === "ADMIN")) {

                $sqlstatement = $sqlstatement . " where user_id = ?";
            }

            $selectStmt = $db_conn->prepare($sqlstatement);

            if (!($rRole === "ADMIN")) {
                $selectStmt->bind_param("i", $rId);
            }


            $selectStmt->bind_result($reservation_id, $rstartDat, $rEndDat, $rRoomType, $rPersons, $rRooms, $rStatus, $rBreakfast, $rParking, $rPets, $rUserId, $rCreationDate);
            $selectStmt->execute();

            ?>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nr.</th>
                        <th scope="col">Anreise</th>
                        <th scope="col">Abreise</th>
                        <th scope="col">Zimmerkategorie</th>
                        <th scope="col">Anzahl Personen</th>
                        <th scope="col">Anzahl Zimmer</th>
                        <th scope="col">Haustiere</th>
                        <th scope="col">Frühstück</th>
                        <th scope="col">Parkplatz</th>
                        <th scope="col">Status</th>
                        <?php if (isset($rRole) && $rRole === 'ADMIN'): ?>
                            <th scope="col">User</th>
                            <th scope="col">Res-Zp</th>
                        <?php endif; ?>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


                    while ($selectStmt->fetch()) {
                        echo "<tr>";
                        echo "<td>" . $reservation_id . "</td>";
                        echo "<td>" . $rstartDat . "</td>";
                        echo "<td>" . $rEndDat . "</td>";
                        echo "<td>" . $rRoomType . "</td>";
                        echo "<td>" . $rPersons . "</td>";
                        echo "<td>" . $rRooms . "</td>";
                        echo '<td><input type="checkbox" name="pets" disabled="disabled"';
                        if (isset($rPets)) {
                            echo $rPets == 1 ? 'checked' : '';
                        }
                        ;
                        echo "></td>";

                        echo '<td><input type="checkbox" name="breakfast" disabled="disabled"';
                        if (isset($rBreakfast)) {
                            echo $rBreakfast == 1 ? 'checked' : '';
                        }
                        ;
                        echo "></td>";

                        echo '<td><input type="checkbox" name="parkplatz" disabled="disabled"';

                        if (isset($rParking)) {
                            echo $rParking == 1 ? 'checked' : '';
                        }
                        ;
                        echo "></td>";


                        echo "<td>" . $rStatus . "</td>";
                        if (isset($rRole) && $rRole === 'ADMIN'){
                            echo  "<td>" . $rUserId . "</td>";
                            echo  "<td>" . $rCreationDate . "</td>";
                        }
                        
                        echo "<td>";
                        
                        echo '<form action="reservation.php" method="post">';
                        echo '<input type="hidden" name="reservation_id" value="' . $reservation_id . '" />';
                        echo "<button>Edit $reservation_id </button>";
                        echo '</form>';
                        echo "</tr>";
                        
                        
                    }
                    //  reservierung = [$anreise_dat, $abreise_dat, $cleaned_anzahl_personen, $cleaned_anzahl_zimmer, $_POST["formhaustiere"], $_POST["formfruehstueck"]];
                
                    ?>
                </tbody>
            </table>

        <?php endif ?>


</body>