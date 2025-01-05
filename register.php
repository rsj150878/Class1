<?php require 'inc/tools.php';
include 'inc/daten.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    if (isset($_POST["Geschlecht"])) {

        $errors = [];
        $errors["password"] = false;
        $errors["emptypassword"] = false;

        $cleaned_password = clean_input($_POST["formPassword"]);

        $cleaned_repeated_password = clean_input($_POST["formPasswordRepeat"]);
        $cleaned_user = clean_input($_POST["formUserid"]);

        $geschlecht = $_POST["Geschlecht"];
        $vorname = clean_input($_POST["formVorname"]);
        $nachname= clean_input($_POST["formNachname"]);
        $geschlecht = clean_input($_POST["Geschlecht"]);
        $cleaned_email = clean_input($_POST["formEmail"]);


        if (!empty($cleaned_password) && !empty($cleaned_repeated_password)) {

            
            
        }

        if (!($cleaned_password === $cleaned_repeated_password)) {
            $errors["password"] = true;
        }

        if (empty($cleaned_password) || empty($cleaned_repeated_password)) {
            $errors["emptypassword"] = true;
        }

        if (!is_session_active()) {


            $errors["uservorh"] = check_user_vorhanden_db($cleaned_user);

        }

        if (
            !$errors["password"] &&
            !$errors["emptypassword"] &&
            !$errors["uservorh"]
        ) {
           

            $_SESSION["users"][$cleaned_user] = $cleaned_password;
            insert_user_db($cleaned_user, $cleaned_password, $vorname, $nachname, $cleaned_email,$geschlecht);
            create_session_db($cleaned_user, $cleaned_password);

           
            $_SESSION["userdaten"][$cleaned_user] = [clean_input($_POST["Geschlecht"]), clean_input($_POST["formVorname"]), clean_input($_POST["formNachname"]), "Höchstädtplatz", "test-john@no-domain.com"];

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

                <<?php 
     header('Location: news.php');
     
?>

            </div>


        <?php else: ?>
            <div class="col-12 col-lg-6 mb-4 mb-md-6">
                <h1 class="text-bg-secondary p-3 border">Registrierung</h1>
            </div>
        <?php endif ?>

        <?php if (isset($errors) && $errors["uservorh"]): ?>
            <div class="alert alert-secondary" role="alert">
                User bereits vorhanden
            </div>

        <?php endif ?>

        <?php if (!is_session_active()): ?>
            <form class="row" action="./register.php" method="POST">
                <div class="col-12 col-lg-6 mb-4 mb-md-6 form-group">
                    <label for="Geschlecht">Geschlecht</label>
                    <select class="form-select" id="geschlecht" name="Geschlecht" required>
                        <option value="" disabled selected hidden>bitte wählen</option>
                        <option value="Herr" <?php if (isset($geschlecht) && $geschlecht == 'Herr')
                            echo ' selected="selected"'; ?>>Herr
                        </option>
                        <option value="Frau" <?php if (isset($geschlecht) && $geschlecht == 'Frau')
                            echo ' selected="selected"'; ?>>Frau
                        </option>
                        <option value="Divers" <?php if (isset($geschlecht) && $geschlecht == 'Divers')
                            echo ' selected="selected"'; ?>>
                            Divers</option>

                    </select>

                </div>

                <div class="form-group col-12 col-lg-6 mb-4">
                    <label class="ml-4" for="vorname">Vorname</label>
                    <input class="form-control" name="formVorname" id="vorname" type="text" required placeholder="Vorname"
                        value="<?php if (isset($vorname))
                            echo $vorname; ?>" />


                </div>
                <div class="form-group col-12 col-lg-6 mb-4">
                    <label for="nachname">Nachname</label>
                    <input class="form-control" name="formNachname" id="nachname" type="text" required
                        placeholder="Nachname" value="<?php if (isset($nachname))
                            echo $nachname; ?>" />




                </div>
                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="userid">Benutzername</label>
                    <input class="form-control" name="formUserid" id="userid" type="text" required
                        placeholder="Benutzername" value="<?php if (isset( $cleaned_user))
                            echo  $cleaned_user; ?>" />



                </div>

                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="email">Emailadresse</label>
                    <input class="form-control" name="formEmail" id="email" type="email" required
                        placeholder="Email" value="<?php if (isset( $cleaned_email))
                            echo  $cleaned_email; ?>" />



                </div>

                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="password">Password</label>
                    <input class="form-control <?php if ($errors["password"])
                        echo "is-invalid";
                    elseif (isset($errors["password"]))
                        echo "is-valid"; ?>" name="formPassword" id="password" type="password" placeholder="Passwort"
                        required>
                    </input>


                </div>
                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="passwordRepeat">Password wiederholen</label>
                    <input class="form-control <?php if ($errors["password"])
                        echo "is-invalid";
                    elseif (isset($errors["password"]))
                        echo "is-valid"; ?>" name="formPasswordRepeat" id="passwordRepeat" type="password" required
                        placeholder="Passwort wiederholen">
                    </input>


                </div>
                <div class="col-12 col-lg-6 mb-4 mb-md-6">
                    <input class="btn btn-primary" type="submit"
                        value=" <?php echo is_session_active() ? 'Daten aktualisieren' : 'Registrierung abschicken'; ?>" />
                </div>
                </>
            </form>

        <?php endif ?>


</body>