<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Coookie</title>
</head>
<body>

<?php $value =  'wir testen cookies';
setcookie("firstcookie","Hello first cookie");
$cookieValue = $_COOKIE["firstcookie"];
echo "<h3>Cookie was $cookieValue</h3>";


session_start();
$_SESSION["name"]="stefan"
?>

<h1>Login</h1>
<p>Hallo <?php echo $_SESSION["name"];?></p>
    
</body>
</html>