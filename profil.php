<?php require 'inc/tools.php';
include 'inc/daten.php'; //$error=False; //if ($_SERVER["REQUEST_METHOD"]=="POST" )
//{ // $error=create_session(clean_input($_POST["formUserid"]), clean_input($_POST["formPassword"])); //} 

if (is_session_active()) {


    //$current_user_data = $_SESSION["userdaten"][$_SESSION["user"]];


    include("dbacces.php");

    $db_conn = new mysqli($host, $user, $password, $dbname, $port);
    $sqlstatement = "select id, username, password, useremail, role, status, firstname, lastname, sex from users where username = ?";
    $selectStmt = $db_conn->prepare($sqlstatement);
    $selectStmt->bind_param("s", $_SESSION["user"]);
    $selectStmt->bind_result($rId, $rUsername, $rPassword, $rEmail, $rRole, $rStatus, $rFirstname, $rLastname, $rSex);
    $selectStmt->execute();
    $selectStmt->fetch();
    $selectStmt->close();

}




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    

    //global $users;
    if (isset($_POST["Geschlecht"])) {

        $errors = [];
        $errors["password"] = false;
        $errors["emptypassword"] = false;
        $errors["oldPasswordEmpty"] = false;
        $errors["oldPassworddiff"] = false;
        $errors["uservorh"] = false;

        $cleaned_password = clean_input($_POST["formPassword"]);

        $cleaned_repeated_password = clean_input($_POST["formPasswordRepeat"]);
        $cleaned_user = clean_input($_POST["formUserid"]);
        $oldPassword = clean_input($_POST["formOldPassword"]);
        $geschlecht = clean_input($_POST["Geschlecht"]);
        $cleanedFirstName = clean_input($_POST["formVorname"]);
        $cleanedLastName = clean_input($_POST["formNachname"]);
        $cleanedEmail = clean_input($_POST["formEmail"]);

        if (!($cleaned_password === $cleaned_repeated_password)) {
            $errors["password"] = true;
        }

        if (!empty($cleaned_password) && !empty($cleaned_repeated_password)) {
            if (empty($oldPassword)) {
                $errors["oldPasswordEmpty"] = true;
            } elseif (!checkPassword($_SESSION["user"], $oldPassword)) {
                $errors["oldPassworddiff"] = true;
            } else {
                echo "in update";
                update_password($rId, $cleaned_password);
            }

        }
        if (empty($cleaned_password) || empty($cleaned_repeated_password)) {
            $errors["emptypassword"] = true;
        }

        if ($cleaned_user !== $_SESSION["user"]) {

            echo $cleaned_user;

            $errors["uservorh"] = check_user_vorhanden_db($cleaned_user);

        }

        if (
            !$errors["password"] //&&
           // !$errors["emptypassword"]
        ) {
            $_SESSION["users"][$cleaned_user] = $cleaned_password;

            update_user($rId, $cleaned_user, $cleanedFirstName, $cleanedLastName, $cleanedEmail, $geschlecht,"ACTIVE");

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

</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">

    <?php include 'nav.php'; ?>
    <!--
    <div class="container container-xl position-absolute top-50 start-50 translate-middle">
-->
    <div class="container mt-5">
        <?php if (is_session_active()): ?>


            <div class="col-12 col-lg-6 mb-4 mb-md-6">

                <h1 class="text-bg-secondary p-3 border">Userdaten bearbeiten <?php echo $_SESSION["user"]; ?></h1>
            </div>
        <?php endif ?>

        <?php if (isset($errors) && $errors["uservorh"]): ?>
            <div class="alert alert-secondary" role="alert">
                User bereits vorhanden
            </div>

        <?php endif ?>

        <?php if (isset($errors) && $errors["oldPassworddiff"]): ?>
            <div class="alert alert-secondary" role="alert">
                Altes Passwort falsch
            </div>

        <?php endif ?>


        <?php if (isset($errors) && $errors["oldPasswordEmpty"]): ?>
            <div class="alert alert-secondary" role="alert">
                Altes Passwort leer und muss eingegeben werden
            </div>

        <?php endif ?>

        <form class="row" action="./profil.php" method="POST">
            <div class="col-12 col-lg-6 mb-4 mb-md-6 form-group">
                <label for="Geschlecht">Geschlecht</label>
                <select class="form-select" id="geschlecht" name="Geschlecht" required>
                    <option value="" disabled selected hidden>bitte wählen</option>
                    <option value="Mann" <?php if (isset($rSex) && $rSex == 'Mann')
                        echo ' selected="selected"'; ?>>Mann
                    </option>
                    <option value="Frau" <?php if (isset($rSex) && $rSex == 'Frau')
                        echo ' selected="selected"'; ?>>Frau
                    </option>
                    <option value="Divers" <?php if (isset($rSex) && $rSex == 'Divers')
                        echo ' selected="selected"'; ?>>
                        Divers</option>

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
                    placeholder="Benutzername" value="<?php if (isset($_SESSION["user"]))
                        echo $_SESSION["user"]; ?>" />



            </div>

            <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                <label for="useremail">Email</label>
                <input class="form-control" name="formEmail" id="useremail" type="email" required
                    placeholder="Email" value="<?php if (isset($rEmail))
                        echo $rEmail; ?>" />



            </div>

            <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                <label for="oldpassword">Altes Passwort</label>
                <input class="form-control <?php if (isset($errors["oldpassword"]) && $errors["oldpassword"])
                    echo "is-invalid";
                elseif (isset($errors["oldpassword"]) && !$errors["oldpassword"])
                    echo "is-valid"; ?>" name="formOldPassword" id="oldpassword" type="password"
                    placeholder="AltesPasswort">
                </input>


            </div>

            <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                <label for="password">Password</label>
                <input class="form-control <?php if (!isset($errors["password"]))
                    echo "";
                elseif ($errors["password"])
                    echo "is-invalid";
                elseif (isset($errors["password"]))
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
            <div class="col-12 col-lg-6 mb-4 mb-md-6">
                <input class="btn btn-primary" type="submit"
                    value=" <?php echo is_session_active() ? 'Daten aktualisieren' : 'Registrierung abschicken'; ?>" />
            </div>
            </>
        </form>



</body>