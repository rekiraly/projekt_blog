<?php

session_name("blogProject");
session_start();
require_once "include/config.inc.php";
require_once "include/db.inc.php";
/*require_once "include/form.inc.php";
include_once "include/dateTime.inc.php";

require_once "class/iBlog.class.php";
require_once "class/iCategory.class.php";
require_once "class/iUser.class.php";

require_once "class/Blog.class.php";
require_once "class/Category.class.php";
require_once "class/User.class.php";
 */
$thema_id = 0;

/**********************************************/
/********** FETCH CATEGORIES FROM DB **********/
/**********************************************/
//
if (isset($_GET['thema_id'])) {
    //header("Content-Type: text/html; charset=UTF-8");
    $thema_id = $_REQUEST['thema_id'];

    $pdo = dbConnect();
    $sql = "SELECT * FROM category WHERE thema_id = ?";
    $params = [$thema_id];

    // Schritt 2 DB: SQL-Statement vorbereiten
    $statement = $pdo->prepare($sql);
    // Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
    $statement->execute($params);

    // Kategorien aus DB zur späteren Verwendung in Array speichern
    $categoriesArray = $statement->fetchAll();

    //echo "<pre class='debug'>\r\n";
    //print_r($categoriesArray);
    //echo "</pre>\r\n";
    $JsonCatArr = json_encode($categoriesArray);
    echo $JsonCatArr;

}
/***************************************************************************************/
