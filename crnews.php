<?php require 'inc/tools.php';
include 'inc/daten.php';



$fileError = [];
$fileError["size"] = FALSE;
$fileError["uploadok"] = FALSE;
$fileError["type"] = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Allowed file types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];


    // Check file extension
    $uploadDir = "uploads/news/";

    $cleaned_newsheader = clean_input($_POST["formnewsHeader"]);
    $cleaned_newstext = clean_input($_POST["formnewsText"]);


    if (!file_exists($uploadDir)) {

        mkdir($uploadDir);
    }

    if ($_FILES["upload"]["size"] > 10485760) {
        $fileError["size"] = TRUE;
    } elseif (!in_array($_FILES["upload"]["type"], $allowedTypes)) {
        $fileError["type"] = TRUE;
    } else {

        $userFileName = htmlspecialchars(basename($_FILES["upload"]["name"]));
        $targetPath = $uploadDir . $userFileName;
        move_uploaded_file($_FILES["upload"]["tmp_name"], $targetPath);


        $news = $_SESSION["news"];


        $newsitem = [];
        $newsitem = [$cleaned_newsheader, $cleaned_newstext, $targetPath];

        $news[sizeof($news) + 1] = $newsitem;
        $_SESSION["news"] = $news;


        $fileError["uploadok"] = TRUE;

        $insertStatement = "insert into news (file_path,header,comment,user_id) values (?,?,?,?)";
        $insertStatement = $db_conn->prepare($insertStatement);
        $insertStatement->bind_param("sssi", $targetPath, $cleaned_newsheader, $cleaned_newstext, $rId);

        $insertStatement->execute();


        $currentID = $insertStatement->insert_id;

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
    <div class="container mt-5">

        <?php if (is_session_active()): ?>

            <h4>Newsbeitrag erstellen</h4>
            <p class="mb-4">
                Erstellen Sie hier Ihren Newsbeitrag
            </p>
            <form class="row" action="./crnews.php" method="POST" enctype="multipart/form-data">

                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="Newsheader">Titel</label>
                    <input class="form-control" name="formnewsHeader" id="Newsheader" type="text" required
                        placeholder="Titel" value="" />


                </div>
                <div class="col-12 col-lg-6 form-group mb-4 mb-md-6">
                    <label for="newstext">Text</label>
                    <textarea class="form-control" name="formnewsText" id="inputMessage" rows="4"
                        placeholder="Deine Nachricht" value="<?php if (isset($cleaned_newstext))
                            echo $cleaned_newstext; ?>"></textarea>


                </div>
                <div class="form-group col-12 col-lg-6 mb-4">
                    <label class="ml-4" for="upload">Hochladen</label>
                    <input class="form-control" name="upload" id="upload" type="file" required>


                </div>
                <div class="col-12 col-lg-6 mb-4 mb-md-6">
                    <input class="btn btn-primary" type="submit" value="Hochladen" />
                </div>

            </form>

            <?php if ($fileError["size"]): ?>
                <div class="alert alert-danger" role="alert">
                    Leider können wir Dateien größer 10 MB derzeit nicht speichern!
                    Bitte versuchen Sie die Datei zu verkleinern!
                </div>
            <?php endif ?>
            <?php if ($fileError["type"]): ?>
                <div class="alert alert-danger" role="alert">
                    Es sind nur Bilder erlaubt!
                </div>
            <?php endif ?>
            <?php if ($fileError["uploadok"]): ?>
                <div class="alert alert-primary" role="alert">
                    Der Fileupload war ok!
                </div>

            <?php endif ?>



        <?php else: ?>
            <div class="col-12 col-lg-6 mb-4 mb-md-6">
                <h4 class="text-bg-secondary p-3 border">Fileupload nur im eingeloggten Zustand möglich</h4>
            </div>
        <?php endif ?>

        <?php /*    <img src="<?php if (isset($targetPath)) {
echo $targetPath;
} ?> " alt="Girl in a jacket">

<ul>
<?php if (file_exists($uploadDir)) {
$files = scandir($uploadDir);
foreach ($files as $file) {
if(!str_starts_with($file,'.'))
echo "<li>" . $file . "</li>";
}
}
?>
</ul>
*/ ?>



</body>