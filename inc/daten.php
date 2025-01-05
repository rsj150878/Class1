<?php


if (!isset($_SESSION["userdaten"] )) {
    
$userdaten = [];
$userdaten["andrea"] = ["Frau", "Andrea", "Test", "Höchstädtplatz", "test@no-domain.com"];
$userdaten["john"] = ["Mann", "John", "Test", "Höchstädtplatz", "test-john@no-domain.com"];
$_SESSION["userdaten"][] = $userdaten;
}

function get_userdaten($registeredUser)
{

   

    return $_SESSION["userdaten"][$registeredUser];
}

$zimmerliste = [];
$zimmerliste["standard"] = ["Standard", "Standardzimmer", "2 pers"];
$zimmerliste["komfort"] = ["Komfort", "Komfortzimmer", "2 Pers"];
$zimmerliste["exklusiv"] = ["Exklusiv", "Exklusivzimmer", "1 Pers"];
function get_zimmerinfo($selectedRoom)
{

    global $zimmerliste;

    return $zimmerliste[$selectedRoom];
}


if (!isset($_SESSION["users"])) {
    $users = [];
    $users["andrea"] = "test1";
    $users["john"] = "test2";
    $_SESSION["users"] = $users;
}

if (!isset($_SESSION["news"])) {
    $news = [];
    $news[0] = ["Lorum ipsum 1","Lorem ipsum dolor sit amet, consectetur adipisici elit",""];
    $news[1] = ["Lorum ipsum 2","Lorem ipsum dolor sit amet, consectetur adipisici elit",""];
    $_SESSION["news"] = $news;
}
