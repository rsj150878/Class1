<?php require 'inc/tools.php';
include 'inc/daten.php'; //$error=False; //if ($_SERVER["REQUEST_METHOD"]=="POST" )
//{ // $error=create_session(clean_input($_POST["formUserid"]), clean_input($_POST["formPassword"])); //} 


$buchungsStatus = [
    "Neu",
    "Bestätigt",
    "Storniert"
];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["reservation_id"])) {
        $currentID = $_POST["reservation_id"];

        include "dbacces.php";

        $db_conn = new mysqli($host, $user, $password, $dbname, $port);

        $sqlstatement = "select reservation_id, start_date, end_date, room_type, persons, rooms, 
             breakfast, parking, pets, status , create_ts FROM reservation where reservation_id = ?";

        $selectStmt = $db_conn->prepare($sqlstatement);

        $selectStmt->bind_param("i", $currentID);

        $selectStmt->bind_result($reservation_id, $anreise_dat, $abreise_dat, $zimmerkategorie, $cleaned_anzahl_personen, 
             $cleaned_anzahl_zimmer, $fruehstueck, $parkplatz, $haustiere,$dbstatus,$createTs);
        $selectStmt->execute();
        $selectStmt->fetch();
        $selectStmt->close();
    }

}

if (isset($_POST["Zimmerkategorie"])) {

    $zimmerkategorie = clean_input($_POST["Zimmerkategorie"]);
    $dbstatus = isset($_POST["formBuchungsStatus"])  ?clean_input($_POST["formBuchungsStatus"]):"Neu";


    $ok = false;
    $errors = [];
    $errors["datum"] = false;
    $errors["personen"] = false;
    $errors["zimmeranzahl"] = false;
    $errors["beforetoday"] = false;

    $cleaned_anzahl_personen = clean_input($_POST["formanzahlpersonen"]);

    $cleaned_anzahl_zimmer = clean_input($_POST["formZimmeranzahl"]);

    $anreise_dat = $_POST["formAnreise"];
    $abreise_dat = $_POST["formAbreise"];

    $haustiere = isset($_POST["formhaustiere"]) ? true : false;
    $fruehstueck = isset($_POST["formfruehstueck"]) ? true : false;

    $parkplatz = isset($_POST["formparkplatz"]) ? true : false;



    $currentDate = date('Y-m-d'); // Das heutige Datum im gleichen Format

    if (strtotime($anreise_dat) < strtotime($currentDate)) {

        $errors["beforetoday"] = true;
    }
    if ($abreise_dat <= $anreise_dat) {
        $errors["datum"] = true;
    }

    if ($cleaned_anzahl_personen <= 0) {
        $errors["personen"] = true;
    }

    if ($cleaned_anzahl_zimmer <= 0) {
        $errors["zimmeranzahl"] = true;
    }


    if (
        !$errors["datum"] &&
        !$errors["personen"] &&
        !$errors["beforetoday"] &&
        !$errors["zimmeranzahl"]
    ) {

        // Neue Reservierung hinzufügen
        // $_SESSION["reservierungen"][] = $reservierung;

        include("dbacces.php");

        $db_conn = new mysqli($host, $user, $password, $dbname, $port);

        if (!isset($currentID)) {
            $insertStatement = "insert into reservation (start_date, end_date, room_type, persons, rooms, status, breakfast, parking, pets, user_id) values (?,?,?,?,?,?,?,?,?,?)";
            $insertStatement = $db_conn->prepare($insertStatement);
            $insertStatement->bind_param("ssssssiiii", $anreise_dat, $abreise_dat, $zimmerkategorie, $cleaned_anzahl_personen, $cleaned_anzahl_zimmer, $dbstatus, $fruehstueck, $parkplatz, $haustiere, $rId);

            $insertStatement->execute();


            $currentID = $insertStatement->insert_id;

        } else {
            $updateStatement = "update reservation set start_date=?, end_date=?, room_type=?, persons=?,rooms=?, status=?, breakfast=?, parking=?, pets=? where reservation_Id =?";
            $updateStatement = $db_conn->prepare($updateStatement);
            $updateStatement->bind_param("ssssssiiii", $anreise_dat, $abreise_dat, $zimmerkategorie, $cleaned_anzahl_personen, $cleaned_anzahl_zimmer, $dbstatus, $fruehstueck, $parkplatz, $haustiere, $currentID);

            $updateStatement->execute();
        }
        $ok = true;

    }

}



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

                <h1 class="text-bg-secondary p-3 border">Reservierung vornehmen </h1>
            </div>

            <?php if (isset($errors) && $errors["beforetoday"]): ?>
                <div class="alert alert-secondary" role="alert">
                    Das Anreisedatum darf nicht in der Vergangenheit liegen.
                </div>

            <?php endif ?>

            <?php if (isset($errors) && $errors["datum"]): ?>
                <div class="alert alert-secondary" role="alert">
                    Das Abreisedatum muss nach dem Anreisedatum liegen
                </div>

            <?php endif ?>

            <?php if (isset($errors) && $errors["personen"]): ?>
                <div class="alert alert-secondary" role="alert">
                    Die Anzahl der Personen muss größer 0 sein
                </div>

            <?php endif ?>
            <?php if (isset($errors) && $errors["zimmeranzahl"]): ?>
                <div class="alert alert-secondary" role="alert">
                    Die Anzahl der Zimmer muss größer 0 sein
                </div>

            <?php endif ?>

            <?php if (
                isset($ok) && $ok
            ): ?>
                <div class="alert alert-primary" role="alert">
                    Die Reservierung wurde erfolgreich angenommen!
                </div>

            <?php endif ?>

            <form class="row" action="./reservation.php" method="POST">
                <div class="col-12 col-6 col-lg-12 mb-4 mb-md-6 form-group">
                    <label for="zimmerkategorie">Zimmerkategorie</label>
                    <select class="form-select w-100" id="zimmerkategorie" name="Zimmerkategorie" required>
                        <option value="" disabled selected hidden>bitte wählen</option>
                        <option value="Standard" <?php if (isset($zimmerkategorie) && $zimmerkategorie == 'Standard')
                            echo ' selected="selected"'; ?>>
                            Standard
                        </option>
                        <option value="Komfort" <?php if (isset($zimmerkategorie) && $zimmerkategorie == 'Komfort')
                            echo ' selected="selected"'; ?>>
                            Komfort
                        </option>
                        <option value="Exklusiv" <?php if (isset($zimmerkategorie) && $zimmerkategorie == 'Exklusiv')
                            echo ' selected="selected"'; ?>>
                            Exklusiv</option>

                    </select>

                </div>

                <?php if (isset($rRole) && $rRole === 'ADMIN'): ?>
                <div class="col-12 col-6 col-lg-12 mb-4 mb-md-6 form-group">
                    <label for="status">Buchungsstatus</label>
                    <select class="form-select w-100" id="buchungsStatus" name="formBuchungsStatus" required>

                        <?php foreach( $buchungsStatus as $status) {
                            echo '<option value="';
                            echo $status;
                            echo '" ';

                            if (isset($dbstatus) && $dbstatus == $status) {
                                echo ' selected="selected" '; 
                            }
                            echo $status; 
                        
                            echo '</option>';
                            print $status;
                        }
                        ?>

                    </select>

                </div>

                <?php endif; ?>

                <div class="form-group col-md-6 col-sm-12 col-lg-3 mb-4">
                    <label class="ml-4" for="anreise">Anreise</label>
                    <input class="form-control" name="formAnreise" id="anreise" type="date" required value="<?php if (isset($anreise_dat))
                        echo $anreise_dat; ?>" />


                </div>
                <div class="form-group  col-md-6 col-sm-12 col-lg-3 mb-4">
                    <label for="abreise">Abreise</label>
                    <input class="form-control" name="formAbreise" id="abreise" type="date" required value="<?php if (isset($abreise_dat))
                        echo $abreise_dat; ?>" />

                </div>
                <div class=" col-lg-3 col-md-6  col-sm-12 form-group mb-4 mb-md-6">
                    <label for="zimmeranzahl">Anzahl zimmer</label>
                    <input class="form-control" name="formZimmeranzahl" id="zimmeranzahl" type="number" required
                        placeholder="0" value="<?php if (isset($cleaned_anzahl_zimmer))
                            echo $cleaned_anzahl_zimmer; ?>" />



                </div>

                <div class=" col-lg-3 col-md-6 col-sm-12 form-group mb-4 mb-md-6">
                    <label for="anzahlpersonen">Personenanzahl</label>
                    <input class="form-control " name="formanzahlpersonen" id="anzahlpersonen" type="number" placeholder="0"
                        value="<?php if (isset($cleaned_anzahl_personen))
                            echo $cleaned_anzahl_personen; ?>" required>
                    </input>


                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 form-group mb-4 mb-md-6">
                    <label for="fruehstueck">Frühstück</label>
                    <input class="form-check-input " name="formfruehstueck" id="fruehstueck" type="checkbox"
                    <?php if (isset($fruehstueck)) { echo $fruehstueck==1 ? 'checked' : '';}?>></input>


                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 form-group mb-4 mb-md-6">
                    <label for="haustiere">Haustiere</label>
                    <input class="form-check-input " name="formhaustiere" id="haustiere" type="checkbox"

                    <?php if (isset($haustiere)) { echo $haustiere==1 ? 'checked' : '';}?>
                    ></input>
                </div>

                <div class=" col-lg-3 col-md-6 col-sm-12 form-group mb-4 mb-md-6">
                    <label for="parkplatz">Parkplatz</label>
                    <input class="form-check-input " name="formparkplatz" id="parkplatz" type="checkbox"  
                    <?php if (isset($parkplatz)) { echo ($parkplatz==1 ? 'checked' : ''); }?>></input>


                </div>

                <?php if (isset($rRole) && $rRole === 'ADMIN'): ?>
                    <div class="row">
  
                    <p class="text-left">Zeitpunkt der Erstellung der Reservierung:
                    <?php echo isset($createTs)?$createTs:""; ?>
                </p>
                    <?php endif; ?>


                <!-- verstecktes eingabefeld, um die id im formular zu haben -->

                <?php if (isset($currentID)): ?>
                    <input type="hidden" name="reservation_id" <?php echo 'value="' . $currentID . '"' ?> />
                <?php endif ?>

                <div class="col-lg-6 col-md-6 col-sm-12 col-6 mb-4 mb-md-6">
                    <input class="btn btn-primary" type="submit" value="Reservierung abschicken" />
                </div>




            </form>


        <?php else: ?>
            <div class="col-12 col-lg-6 mb-4 mb-md-6">
                <h1 class="text-bg-secondary p-3 border">Reservierung nur im eingeloggten Zustand möglich</h1>
            </div>
        <?php endif ?>




</body>