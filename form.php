<?php
include 'inc/tools.php';

session_start();
$_SESSION["name"]="stefan";


//var_dump($_GET);
//var_dump($_REQUEST);

if (isset($_GET["name"])) {
    $enteredName = clean_input($_GET["name"]);
    $requestType = clean_input($_GET["requestType"]);
    $enteredEmail = clean_input($_GET["email"]);

    $errors = [];
    $errors["name"] = false;
    $errors["email"] = false;

    if (empty($enteredName)) {
        $errors["name"] = true;
    }

    if (empty($enteredEmail)) {
        $errors["email"] = true;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<header> <?php if (isset($_SESSION["name"])) echo $_SESSION["name"]; ?></header>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <title>Login</title>
</head>

<body>
    <main>
        <h2>Kontaktformular</h2>

        <?php echo "Hallo " . $enteredName;
        echo "Wir danken für Ihre " . $requestType; ?>
        <div class="container">

            <form action="form.php" method="GET">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control <?php if ($errors["name"]) 
                        echo "is-invalid"; elseif (isset($errors["name"])) echo "is-valid";  ?>" name="name" id="floatingInput" placeholder="Ihr Name" value = "<?php if (isset($enteredName)) echo $enteredName; ?>">
                    <label for="floatingInput">Name</label>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control <?php if ($errors["email"]) 
                        echo "is-invalid"; elseif (isset($errors["email"])) echo "is-valid";  ?>" name="email" id="floatingPassword" placeholder="Email" value = "<?php if (isset($enteredEmail)) echo $enteredEmail; ?>">
                    <label for="floatingPassword">Email</label>
                </div>

                <select name="requestType">
                    <option>Anfrage</option>
                    <option>Beschwerde</option>
                </select>
                <br />
                <textarea name="request" placeholder="Ihr Anliegen"></textarea>
                <input class="btn btn-primary" type="submit" value="Abschicken" />

            </form>
        </div>
    </main>

</body>

</html>