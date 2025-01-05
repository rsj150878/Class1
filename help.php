<?php
require 'inc/tools.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'head.php'; ?>
    <title>Impressum</title>
</head>

<body class="p-3 m-0 border-0 bd-example">
    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <div class="col-12 col-lg-6 mb-4">
            <h3 class="mt-5">Hilfe</h3>
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            Wie kann ich Reservierungen vornehmen?
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Reservierungen können nur vorgenommen werden, wenn der User
                            eingeloggt ist.
                            Es müssen der gewünschte Zeitraum, die Zimmerkategorie und die Personenanzahl ausgewählt
                            werden.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            Übersicht der Reservierungen
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Eine Übersicht der bestehenden Reservierungen ist geplant und in Kürze verfügbar.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                            Weitere Fragen?
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Sollten Sie noch weitere fragen haben, wenden sie sich bitte an <a
                                href="mailto:support@nodomain.com">support@nodomain.com</a>. Wir sind bemüht, Ihre Anfrage
                            so schnell wie möglich zu beantworten!
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>