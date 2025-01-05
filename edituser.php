<?php require 'inc/tools.php';
include 'inc/daten.php'; //$error=False; //if ($_SERVER["REQUEST_METHOD"]=="POST" )
//{ // $error=create_session(clean_input($_POST["formUserid"]), clean_input($_POST["formPassword"])); //} 

if (is_session_active()) {
    // $current_user_data = get_userdaten($_SESSION["user"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST["user_id"])) {

        $userId = $_POST["user_id"];

        include("conf/dbaccess.php");


        $db_conn = new mysqli($host, $user, $password, $dbname, $port);
        $sqlstatement = "select id, username, password, useremail, role, status, firstname, lastname, sex from users where id = ?";
        $selectStmt = $db_conn->prepare($sqlstatement);
        $selectStmt->bind_param("i", $userId);
        $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail, $rRole, $rStatus, $rFirstname, $rLastname, $rSex);
        $selectStmt->execute();
        $selectStmt->fetch();
        $selectStmt->close();

    }

    if (isset($_POST["Geschlecht"])) {

        $errors = [];
        $errors["password"] = false;
        $errors["emptypassword"] = false;
        $errors["uservorh"] = false;

        $cleaned_password = clean_input($_POST["formPassword"]);

        $cleaned_repeated_password = clean_input($_POST["formPasswordRepeat"]);
        $cleaned_user = clean_input($_POST["formUserid"]);
        $geschlecht = clean_input($_POST["Geschlecht"]);
        $cleanedFirstName = clean_input($_POST["formVorname"]);
        $cleanedLastName = clean_input($_POST["formNachname"]);
        $cleanedStatus = clean_input($_POST["Status"]);

        if (!($cleaned_password === $cleaned_repeated_password)) {
            $errors["password"] = true;
        }

        if (!empty($cleaned_password) && !empty($cleaned_repeated_password)) {

           // echo "in update";
            update_password($rId, $cleaned_password);

        }
        if (empty($cleaned_password) || empty($cleaned_repeated_password)) {
            $errors["emptypassword"] = true;
        }

        if ($cleaned_user !== $rUsername) {

            echo $cleaned_user;

            $errors["uservorh"] = check_user_vorhanden_db($cleaned_user);

        }

        if (
            !$errors["password"] &&
            !$errors["uservorh"]
        ) {



            update_user($rId, $cleaned_user, $cleanedFirstName, $cleanedLastName, $rEmail, $geschlecht, $cleanedStatus);

            include("conf/dbaccess.php");
            $db_conn = new mysqli($host, $user, $password, $dbname,$port);

            $sqlstatement = "select id, username, password, useremail, role, status, firstname, lastname, sex from users where id = ?";
            $selectStmt = $db_conn->prepare($sqlstatement);
            $selectStmt->bind_param("i", $rId);
            $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail, $rRole, $rStatus, $rFirstname, $rLastname, $rSex);
            $selectStmt->execute();
            $selectStmt->fetch();
            $selectStmt->close();

        }
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

                <h1 class="text-bg-secondary p-3 border">User bearbeiten <?php echo $rUsername; ?></h1>
            </div>

            <?php if (isset($errors) && $errors["uservorh"]): ?>
                <div class="alert alert-secondary" role="alert">
                    User bereits vorhanden
                </div>

            <?php endif ?>


            <form class="row" action="./edituser.php" method="POST">
                <div class="col-12 col-lg-6 mb-4 mb-md-6 form-group">
                    <label for="Geschlecht">Geschlecht</label>
                    <select class="form-select" id="geschlecht" name="Geschlecht" required>
                        <option value="" disabled selected hidden>bitte wählen</option>
                        <option value="Herr" <?php if (isset($rSex) && $rSex == 'Herr')
                            echo ' selected="selected"'; ?>>Herr
                        </option>
                        <option value="Frau" <?php if (isset($rSex) && $rSex == 'Frau')
                            echo ' selected="selected"'; ?>>Frau
                        </option>
                        <option value="Divers" <?php if (isset($rSex) && $rSex == 'Divers')
                            echo ' selected="selected"'; ?>>
                            Divers</option>

                    </select>

                </div>

                <div class="col-12 col-lg-6 mb-4 mb-md-6 form-group">
                    <label for="Status">Status</label>
                    <select class="form-select" id="status" name="Status" required>
                        <option value="" disabled selected hidden>bitte wählen</option>
                        <option value="ACTIVE" <?php if (isset($rStatus) && $rStatus == 'ACTIVE')
                            echo ' selected="selected"'; ?>>Aktiv
                        </option>
                        <option value="INACTIVE" <?php if (isset($rStatus) && $rStatus == 'INACTIVE')
                            echo ' selected="selected"'; ?>>Inaktiv
                        </option>

                    </select>

                </div>

                <div class="form-group col-12 col-lg-6 mb-4">
                    <label class="ml-4" for="vorname">Vorname</label>
                    <input class="form-control" name="formVorname" id="vorname" type="text" required placeholder="Vorname"
                        value="<?php if (isset($rFirstname))
                            echo $rFirstname; ?>" />


                </div>
                <div class="form-group col-12 col-lg-6 mb-4">
                    <label for="nachname">Nachname</label>
                    <input class="form-control" name="formNachname" id="nachname" type="text" required
                        placeholder="Nachname" value="<?php if (isset($rLastname))
                            echo $rLastname; ?>" />




                </div>
                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="userid">Benutzername</label>
                    <input class="form-control" name="formUserid" id="userid" type="text" required
                        placeholder="Benutzername" value="<?php if (isset($rUsername))
                            echo $rUsername; ?>" />



                </div>

                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="oldpassword">Passwort</label>
                    <input class="form-control <?php if (isset($errors["oldpassword"]) && $errors["oldpassword"])
                        echo "is-invalid";
                    elseif (isset($errors["formPassword"]) && !$errors["formPassword"])
                        echo "is-valid"; ?>" name="formPassword" id="password" type="password" placeholder="Passwort">
                    </input>


                </div>


                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="passwordRepeat">Password wiederholen</label>
                    <input class="form-control <?php if (!isset($errors["password"]))
                        echo "";
                    elseif ($errors["password"])
                        echo "is-invalid";
                    elseif (isset($errors["password"]))
                        echo "is-valid"; ?>" name="formPasswordRepeat" id="passwordRepeat" type="password"
                        placeholder="Passwort wiederholen">
                    </input>


                </div>

                <!-- verstecktes eingabefeld, um die id im formular zu haben -->
                <input type="hidden" name="user_id" <?php echo 'value="' . $rId . '"' ?> />


                <div class="col-12 col-lg-6 mb-4 mb-md-6">
                    <input class="btn btn-primary" type="submit"
                        value=" <?php echo is_session_active() ? 'Daten aktualisieren' : 'Registrierung abschicken'; ?>" />
                </div>
                </>
            </form>
        <?php else: ?>
            <div class="col-12 col-lg-6 mb-4 mb-md-6">
                <h1 class="text-bg-secondary p-3 border">Reservierung nur im eingeloggten Zustand möglich</h1>
            </div>
        <?php endif ?>




</body>